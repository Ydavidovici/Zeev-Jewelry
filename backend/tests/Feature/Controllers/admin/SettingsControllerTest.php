<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure 'admin' role is created and assign 'manage settings' permission
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create(['name' => 'admin']);
        } else {
            $adminRole = Role::where('name', 'admin')->first();
        }

        if (!Permission::where('name', 'manage settings')->exists()) {
            $manageSettingsPermission = Permission::create(['name' => 'manage settings']);
            $adminRole->givePermissionTo($manageSettingsPermission);
        }
    }

    public function test_anyone_can_view_settings()
    {
        $response = $this->getJson(route('current.settings'));

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_admin_can_view_settings()
    {
        // Create a user and assign the 'admin' role with 'manage settings' permission
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('admin.settings.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_non_admin_cannot_view_settings()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('admin.settings.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_setting()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('admin.settings.store'), [
            'key' => 'site_name',
            'value' => 'My Website',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'setting']);
    }

    public function test_non_admin_cannot_create_setting()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson(route('admin.settings.store'), [
            'key' => 'site_name',
            'value' => 'My Website',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_setting()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('admin.settings.update', $setting->key), [
            'value' => 'New Website Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'setting']);
    }

    public function test_non_admin_cannot_update_setting()
    {
        $user = User::factory()->create();
        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($user, 'api');

        $response = $this->putJson(route('admin.settings.update', $setting->key), [
            'value' => 'New Website Name',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_setting()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('admin.settings.destroy', $setting->key));

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    public function test_non_admin_cannot_delete_setting()
    {
        $user = User::factory()->create();
        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('admin.settings.destroy', $setting->key));

        $response->assertStatus(403);
    }
}
