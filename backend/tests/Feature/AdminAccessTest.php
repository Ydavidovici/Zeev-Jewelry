<?php

// tests/Feature/AdminAccessTest.php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $nonAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'User']);

        // Create users
        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->adminUser->assignRole('Admin');

        $this->nonAdminUser = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->nonAdminUser->assignRole('User');
    }

    /** @test */
    public function admin_can_access_admin_route()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin-test');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'You are an admin!']);
    }

    /** @test */
    public function non_admin_cannot_access_admin_route()
    {
        $response = $this->actingAs($this->nonAdminUser)->get('/admin-test');

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_admin_route()
    {
        $response = $this->get('/admin-test');

        $response->assertStatus(403);
    }
}
