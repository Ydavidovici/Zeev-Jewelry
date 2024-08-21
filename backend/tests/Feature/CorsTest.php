<?php

namespace Tests\Feature;

use Tests\TestCase;

class CorsTest extends TestCase
{
    public function test_cors_headers_are_present()
    {
        $response = $this->get('/some-endpoint'); // Adjust to API endpoint

        $response->assertStatus(200) // Adjust status code as needed
        ->assertHeader('Access-Control-Allow-Origin', '*') // Or specific domain if you set it
        ->assertHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->assertHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
