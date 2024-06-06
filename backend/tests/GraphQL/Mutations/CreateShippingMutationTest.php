<?php

namespace tests\GraphQL\Mutations;

use App\Models\Order;
use Tests\TestCase;

class CreateShippingMutationTest extends TestCase
{
    /**
     * Test creating a new shipping entry.
     *
     * @return void
     */
    public function testCreateShipping()
    {
        $order = Order::factory()->create();

        $response = $this->graphql('
            mutation($input: ShippingInput!) {
                createShipping(input: $input) {
                    id
                    order {
                        id
                    }
                    shipping_type
                    shipping_cost
                    shipping_status
                    tracking_number
                    shipping_address
                }
            }
        ', [
            'input' => [
                'order_id' => $order->id,
                'shipping_type' => 'Standard',
                'shipping_cost' => 10.50,
                'shipping_status' => 'pending',
                'tracking_number' => '1234567890',
                'shipping_address' => '123 Main St, Anytown, USA'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createShipping' => [
                    'id',
                    'order' => [
                        'id'
                    ],
                    'shipping_type',
                    'shipping_cost',
                    'shipping_status',
                    'tracking_number',
                    'shipping_address'
                ]
            ]
        ]);

        $this->assertDatabaseHas('shippings', [
            'order_id' => $order->id,
            'shipping_type' => 'Standard',
            'shipping_cost' => 10.50,
            'shipping_status' => 'pending',
            'tracking_number' => '1234567890',
            'shipping_address' => '123 Main St, Anytown, USA'
        ]);
    }
}
