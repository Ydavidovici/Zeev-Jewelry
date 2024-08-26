<?php

// tests/Unit/Models/RoleTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function role_has_many_users()
    {
        // Create a role using the Role factory
        $role = Role::factory()->create();

        // Create a user and assign the role
        $user = User::factory()->create();
        $user->assignRole($role->name);

        $this->assertTrue($role->users->contains($user));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $role->users);
    }
}
