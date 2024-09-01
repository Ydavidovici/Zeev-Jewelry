<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User; // Replaced Customer with User
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function seedRoles()
    {
        if (Role::where('name', 'Seller')->doesntExist()) {
            Role::create(['name' => 'Seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    #[Test]
    public function product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    #[Test]
    public function product_has_many_reviews()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create(); // Replaced Customer with User
        $review = Review::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id, // Replaced customer_id with user_id
        ]);

        $this->assertTrue($product->reviews->contains($review));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->reviews);
    }
}
