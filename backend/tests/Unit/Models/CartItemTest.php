<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class CartItemTest extends TestCase
{
    use RefreshDatabase;

    // Seed roles before each test
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    // Method to seed roles for testing
    private function seedRoles()
    {
        // Check if roles already exist to avoid duplicate entries
        if (Role::where('name', 'seller')->doesntExist()) {
            Role::create(['name' => 'seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    public function test_cart_item_belongs_to_cart()
    {
        // Create a cart instance
        $cart = Cart::factory()->create();

        // Create a cart item instance associated with the created cart
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

        // Assert the cart item belongs to the correct cart
        $this->assertInstanceOf(Cart::class, $cartItem->cart);
        $this->assertEquals($cart->id, $cartItem->cart->id);
    }

    public function test_cart_item_belongs_to_product()
    {
        // Create a product instance
        $product = Product::factory()->create();

        // Create a cart item instance associated with the created product
        $cartItem = CartItem::factory()->create(['product_id' => $product->id]);

        // Assert the cart item belongs to the correct product
        $this->assertInstanceOf(Product::class, $cartItem->product);
        $this->assertEquals($product->id, $cartItem->product->id);
    }
}
