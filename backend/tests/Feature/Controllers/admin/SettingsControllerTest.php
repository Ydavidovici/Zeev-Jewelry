<?php

namespace Tests\Feature\Controllers;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private $adminUser;
    private $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the roles
        $this->artisan('db:seed', ['--class' => 'RolesTableSeeder']);

        // Create an admin user and a normal user
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        $this->normalUser = User::factory()->create();
        $this->normalUser->assignRole('customer');
    }

    /**
     * Test public access to current settings.
     *
     * @return void
     */
    public function test_public_can_get_current_settings()
    {
        Settings::factory()->create(['key' => 'theme', 'value' => 'dark']);

        $response = $this->getJson('/current-settings');

        $response->assertStatus(200)
            ->assertJsonFragment(['key' => 'theme'])
            ->assertJsonFragment(['value' => 'dark']);
    }

    /**
     * Test admin can store settings.
     *
     * @return void
     */
    public function test_admin_can_store_settings()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/admin/settings', ['key' => 'theme', 'value' => 'dark']);

        $response->assertStatus(201)
            ->assertJsonFragment(['key' => 'theme'])
            ->assertJsonFragment(['value' => 'dark']);
    }

    /**
     * Test normal user cannot store settings.
     *
     * @return void
     */
    public function test_normal_user_cannot_store_settings()
    {
        $response = $this->actingAs($this->normalUser, 'sanctum')
            ->postJson('/admin/settings', ['key' => 'theme', 'value' => 'dark']);

        $response->assertStatus(403);
    }

    /**
     * Test admin can update settings.
     *
     * @return void
     */
    public function test_admin_can_update_settings()
    {
        $setting = Settings::factory()->create(['key' => 'theme', 'value' => 'light']);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->putJson('/admin/settings/' . $setting->key, ['value' => 'dark']);

        $response->assertStatus(200)
            ->assertJsonFragment(['key' => 'theme'])
            ->assertJsonFragment(['value' => 'dark']);
    }

    /**
     * Test normal user cannot update settings.
     *
     * @return void
     */
    public function test_normal_user_cannot_update_settings()
    {
        $setting = Settings::factory()->create(['key' => 'theme', 'value' => 'light']);

        $response = $this->actingAs($this->normalUser, 'sanctum')
            ->putJson('/admin/settings/' . $setting->id, ['value' => 'dark']);

        $response->assertStatus(403);
    }

    /**
     * Test admin can delete settings.
     *
     * @return void
     */
    public function test_admin_can_delete_settings()
    {
        $setting = Settings::factory()->create(['key' => 'theme', 'value' => 'dark']);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->deleteJson('/admin/settings/' . $setting->key);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Setting deleted successfully.']);
        $this->assertDatabaseMissing('settings', ['key' => 'theme']);
    }

    /**
     * Test normal user cannot delete settings.
     *
     * @return void
     */
    public function test_normal_user_cannot_delete_settings()
    {
        $setting = Settings::factory()->create(['key' => 'theme', 'value' => 'dark']);

        $response = $this->actingAs($this->normalUser, 'sanctum')
            ->deleteJson('/admin/settings/' . $setting->id);

        $response->assertStatus(403);
    }
}
