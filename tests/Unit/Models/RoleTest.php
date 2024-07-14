<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_role()
    {
        $role = Role::factory()->create([
            'role_name' => 'Admin',
        ]);

        $this->assertDatabaseHas('roles', ['role_name' => 'Admin']);
    }
}
