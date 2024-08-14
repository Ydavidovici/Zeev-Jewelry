<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_file()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $file = UploadedFile::fake()->image('file.jpg');

        $response = $this->postJson('/api/file_uploads', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['path']);

        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    public function test_user_can_view_uploaded_files()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Upload a file
        $file = UploadedFile::fake()->image('file.jpg');
        $path = $file->store('public/uploads');

        $response = $this->getJson('/api/file_uploads');

        $response->assertStatus(200)
            ->assertJsonStructure(['files' => []])
            ->assertJsonFragment(['files' => [Storage::url($path)]]);
    }

    public function test_user_can_delete_uploaded_file()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Upload a file
        $file = UploadedFile::fake()->image('file.jpg');
        $path = $file->store('public/uploads');

        $response = $this->deleteJson('/api/file_uploads/' . basename($path));

        $response->assertStatus(200)
            ->assertJson(['message' => 'File deleted successfully.']);

        Storage::disk('public')->assertMissing('uploads/' . basename($path));
    }
}
