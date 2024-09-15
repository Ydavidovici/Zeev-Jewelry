<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function testHomePageFeaturedProducts()
    {
        Product::factory()->count(6)->create(['is_featured' => true]);

        $response = $this->getJson(route('home')); // Make sure the route matches

        $response->assertStatus(200)
            ->assertJsonCount(6, 'featured_products');
    }
}
