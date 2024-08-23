<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('admin.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users',
                'roles',
                'permissions',
                'products',
                'orders',
            ]);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('admin.index'));

        $response->assertStatus(403); // Forbidden
    }
}
