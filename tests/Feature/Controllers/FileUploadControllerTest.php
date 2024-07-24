<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_upload_file()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('testfile.jpg');

        $response = $this->post(route('file.upload'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['path']);

        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    /** @test */
    public function user_can_view_uploaded_files()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('testfile.jpg');
        $file->storeAs('public/uploads', 'testfile.jpg');

        $response = $this->get(route('file.index'));

        $response->assertStatus(200);
        $response->assertViewIs('uploads.index');
        $response->assertViewHas('fileUrls', [Storage::url('public/uploads/testfile.jpg')]);
    }

    /** @test */
    public function user_can_delete_uploaded_file()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('testfile.jpg');
        $file->storeAs('public/uploads', 'testfile.jpg');

        $response = $this->delete(route('file.delete', 'testfile.jpg'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'File deleted successfully.');

        Storage::disk('public')->assertMissing('uploads/testfile.jpg');
    }
}
