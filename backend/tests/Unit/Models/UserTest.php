<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_user_has_username()
    {
        $user = new User(['username' => 'testuser']);

        $this->assertEquals('testuser', $user->username);
    }

    public function test_user_has_email()
    {
        $user = new User(['email' => 'test@example.com']);

        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_user_has_password()
    {
        $user = new User(['password' => 'secret']);

        $this->assertEquals('secret', $user->password);
    }

    public function test_user_has_role_id()
    {
        $user = new User(['role_id' => 1]);

        $this->assertEquals(1, $user->role_id);
    }
}
