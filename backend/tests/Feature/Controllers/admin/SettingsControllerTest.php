<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Settings;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_current_settings()
    {
        Settings::create(['key' => 'site_name', 'value' => 'Test Site']);

        $response = $this->getJson(route('current.settings'));

        $response->assertStatus(200)
            ->assertJsonStructure([[
                'id',
                'key',
                'value',
                'created_at',
                'updated_at',
            ]]);
    }

    public function test_admin_can_create_setting()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('admin.settings.store'), [
            'key' => 'site_name',
            'value' => 'Test Site',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'setting']);
    }

    public function test_admin_can_update_setting()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $setting = Settings::create(['key' => 'site_name', 'value' => 'Test Site']);

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('admin.settings.update', $setting->key),
            [
                'value' => 'Updated Site Name',
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'setting']);
    }

    public function test_admin_can_delete_setting()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $setting = Settings::create(['key' => 'site_name', 'value' => 'Test Site']);

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('admin.settings.destroy', $setting->key));

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }
}