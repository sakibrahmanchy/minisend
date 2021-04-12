<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    /** @test */
    public function test_attachments_can_be_uploaded()
    {
        Storage::fake('s3');

        $file = UploadedFile::fake()->image('avatar.png');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $this->postJson('/api/attachments', [
            'file' => $file,
        ])->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'url'
        ]);
    }
}
