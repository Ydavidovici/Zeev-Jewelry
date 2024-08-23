<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testHomePageFeaturedProducts()
    {
        Product::factory()->count(6)->create(['is_featured' => true]);

        $response = $this->getJson('/api/home');

        $response->assertStatus(200)
            ->assertJsonCount(6, 'featured_products');
    }
}
