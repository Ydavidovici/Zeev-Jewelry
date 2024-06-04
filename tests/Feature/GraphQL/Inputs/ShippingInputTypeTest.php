<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class ShippingInputTypeTest extends TestCase
{
    public function testShippingInputType()
    {
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
                'order_id' => 1,
                'shipping_type' => 'Standard',
                'shipping_cost' => 10.00,
                'shipping_status' => 'pending',
                'tracking_number' => '123456789',
                'shipping_address' => '123 Main St'
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
    }
}
