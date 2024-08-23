<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFileUpload()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/files', [
                'file' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['path']);

        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    public function testFileIndex()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Storage::disk('public')->put('uploads/file1.jpg', 'content');
        Storage::disk('public')->put('uploads/file2.jpg', 'content');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/files');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'files');
    }

    public function testFileDelete()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $file = UploadedFile::fake()->image('avatar.jpg');
        $filePath = $file->storeAs('public/uploads', 'avatar.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson('/api/files/avatar.jpg');

        $response->assertStatus(200)
            ->assertJson(['message' => 'File deleted successfully.']);

        Storage::disk('public')->assertMissing('uploads/avatar.jpg');
    }
}
