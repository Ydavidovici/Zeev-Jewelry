<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function admin_can_access_dashboard_and_generate_reports()
    {
        $response = $this->actingAs($this->adminUser)->getJson('/admin/reports');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'server_performance',
                'database_performance',
                'error_logs',
                'uptime'
            ]);
    }

    /** @test */
    public function admin_can_generate_api_performance_report()
    {
        $response = $this->actingAs($this->adminUser)->getJson('/admin/reports/api-performance');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'average_response_time',
                'peak_response_time',
                'error_rate',
            ]);
    }

    /** @test */
    public function non_admin_cannot_access_admin_reports()
    {
        $nonAdminUser = User::factory()->create([
            'role' => 'seller',
        ]);

        $response = $this->actingAs($nonAdminUser)->getJson('/admin/reports');

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_reports()
    {
        $response = $this->getJson('/admin/reports');

        $response->assertStatus(401);
    }
}
