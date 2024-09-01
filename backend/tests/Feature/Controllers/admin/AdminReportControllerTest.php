<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_report()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('admin.report.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'server_performance',
                'database_performance',
                'error_logs',
                'uptime',
            ]);
    }

    public function test_non_admin_cannot_access_dashboard_report()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('admin.report.index'));

        $response->assertStatus(403); // Forbidden
    }
}
