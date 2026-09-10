<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoUploadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'site.sites' => []]);
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('photo_approved')->nullable();
        });
        Storage::fake('profile_photos');
    }

    public function test_profile_completion_stores_an_image_and_requires_approval(): void
    {
        $member = Member::create(['photo' => 'old.jpg', 'photo_approved' => 'Yes']);
        $this->actingAs($member, 'member')->post('/complete-profile', [
            'photo' => UploadedFile::fake()->image('portrait.jpg'),
            'photo_approved' => 'Yes',
        ])->assertOk()->assertJson(['success' => true]);
        $member->refresh();
        Storage::disk('profile_photos')->assertExists($member->photo);
        $this->assertSame('No', $member->photo_approved);
        $this->assertNotSame('portrait.jpg', $member->photo);
    }

    public function test_a_browser_cannot_set_a_photo_path_or_approve_it(): void
    {
        $member = Member::create(['photo' => 'existing.jpg', 'photo_approved' => 'No']);
        $this->actingAs($member, 'member')->postJson('/complete-profile', ['photo' => '../../fake.jpg', 'photo_approved' => 'Yes'])->assertOk();
        $this->assertSame('existing.jpg', $member->fresh()->photo);
        $this->assertSame('No', $member->fresh()->photo_approved);
    }

    public function test_non_image_upload_is_rejected(): void
    {
        $member = Member::create([]);
        $this->actingAs($member, 'member')->postJson('/complete-profile', [
            'photo' => UploadedFile::fake()->create('file.php', 1, 'text/plain'),
        ])->assertUnprocessable();
        $this->assertNull($member->fresh()->photo);
        $this->assertCount(0, Storage::disk('profile_photos')->allFiles());
    }
}
