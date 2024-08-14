<?php

namespace Tests\Feature\Controllers;

use App\Mail\ShippingConfirmationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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
        Mail::fake();

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();

        $response = $this->postJson('/api/shippings', [
            'order_id' => $order->id,
            'shipping_type' => 'UPS',
            'shipping_cost' => 10.00,
            'shipping_status' => 'Shipped',
            'tracking_number' => '1Z999AA10123456784',
            'shipping_address' => '123 Main St',
            'shipping_carrier' => 'UPS',
            'recipient_name' => 'John Doe',
            'estimated_delivery_date' => now()->addDays(5)->toDateString(),
            'additional_notes' => 'Handle with care',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'order_id', 'shipping_type', 'shipping_cost', 'shipping_status', 'tracking_number']);

        $shipping = Shipping::first();

        Mail::assertSent(ShippingConfirmationMail::class, function ($mail) use ($shipping, $order) {
            return $mail->hasTo($order->customer->email) && $mail->order->is($order);
        });
    }

    public function test_user_can_view_single_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $shipping = Shipping::factory()->create();

        $response = $this->getJson("/api/shippings/{$shipping->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'shipping_type', 'shipping_cost', 'shipping_status', 'tracking_number']);
    }

    public function test_user_can_update_shipping()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $shipping = Shipping::factory()->create();

        $response = $this->putJson("/api/shippings/{$shipping->id}", [
            'shipping_status' => 'Delivered',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'shipping_type', 'shipping_cost', 'shipping_status', 'tracking_number']);
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
