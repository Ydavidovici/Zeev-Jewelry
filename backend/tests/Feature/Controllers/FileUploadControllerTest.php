<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
        Storage::fake('public');
    }

    /** @test */
    public function it_can_upload_a_file()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson(route('files.store'), [
            'file' => $file
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['path']);

        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    /** @test */
    public function it_can_list_uploaded_files()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        Storage::disk('public')->put('uploads/test.jpg', 'Test Content');

        $response = $this->getJson(route('files.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['files' => [Storage::url('uploads/test.jpg')]]);
    }

    /** @test */
    public function it_can_delete_a_file()
    {
        Gate::define('delete', function ($user) {
            return true;
        });

        Storage::disk('public')->put('uploads/test.jpg', 'Test Content');

        $response = $this->deleteJson(route('files.destroy', 'test.jpg'));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'File deleted successfully.']);

        Storage::disk('public')->assertMissing('uploads/test.jpg');
    }
}
