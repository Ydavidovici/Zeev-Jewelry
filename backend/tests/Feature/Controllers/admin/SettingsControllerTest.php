<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_settings()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->getJson('/api/admin/settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'settings'
            ]);
    }

    public function test_admin_can_update_settings()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->putJson('/api/admin/settings', [
            'settings' => [
                'site_name' => 'My Site',
                'site_description' => 'A description'
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Settings updated successfully.'
            ]);
    }
}
