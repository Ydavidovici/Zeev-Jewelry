<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and set them as the current authenticated user
        $this->admin = User::factory()->create(['role_id' => 1]); // Assuming role_id 1 is admin
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_can_view_settings_index()
    {
        $response = $this->get(route('admin-page.settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-page.settings.index');
    }

    /** @test */
    public function admin_can_update_settings()
    {
        $settings = Setting::factory()->count(3)->create();

        $data = [
            'settings' => [
                'setting1' => 'value1',
                'setting2' => 'value2',
                'setting3' => 'value3',
            ],
        ];

        $response = $this->post(route('admin-page.settings.update'), $data);

        $response->assertRedirect(route('admin-page.settings.index'));
        $response->assertSessionHas('success', 'Settings updated successfully.');

        $this->assertDatabaseHas('settings', ['key' => 'setting1', 'value' => 'value1']);
        $this->assertDatabaseHas('settings', ['key' => 'setting2', 'value' => 'value2']);
        $this->assertDatabaseHas('settings', ['key' => 'setting3', 'value' => 'value3']);
    }
}
