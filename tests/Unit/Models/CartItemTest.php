<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;

class CartItemTest extends TestCase
{
    public function test_cart_item_has_cart_id()
    {
        $cartItem = new CartItem(['cart_id' => 1]);

        $this->assertEquals(1, $cartItem->cart_id);
    }

    public function test_cart_item_has_product_id()
    {
        $cartItem = new CartItem(['product_id' => 1]);

        $this->assertEquals(1, $cartItem->product_id);
    }

    public function test_cart_item_has_quantity()
    {
        $cartItem = new CartItem(['quantity' => 5]);

        $this->assertEquals(5, $cartItem->quantity);
    }

    public function test_cart_item_belongs_to_cart()
    {
        $cartItem = new CartItem();
        $relation = $cartItem->cart();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('cart_id', $relation->getForeignKeyName());
    }

    public function test_cart_item_belongs_to_product()
    {
        $cartItem = new CartItem();
        $relation = $cartItem->product();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('product_id', $relation->getForeignKeyName());
    }
}
