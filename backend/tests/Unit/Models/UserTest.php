<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;  // Ensure the correct Role model is imported
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles in the database
        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        Role::create(['name' => 'customer', 'guard_name' => 'api']);
        Role::create(['name' => 'Seller', 'guard_name' => 'api']);
    }

    #[Test]
    public function user_has_fillable_attributes()
    {
        $fillable = ['username', 'email', 'password'];

        $this->assertEquals($fillable, (new User())->getFillable());
    }

    #[Test]
    public function user_can_be_assigned_roles()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }

    #[Test]
    public function user_is_admin()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->isAdmin());
    }

    #[Test]
    public function user_is_customer()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $this->assertTrue($user->isCustomer());
    }

    #[Test]
    public function user_is_seller()
    {
        $user = User::factory()->create();
        $user->assignRole('Seller');

        $this->assertTrue($user->isSeller());
    }
}
