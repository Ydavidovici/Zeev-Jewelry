<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_product_has_category_id()
    {
        $product = new Product(['category_id' => 1]);

        $this->assertEquals(1, $product->category_id);
    }

    public function test_product_has_name()
    {
        $product = new Product(['name' => 'Sample Product']);

        $this->assertEquals('Sample Product', $product->name);
    }

    public function test_product_has_description()
    {
        $product = new Product(['description' => 'Sample Description']);

        $this->assertEquals('Sample Description', $product->description);
    }

    public function test_product_has_price()
    {
        $product = new Product(['price' => 99.99]);

        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_has_stock_quantity()
    {
        $product = new Product(['stock_quantity' => 50]);

        $this->assertEquals(50, $product->stock_quantity);
    }

    public function test_product_is_featured()
    {
        $product = new Product(['is_featured' => true]);

        $this->assertTrue($product->is_featured);
    }

    public function test_product_belongs_to_category()
    {
        $product = new Product();
        $relation = $product->category();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('category_id', $relation->getForeignKeyName());
    }

    public function test_product_has_many_reviews()
    {
        $product = new Product();
        $relation = $product->reviews();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
        $this->assertEquals('product_id', $relation->getForeignKeyName());
    }
}
