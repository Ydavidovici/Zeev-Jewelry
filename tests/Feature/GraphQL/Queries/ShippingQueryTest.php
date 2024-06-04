<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class ShippingQueryTest extends TestCase
{
    public function testShippingQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                shipping(id: $id) {
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
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'shipping' => [
                    'id',
                    'order' => [
                        'id'
                    ],
                    'shipping_type',
                    'shipping_cost',
                    'shipping_status',
                    'tracking_number',
                    'shipping_address',
                ]
            ]
        ]);
    }
}
