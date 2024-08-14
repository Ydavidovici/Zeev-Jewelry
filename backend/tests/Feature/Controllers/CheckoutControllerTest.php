<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_cart_at_checkout()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/checkout');

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    public function test_user_can_place_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Assume there is a cart in the session
        session(['cart' => [
            1 => ['product' => Product::factory()->create(), 'quantity' => 1]
        ]]);

        $response = $this->postJson('/api/checkout', [
            'address' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Order placed successfully.']);
    }

    public function test_order_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/checkout/success');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Order completed successfully.']);
    }

    public function test_order_failure()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/checkout/failure');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Order failed.']);
    }
}
