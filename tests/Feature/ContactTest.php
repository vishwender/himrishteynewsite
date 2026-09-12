<?php

namespace Tests\Feature;

use App\Mail\ContactInquiry;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        config()->set('site.sites', []);
        config()->set('mail.from.address', 'support@example.com');
        (require database_path('migrations/2026_09_03_000000_create_contact_messages_table.php'))->up();
        Mail::fake();
        $this->withSession(['contact_captcha' => ['answer' => 7, 'expires_at' => now()->addMinutes(30)->timestamp]]);
    }

    private function inquiry(): array
    {
        return [
            'name' => 'Test Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Profile or account help',
            'message' => 'Please help me update my profile.',
        ];
    }

    public function test_guest_inquiry_is_saved_and_emailed(): void
    {
        $this->post('/contact-us', $this->inquiry() + ['captcha' => '7'])
            ->assertRedirect(route('contact-us'))
            ->assertSessionHas('contact_success');
        $this->assertDatabaseHas('contact_messages', $this->inquiry());
        Mail::assertSent(ContactInquiry::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('support@example.com')
                && $mail->hasReplyTo('visitor@example.com');
        });
    }

    public function test_inquiry_uses_the_current_sites_support_address(): void
    {
        config()->set('site.sites', [
            'contact.example.com' => [
                'connection' => 'sqlite',
                'support_email' => 'tenant-support@example.com',
            ],
        ]);
        $this->post('http://contact.example.com/contact-us', $this->inquiry() + ['captcha' => '7'])
            ->assertSessionHas('contact_success');
        $this->assertDatabaseHas('contact_messages', ['site_key' => 'contact.example.com']);
        Mail::assertSent(ContactInquiry::class, fn ($mail) => $mail->hasTo('tenant-support@example.com'));
    }

    public function test_invalid_input_and_spam_are_rejected(): void
    {
        foreach ([['email' => 'invalid'], ['subject' => 'Unknown'], ['message' => 'short'], ['website' => 'spam.example']] as $invalid) {
            $this->postJson('/contact-us', array_replace($this->inquiry(), $invalid))
                ->assertUnprocessable()->assertJsonValidationErrors(array_keys($invalid));
        }
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingSent();
    }

    public function test_mail_failure_preserves_inquiry_and_confirmation(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Mail unavailable'));
        $this->post('/contact-us', $this->inquiry() + ['captcha' => '7'])->assertSessionHas('contact_success');
        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_missing_wrong_and_expired_captcha_are_rejected(): void
    {
        foreach ([[], ['captcha' => '8']] as $answer) {
            $this->postJson('/contact-us', $this->inquiry() + $answer)
                ->assertUnprocessable()->assertJsonValidationErrors('captcha');
        }
        $this->withSession(['contact_captcha' => ['answer' => 7, 'expires_at' => now()->subMinute()->timestamp]])
            ->postJson('/contact-us', $this->inquiry() + ['captcha' => '7'])
            ->assertUnprocessable()->assertJsonValidationErrors('captcha');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingSent();
    }

    public function test_captcha_cannot_be_reused(): void
    {
        $this->post('/contact-us', $this->inquiry() + ['captcha' => '7'])->assertSessionHas('contact_success');
        $this->postJson('/contact-us', $this->inquiry() + ['captcha' => '7'])
            ->assertUnprocessable()->assertJsonValidationErrors('captcha');
        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_email_escapes_visitor_content(): void
    {
        $inquiry = new ContactMessage(array_replace($this->inquiry(), ['message' => '<script>alert(1)</script>']));
        $html = (new ContactInquiry($inquiry))->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
