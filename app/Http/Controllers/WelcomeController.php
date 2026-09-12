<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\ProfileCreatedFor;
use App\Models\SuccessStory;
use App\Models\MaritalStatus;
use App\Models\Member;
use App\Models\Page;
use App\Models\MembershipPlan;
use App\Models\ContactMessage;
use App\Mail\ContactInquiry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'data' => $this->homeSummary(),
            'featuredProfiles' => app(\App\Services\HomepageProfiles::class)->get(),
            'searchCities' => app(\App\Services\HomepageCities::class)->get(),
        ]);
    }

    public function designPreview()
    {
        return view('pages.design-preview', ['data' => $this->homeSummary()]);
    }

    private function homeSummary(): array
    {
        $siteKey = config('site.current.key', 'default');

        return Cache::remember("{$siteKey}:public-home-summary", now()->addMinutes(5), function (): array {
            return [
                'maleProfile' => Member::where('gender', 'Male')
                    ->where('is_trusted', 'trusted')
                    ->where('active', 'Yes')
                    ->latest('id')
                    ->first(),
                'femaleProfile' => Member::where('gender', 'Female')
                    ->where('member_type', 'Verified')
                    ->where('active', 'Yes')
                    ->latest('id')
                    ->first(),
                'totalprofiles' => Member::count(),
            ];
        });
    }

    public function about()
    {
        $aboutUs = Page::where('id', 1)->value('about_us');
        return view('pages.about-us', compact('aboutUs'));
    }

    public function success_stories()
    {
        $stories =  SuccessStory::where('status', 1)->get();
        return view('pages.success-stories', compact('stories'));
    }

    public function contact(Request $request)
    {
        $first = random_int(1, 9);
        $second = random_int(1, 9);
        $request->session()->put('contact_captcha', [
            'answer' => $first + $second,
            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);

        return view('pages.contact', ['captchaQuestion' => "$first + $second"]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'profile_id' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', Rule::in(ContactMessage::SUBJECTS)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'max:0'],
            'captcha' => ['bail', 'required', 'integer', function ($attribute, $value, $fail) use ($request) {
                $challenge = $request->session()->get('contact_captcha');
                if (!$challenge || $challenge['expires_at'] < now()->timestamp || (int) $value !== $challenge['answer']) {
                    $fail('Please answer the security question correctly. Refresh the page if it has expired.');
                }
            }],
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
            'website.max' => 'Your message could not be submitted.',
            'captcha.required' => 'Please answer the security question.',
            'captcha.integer' => 'Please enter a whole number.',
        ]);

        unset($validated['website'], $validated['captcha']);
        $request->session()->forget('contact_captcha');

        $message = ContactMessage::create($validated + [
            'site_key' => config('site.current.key'),
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
        ]);

        $supportEmail = config('site.current.support_email') ?: config('mail.from.address');
        try {
            Mail::to($supportEmail)->send(new ContactInquiry($message));
        } catch (\Throwable $exception) {
            // The inquiry is already saved; a mail outage must not lose it or
            // prompt the visitor to submit a duplicate.
            report($exception);
        }

        return redirect()->route('contact-us')->with(
            'contact_success',
            'Thank you! Your message has been received. Our support team will contact you shortly.'
        );
    }

    public function privacy_policy()
    {
        $data = Page::where('id', 1)->value('privacy_policy');
        return view('pages.privacy-policy', compact('data'));
    }

    public function refund_policy()
    {
        $data = Page::where('id', 1)->value('refund_policy');
        return view('pages.refund-policy', compact('data'));
    }

    public function terms_and_conditions()
    {
        $data = Page::where('id', 1)->value('terms_and_conditions');
        return view('pages.terms-and-conditions', compact('data'));
    }

    public function child_safety()
    {
        return view('pages.child-safety');
    }

    public function pricing()
    {
        $pricings = Cache::remember('public-membership-plans', now()->addMinutes(10), function () {
            return MembershipPlan::where('id', '>', 0)->get();
        });

        return view('pages.pricing', compact('pricings'));
    }
    
    public function faqs()
    {
        return view('pages.faqs');
    }
}
