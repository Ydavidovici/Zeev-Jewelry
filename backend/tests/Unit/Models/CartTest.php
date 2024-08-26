<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CartTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cart_belongs_to_user()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertEquals($user->id, $cart->user->id);
    }

    #[Test]
    public function cart_has_many_items()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create(); // Ensure product is created
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id // Ensure product ID is assigned correctly
        ]);

        $this->assertTrue($cart->items->contains($cartItem));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $cart->items);
    }
}
