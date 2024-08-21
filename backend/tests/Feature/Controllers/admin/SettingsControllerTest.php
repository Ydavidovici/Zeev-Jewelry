<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the 'admin' role exists without using firstOrCreate
        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            $role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }

        // Create an admin user and authenticate
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_can_access_test_settings_route(): void
    {
        // Authenticate before the request
        $response = $this->actingAs($this->admin, 'api')
            ->getJson('/test-settings');

        $response->assertStatus(200)
            ->assertJson(['message' => 'SettingsController is working!']);
    }

    public function test_can_retrieve_settings_with_theme(): void
    {
        // Authenticate before the request
        $response = $this->actingAs($this->admin, 'api')
            ->withCookie('theme', 'dark')
            ->getJson('/admin/settings');

        $response->assertStatus(200)
            ->assertJson([
                'settings' => [
                    'site_name' => 'Zeev Jewelry',
                    'currency' => 'USD',
                    'theme_options' => ['light', 'dark'],
                    'default_language' => 'en',
                ],
                'theme' => 'dark',
            ]);
    }

    public function test_can_update_settings_and_theme(): void
    {
        $settings = [
            'site_name' => 'Updated Site',
            'currency' => 'EUR',
        ];

        // Authenticate before the request
        $response = $this->actingAs($this->admin, 'api')
            ->putJson('/admin/settings', [
                'settings' => $settings,
                'theme' => 'dark',
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Settings updated successfully.']);

        $this->assertEquals('Updated Site', $settings['site_name']);
        $this->assertEquals('EUR', $settings['currency']);
        $response->assertCookie('theme', 'dark');
    }
}
