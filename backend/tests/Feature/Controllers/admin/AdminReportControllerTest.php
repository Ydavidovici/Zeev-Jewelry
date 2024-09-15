<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure 'admin' role is created only once
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin']);
        }
    }

    public function test_admin_can_access_dashboard_report()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

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
