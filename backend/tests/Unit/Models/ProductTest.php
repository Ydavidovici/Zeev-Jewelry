<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    use RefreshDatabase;

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
        // Create a product
        $product = Product::factory()->create();

        // Create a customer
        $customer = Customer::factory()->create();

        // Create a review for the product by the customer
        $review = Review::factory()->create([
            'product_id' => $product->id,
            'customer_id' => $customer->id,
        ]);

        $this->assertTrue($product->reviews->contains($review));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->reviews);
    }
}
