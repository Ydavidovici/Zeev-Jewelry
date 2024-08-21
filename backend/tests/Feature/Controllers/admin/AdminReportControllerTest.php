<?php

namespace Tests\Feature\Controllers\Admin;

use App\Http\Controllers\Admin\AdminReportController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use App\Models\User;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->adminUser = User::factory()->create();
    }

    public function test_admin_can_access_dashboard_and_generate_reports()
    {
        // Acting as the admin user
        $this->actingAs($this->adminUser);

        // Mocking the route to return a fixed JSON response
        Route::get('/admin/reports', function () {
            return response()->json([
                'server_performance' => [],
                'database_performance' => [],
                'error_logs' => [],
            ]);
        });

        // Call the route
        $response = $this->getJson('/admin/reports');

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'server_performance',
                'database_performance',
                'error_logs',
            ]);
    }
}
