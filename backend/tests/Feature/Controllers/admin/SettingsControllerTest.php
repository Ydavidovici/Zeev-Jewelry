<?php
namespace Tests\Feature\Controllers\admin;

use Tests\TestCase;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_settings_with_theme()
    {
        $response = $this->withCookie('theme', 'dark')
            ->getJson('/admin/settings');

        $response->assertStatus(200)
            ->assertJson(['theme' => 'dark']);
    }

    public function test_can_update_settings_and_theme()
    {
        $settings = [
            'site_name' => 'My Site',
        ];

        $response = $this->putJson('/admin/settings', [
            'settings' => $settings,
            'theme' => 'dark',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Settings updated successfully.']);

        $this->assertDatabaseHas('settings', [
            'key' => 'site_name',
            'value' => 'My Site',
        ]);

        $this->assertEquals('dark', $this->getCookie('theme'));
    }
}
