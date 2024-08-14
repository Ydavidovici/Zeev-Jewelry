<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\User;

class ShippingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Shipping::factory()->count(3)->create();

        $response = $this->getJson('/api/shippings');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of shipping records
    }

    public function test_user_can_create_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();

        $response = $this->postJson('/api/shippings', [
            'order_id' => $order->id,
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'shipping_method' => 'UPS',
            'tracking_number' => '1Z999AA10123456784',
            'status' => 'Shipped',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'order_id', 'address', 'city', 'state', 'postal_code', 'country', 'shipping_method', 'tracking_number', 'status']);
    }

    public function test_user_can_view_single_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $shipping = Shipping::factory()->create();

        $response = $this->getJson("/api/shippings/{$shipping->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'address', 'city', 'state', 'postal_code', 'country', 'shipping_method', 'tracking_number', 'status']);
    }

    public function test_user_can_update_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $shipping = Shipping::factory()->create();

        $response = $this->putJson("/api/shippings/{$shipping->id}", [
            'status' => 'Delivered',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'address', 'city', 'state', 'postal_code', 'country', 'shipping_method', 'tracking_number', 'status']);
    }

    public function test_user_can_delete_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $shipping = Shipping::factory()->create();

        $response = $this->deleteJson("/api/shippings/{$shipping->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('shippings', ['id' => $shipping->id]);
    }
}
