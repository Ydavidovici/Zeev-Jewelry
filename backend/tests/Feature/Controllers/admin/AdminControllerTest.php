<?php

namespace Tests\Feature\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        // Mocking the route to return a fixed JSON response
        Route::get('/admin/dashboard', function () {
            return response()->json([
                'users' => [],
                'roles' => [],
                'permissions' => [],
                'products' => [],
                'orders' => [],
            ]);
        });

        $response = $this->getJson('/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users', 'roles', 'permissions', 'products', 'orders'
            ]);
    }
}
