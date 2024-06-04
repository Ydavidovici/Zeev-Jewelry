<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class OrderDetailInputTypeTest extends TestCase
{
    public function testOrderDetailInputType()
    {
        $response = $this->graphql('
            mutation($input: OrderDetailInput!) {
                createOrderDetail(input: $input) {
                    id
                    order {
                        id
                    }
                    product {
                        id
                    }
                    quantity
                    price
                }
            }
        ', [
            'input' => [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 50.00
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createOrderDetail' => [
                    'id',
                    'order' => [
                        'id'
                    ],
                    'product' => [
                        'id'
                    ],
                    'quantity',
                    'price'
                ]
            ]
        ]);
    }
}
