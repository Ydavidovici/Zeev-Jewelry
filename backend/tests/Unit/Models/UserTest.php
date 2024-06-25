<?php

namespace Tests\Unit\Models;

use App\Models\User;
use backend\tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user()
    {
        $user = User::factory()->create([
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }
}
