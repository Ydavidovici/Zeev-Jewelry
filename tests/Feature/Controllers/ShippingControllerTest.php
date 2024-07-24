<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shipping;
use App\Models\User;
use App\Models\Order;

class ShippingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_shippings_index()
    {
        $response = $this->get(route('shippings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('shippings.index');
        $response->assertViewHas('shippings');
    }

    /** @test */
    public function user_can_view_create_shipping_form()
    {
        $response = $this->get(route('shippings.create'));

        $response->assertStatus(200);
        $response->assertViewIs('shippings.create');
    }

    /** @test */
    public function user_can_create_shipping()
    {
        $order = Order::factory()->create();

        $data = [
            'order_id' => $order->id,
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'Test Country',
            'shipping_method' => 'Standard',
            'tracking_number' => 'TRACK12345',
            'status' => 'Pending',
        ];

        $response = $this->post(route('shippings.store'), $data);

        $response->assertRedirect(route('shippings.index'));
        $response->assertSessionHas('success', 'Shipping created successfully.');
        $this->assertDatabaseHas('shippings', $data);
    }

    /** @test */
    public function user_can_view_edit_shipping_form()
    {
        $shipping = Shipping::factory()->create();

        $response = $this->get(route('shippings.edit', $shipping));

        $response->assertStatus(200);
        $response->assertViewIs('shippings.edit');
        $response->assertViewHas('shipping', $shipping);
    }

    /** @test */
    public function user_can_update_shipping()
    {
        $shipping = Shipping::factory()->create();

        $data = [
            'order_id' => $shipping->order_id,
            'address' => 'Updated Address',
            'city' => 'Updated City',
            'state' => 'Updated State',
            'postal_code' => '67890',
            'country' => 'Updated Country',
            'shipping_method' => 'Express',
            'tracking_number' => 'UPDATED12345',
            'status' => 'Shipped',
        ];

        $response = $this->put(route('shippings.update', $shipping), $data);

        $response->assertRedirect(route('shippings.index'));
        $response->assertSessionHas('success', 'Shipping updated successfully.');
        $this->assertDatabaseHas('shippings', array_merge(['id' => $shipping->id], $data));
    }

    /** @test */
    public function user_can_delete_shipping()
    {
        $shipping = Shipping::factory()->create();

        $response = $this->delete(route('shippings.destroy', $shipping));

        $response->assertRedirect(route('shippings.index'));
        $response->assertSessionHas('success', 'Shipping deleted successfully.');
        $this->assertDatabaseMissing('shippings', ['id' => $shipping->id]);
    }
}
