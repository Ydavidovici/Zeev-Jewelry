<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure 'admin' and 'seller' roles are created only once
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin']);
        }

        if (Role::where('name', 'seller')->doesntExist()) {
            Role::create(['name' => 'seller']);
        }
    }

    public function test_admin_can_access_dashboard()
    {
        // Create an admin user and assign the 'admin' role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a category
        $category = Category::factory()->create(['category_name' => 'Jewelry']);

        // Create a seller user and assign the 'seller' role
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        // Create a product associated with the created category and seller
        Product::factory()->create([
            'category_id' => $category->id,
            'seller_id' => $seller->id,
            'product_name' => 'Gold Necklace',
            'description' => 'A beautiful gold necklace',
            'price' => 499.99,
            'image_url' => 'path/to/gold-necklace.jpg',
        ]);

        // Acting as the admin user
        $response = $this->actingAs($admin, 'api')->get(route('admin.index'));

        // Assert the response status is OK
        $response->assertStatus(200);

        // Assert dashboard contains necessary data in the JSON response (categories and products)
        $response->assertJsonStructure([
            'categories',
            'products',
        ]);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        // Create a non-admin user and assign the 'customer' role
        $nonAdmin = User::factory()->create();
        $nonAdmin->assignRole('customer');

        // Attempt to access the dashboard as a non-admin user
        $response = $this->actingAs($nonAdmin, 'api')->get(route('admin.index'));

        // Assert the non-admin user receives a 403 Forbidden response
        $response->assertStatus(403);
    }
}
