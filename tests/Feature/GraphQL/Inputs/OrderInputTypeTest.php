<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class OrderInputTypeTest extends TestCase
{
    public function testOrderInputType()
    {
        $response = $this->graphql('
            mutation($input: OrderInput!) {
                createOrder(input: $input) {
                    id
                    customer {
                        id
                    }
                    order_date
                    total_amount
                    is_guest
                    status
                }
            }
        ', [
            'input' => [
                'customer_id' => 1,
                'order_date' => now(),
                'total_amount' => 100.00,
                'is_guest' => false,
                'status' => 'pending'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createOrder' => [
                    'id',
                    'customer' => [
                        'id'
                    ],
                    'order_date',
                    'total_amount',
                    'is_guest',
                    'status'
                ]
            ]
        ]);
    }
}
