<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_homepage_with_featured_products()
    {
        Product::factory()->count(3)->create(['is_featured' => true]);

        $response = $this->getJson('/api/home');

        $response->assertStatus(200)
            ->assertJsonStructure(['featured_products' => []]);
    }
}
