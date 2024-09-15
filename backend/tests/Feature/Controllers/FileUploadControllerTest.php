<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake the public disk
        Storage::fake('public');

        // Authenticate as an admin user
        $this->actingAs(User::factory()->create()->assignRole('admin'), 'api');
    }

    public function test_it_can_upload_a_file()
    {
        // Create a fake image file
        $file = UploadedFile::fake()->image('test.jpg');

        // Send POST request to store the file
        $response = $this->postJson(route('files.store'), ['file' => $file]);

        // Assert that the response is successful and contains the path
        $response->assertStatus(200)
            ->assertJsonStructure(['path']);

        // Assert the file was stored on the 'public' disk under 'uploads'
        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    public function test_it_can_list_uploaded_files()
    {
        // Store a file manually in the 'uploads' directory
        Storage::disk('public')->put('uploads/test.jpg', 'Test Content');

        // Send GET request to retrieve the list of files
        $response = $this->getJson(route('files.index'));

        // Assert that the response is successful and contains the correct file URL
        $response->assertStatus(200)
            ->assertJsonFragment(['files' => [Storage::url('uploads/test.jpg')]]);
    }

    public function test_it_can_delete_a_file()
    {
        // Store a file manually in the 'uploads' directory
        Storage::disk('public')->put('uploads/test.jpg', 'Test Content');

        // Send DELETE request to remove the file
        $response = $this->deleteJson(route('files.destroy', 'test.jpg'));

        // Assert that the response is successful and confirms the file deletion
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'File deleted successfully.']);

        // Verify that the file no longer exists in the 'uploads' directory
        Storage::disk('public')->assertMissing('uploads/test.jpg');
    }
}
