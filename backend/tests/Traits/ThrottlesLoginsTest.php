<?php


namespace Tests\Unit\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ThrottlesLoginsTest extends TestCase
{
    use RefreshDatabase;

    public function test_throttles_logins()
    {
        RateLimiter::hit('login|127.0.0.1', 1);

        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertTrue(RateLimiter::tooManyAttempts('login|127.0.0.1', 1));
    }
}
