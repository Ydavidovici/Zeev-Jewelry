<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_settings()
    {
        $response = $this->getJson(route('settings.getCurrentSettings'));

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_admin_can_view_settings()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('settings.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_non_admin_cannot_view_settings()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('settings.index'));

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_create_setting()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('settings.store'), [
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

        $response = $this->postJson(route('settings.store'), [
            'key' => 'site_name',
            'value' => 'My Website',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_update_setting()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('settings.update', $setting->key), [
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

        $response = $this->putJson(route('settings.update', $setting->key), [
            'value' => 'New Website Name',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_delete_setting()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('settings.destroy', $setting->key));

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    public function test_non_admin_cannot_delete_setting()
    {
        $user = User::factory()->create();
        $setting = Settings::create(['key' => 'site_name', 'value' => 'My Website']);

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('settings.destroy', $setting->key));

        $response->assertStatus(403); // Forbidden
    }
}
