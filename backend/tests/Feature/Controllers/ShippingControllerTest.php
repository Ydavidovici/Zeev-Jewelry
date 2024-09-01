<?php

namespace Tests\Feature\Controllers;

use App\Models\Shipping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ShippingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_shipping_details()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('shipping.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'order_id', 'shipping_type', 'shipping_cost', 'shipping_status']]);
    }

    /** @test */
    public function it_can_create_shipping_details()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $shippingData = [
            'order_id' => 1,
            'seller_id' => 1,
            'shipping_type' => 'Standard',
            'shipping_cost' => 10.00,
            'shipping_status' => 'Pending',
            'tracking_number' => '123456',
            'shipping_address' => '123 Main St',
            'shipping_carrier' => 'DHL',
            'recipient_name' => 'John Doe',
            'estimated_delivery_date' => now()->addWeek()->toDateString(),
        ];

        $response = $this->postJson(route('shipping.store'), $shippingData);

        $response->assertStatus(201)
            ->assertJsonFragment(['shipping_status' => 'Pending']);

        $this->assertDatabaseHas('shipping', ['order_id' => 1, 'shipping_status' => 'Pending']);
    }

    /** @test */
    public function it_can_show_shipping_details()
    {
        Gate::define('view', function ($user, $shipping) {
            return true;
        });

        $shipping = Shipping::factory()->create();

        $response = $this->getJson(route('shipping.show', $shipping->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $shipping->id]);
    }

    /** @test */
    public function it_can_update_shipping_details()
    {
        Gate::define('update', function ($user, $shipping) {
            return true;
        });

        $shipping = Shipping::factory()->create();

        $response = $this->putJson(route('shipping.update', $shipping->id), ['shipping_status' => 'Shipped']);

        $response->assertStatus(200)
            ->assertJsonFragment(['shipping_status' => 'Shipped']);

        $this->assertDatabaseHas('shipping', ['id' => $shipping->id, 'shipping_status' => 'Shipped']);
    }

    /** @test */
    public function it_can_delete_shipping_details()
    {
        Gate::define('delete', function ($user, $shipping) {
            return true;
        });

        $shipping = Shipping::factory()->create();

        $response = $this->deleteJson(route('shipping.destroy', $shipping->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('shipping', ['id' => $shipping->id]);
    }
}
