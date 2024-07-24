<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_homepage()
    {
        $featuredProducts = Product::factory()->count(6)->create(['is_featured' => true]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.home');
        $response->assertViewHas('featuredProducts', function ($viewFeaturedProducts) use ($featuredProducts) {
            return $viewFeaturedProducts->count() === $featuredProducts->count();
        });
    }
}
