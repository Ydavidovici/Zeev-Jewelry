<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CartItemTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cart_item_belongs_to_cart()
    {
        $cart = Cart::factory()->create();
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

        $this->assertInstanceOf(Cart::class, $cartItem->cart);
        $this->assertEquals($cart->id, $cartItem->cart->id);
    }

    #[Test]
    public function cart_item_belongs_to_product()
    {
        $product = Product::factory()->create();
        $cartItem = CartItem::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $cartItem->product);
        $this->assertEquals($product->id, $cartItem->product->id);
    }
}
