<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberPhotos;
use App\Models\MemberWallet;
use App\Models\MembershipPlan;
use App\Services\ProfileUnlockPricing;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MemberJourneyTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // The legacy members table has many required columns without defaults.
        // This session setting mirrors its historic permissive insert behavior
        // while keeping every test inside a transaction on himrishtey_test.
        DB::statement("SET SESSION sql_mode = ''");
    }

    public function test_public_support_and_pricing_pages_are_available(): void
    {
        foreach (['/contact-us', '/child-safety-standard', '/pricing', '/refund-policy'] as $uri) {
            $this->get($uri)->assertOk();
        }

        $this->get('/refun-policy')
            ->assertRedirect('/refund-policy');
    }

    public function test_landing_dashboard_cta_uses_member_authentication_state(): void
    {
        $this->get(route('welcome'))
            ->assertOk()
            ->assertSee('href="' . route('login-form') . '" class="btn-cta-secondary"', false);

        $member = $this->createMember();

        $this->actingAs($member, 'member')
            ->get(route('welcome'))
            ->assertOk()
            ->assertSee('href="' . route('home') . '" class="btn-cta-secondary"', false);
    }

    public function test_member_can_log_in_and_access_authenticated_search(): void
    {
        $member = $this->createMember();

        $this->postJson(route('member-login'), [
            'username' => $member->email,
            'password' => 'test-password',
            'captcha' => 3,
        ])->assertUnprocessable();

        $this->withSession(['captcha_answer' => 3])
            ->postJson(route('member-login'), [
                'username' => $member->email,
                'password' => 'test-password',
                'captcha' => 3,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect', route('home'));

        $this->actingAs($member, 'member')
            ->get(route('home'))
            ->assertOk()
            ->assertViewIs('dashboard.home');

        $this->actingAs($member, 'member')
            ->get(route('quick-search'))
            ->assertOk()
            ->assertViewIs('dashboard.search.quick-search');
    }

    public function test_search_results_clear_url_matches_the_originating_search_page(): void
    {
        $member = $this->createMember();

        $this->actingAs($member, 'member')
            ->get(route('search-results', ['_source' => 'quick']))
            ->assertOk()
            ->assertViewHas('searchReturnUrl', route('quick-search'));

        $this->actingAs($member, 'member')
            ->get(route('search-results', ['_source' => 'advanced']))
            ->assertOk()
            ->assertViewHas('searchReturnUrl', route('advance-search'));

        $this->actingAs($member, 'member')
            ->get(route('search-results', ['height_from' => '4.6']))
            ->assertOk()
            ->assertViewHas('searchSource', 'advanced')
            ->assertViewHas('searchReturnUrl', route('advance-search'));
    }

    public function test_member_can_request_a_persistent_login(): void
    {
        $member = $this->createMember();

        $this->withSession(['captcha_answer' => 3])
            ->postJson(route('member-login'), [
                'username' => $member->email,
                'password' => 'test-password',
                'captcha' => 3,
                'remember' => true,
            ])
            ->assertOk()
            ->assertCookie(Auth::guard('member')->getRecallerName());

        $this->assertDatabaseHas('member_remember_tokens', [
            'member_id' => $member->id,
        ]);
    }

    public function test_member_pages_render_without_server_errors(): void
    {
        $member = $this->createMember();

        foreach ([
            'memberships',
            'referral',
            'member.terms-and-conditions',
            'user-rating',
            'member.success-stories',
            'profile',
            'interest-box',
            'view-my-profile',
            'viewed-contacts',
            'edit-profile',
            'delete-profile',
            'change-password',
            'recent-profiles',
            'all-recent-profiles',
            'stats-profiles',
            'all-stats-profiles',
            'wallet.index',
            'member.privacy-policy',
            'member.refund-policy',
            'verify-account',
        ] as $route) {
            $this->actingAs($member, 'member')
                ->get(route($route))
                ->assertOk();
        }
    }

    public function test_dashboard_shows_the_member_active_plan(): void
    {
        $plan = MembershipPlan::forceCreate([
            'membership_type' => 'Premium',
            'plan_name' => 'Gold Plus',
            'duration_days' => 30,
            'view_contact' => 10,
            'view_profile' => 100,
            'plan_cost' => 1000,
            'discount_percentage' => 0,
            'final_cost' => '1000',
        ]);
        $member = $this->createMember(['plan_id' => $plan->id]);

        $this->actingAs($member, 'member')
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Gold Plus');
    }

    public function test_membership_plans_map_to_the_expected_wallet_rewards(): void
    {
        $rewards = [
            'SILVER' => 60,
            'Gold' => 220,
            'GOLD+' => 600,
            'Premium+' => 600,
            'SILVER+' => 0,
        ];

        foreach ($rewards as $planName => $expectedReward) {
            $plan = new MembershipPlan();
            $plan->plan_name = $planName;

            $this->assertSame($expectedReward, $plan->walletRewardPoints());
        }
    }

    public function test_profile_unlock_prices_follow_view_count_tiers(): void
    {
        $prices = [
            0 => 3,
            20 => 3,
            21 => 8,
            50 => 8,
            51 => 15,
            100 => 15,
            101 => 25,
            200 => 25,
            201 => 50,
            1000 => 50,
        ];

        foreach ($prices as $viewCount => $expectedPrice) {
            $this->assertSame(
                $expectedPrice,
                ProfileUnlockPricing::priceForViewCount($viewCount)
            );
        }
    }

    public function test_edit_profile_retains_saved_section_completion_statuses(): void
    {
        $member = $this->createMember([
            'manglik' => 'No',
            'birth_place' => 'Shimla',
        ]);

        $this->actingAs($member, 'member')
            ->get(route('edit-profile'))
            ->assertOk()
            ->assertSee('ep-tab-status complete', false)
            ->assertSee('data-lucide="circle"', false);
    }

    public function test_view_my_profile_uses_the_local_profile_photo_url(): void
    {
        $member = $this->createMember(['photo' => 'member-photo-20.webp']);

        $this->actingAs($member, 'member')
            ->get(route('view-my-profile'))
            ->assertOk()
            ->assertSee(asset('photos/photo/member-photo-20.webp'), false)
            ->assertSee('assets/css/profile-detail.css', false)
            ->assertSee('pd-profile-image-wrapper', false)
            ->assertSee('Edit Profile');
    }

    public function test_member_can_add_and_remove_gallery_photos(): void
    {
        $member = $this->createMember();
        $upload = UploadedFile::fake()->image('gallery-photo.png', 320, 480);

        $this->actingAs($member, 'member')
            ->post(route('upload-photos'), ['photos' => [$upload]])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $galleryPhoto = MemberPhotos::where('member_id', $member->id)->firstOrFail();
        $photoPath = public_path('uploads/gallery/' . $galleryPhoto->photo);

        $this->assertFileExists($photoPath);

        $this->actingAs($member, 'member')
            ->delete(route('gallery-photos.destroy', $galleryPhoto->id))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('member_photos', ['id' => $galleryPhoto->id]);
        $this->assertFileDoesNotExist($photoPath);
    }

    public function test_member_can_unlock_a_visible_contact_with_wallet_balance(): void
    {
        $member = $this->createMember();
        $profile = $this->createMember([
            'gender' => 'Female',
            'email' => 'profile-' . uniqid() . '@example.test',
            'mobile_number' => '9111111111',
            'whatsapp_number' => '9222222222',
        ]);

        MemberWallet::create([
            'member_id' => $member->id,
            'wallet_balance' => 100,
            'amount_added' => 100,
            'amount_deducted' => 0,
        ]);

        $this->actingAs($member, 'member')
            // A tampered client price must be ignored in favour of server pricing.
            ->postJson(route('unlock.contact', $profile->id), ['unlock_price' => 0])
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('already_unlocked', false)
            ->assertJsonPath('wallet_balance', 97);

        $this->assertDatabaseHas('viewed_contacts', [
            'member_id' => $member->id,
            'profile_id' => $profile->id,
        ]);

        $this->actingAs($member, 'member')
            ->get(route('view-profile', $profile->profile_id))
            ->assertOk()
            ->assertSee('9111111111')
            ->assertSee('9222222222')
            ->assertSee($profile->email)
            ->assertDontSee('id="contactUnlock"', false);

        $this->actingAs($member, 'member')
            ->postJson(route('unlock.contact', $profile->id), ['unlock_price' => 999])
            ->assertOk()
            ->assertJsonPath('already_unlocked', true)
            ->assertJsonPath('wallet_balance', 97);

        $this->assertSame(1, DB::table('viewed_contacts')
            ->where('member_id', $member->id)
            ->where('profile_id', $profile->id)
            ->count());
    }

    private function createMember(array $overrides = []): Member
    {
        return Member::create(array_merge([
            'registration_date' => now()->toDateTimeString(),
            'profile_id' => 'TEST' . random_int(100000, 999999),
            'profile_created_for' => 'Self',
            'full_name' => 'Regression Test Member',
            'email' => 'member-' . uniqid() . '@example.test',
            'mobile_number' => '9000000000',
            'password' => \Illuminate\Support\Facades\Hash::make('test-password'),
            'gender' => 'Male',
            'birth_date_time' => '1995-01-01 08:00:00',
            'height' => '170',
            'partner_age_from' => '21',
            'partner_age_to' => '35',
            'partner_height_from' => 145,
            'partner_height_to' => 190,
            'active' => 'Yes',
            'profile_hide' => 'No',
        ], $overrides));
    }
}
