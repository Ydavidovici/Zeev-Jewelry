<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Order;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_dashboard_displays_correct_data()
    {
        // Create sample data
        $users = User::factory()->count(3)->create();
        $roles = Role::factory()->count(2)->create();
        $permissions = Permission::factory()->count(5)->create();
        $products = Product::factory()->count(10)->create();
        $orders = Order::factory()->count(4)->create();

        // Act as an admin user (assuming role_id 1 is for admin)
        $admin = User::factory()->create(['role_id' => 1]);
        $this->actingAs($admin);

        // Make a GET request to the admin dashboard
        $response = $this->get(route('admin-page.dashboard'));

        // Assert status and view
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        // Assert data
        $response->assertViewHas('users', function ($users) {
            return $users->count() === 3;
        });

        $response->assertViewHas('roles', function ($roles) {
            return $roles->count() === 2;
        });

        $response->assertViewHas('permissions', function ($permissions) {
            return $permissions->count() === 5;
        });

        $response->assertViewHas('products', function ($products) {
            return $products->count() === 10;
        });

        $response->assertViewHas('orders', function ($orders) {
            return $orders->count() === 4;
        });
    }
}
