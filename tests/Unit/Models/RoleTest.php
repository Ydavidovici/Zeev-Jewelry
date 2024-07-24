<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Role;

class RoleTest extends TestCase
{
    public function test_role_has_name()
    {
        $role = new Role(['name' => 'Admin']);

        $this->assertEquals('Admin', $role->name);
    }

    public function test_role_has_description()
    {
        $role = new Role(['description' => 'Administrator role']);

        $this->assertEquals('Administrator role', $role->description);
    }

    public function test_role_has_many_users()
    {
        $role = new Role();
        $relation = $role->users();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
        $this->assertEquals('role_id', $relation->getForeignKeyName());
    }
}
