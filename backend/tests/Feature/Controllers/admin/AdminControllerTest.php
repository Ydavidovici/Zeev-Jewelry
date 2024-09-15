<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles are created only once
        User::factory()->create()->assignRole('admin');
    }

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create()->assignRole('admin');
        $category = Category::factory()->create(['category_name' => 'Jewelry']);
        $seller = User::factory()->create()->assignRole('seller');

        Product::factory()->create([
            'category_id' => $category->id,
            'seller_id' => $seller->id,
            'name' => 'Gold Necklace',
            'description' => 'A beautiful gold necklace',
            'price' => 499.99,
            'image_url' => 'path/to/gold-necklace.jpg',
        ]);

        $response = $this->actingAs($admin, 'api')->get(route('admin.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['categories', 'products']);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $nonAdmin = User::factory()->create()->assignRole('customer');

        $response = $this->actingAs($nonAdmin, 'api')->get(route('admin.index'));

        $response->assertStatus(403);
    }
}
