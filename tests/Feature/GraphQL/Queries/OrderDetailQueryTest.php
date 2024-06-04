<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class OrderDetailQueryTest extends TestCase
{
    public function testOrderDetailQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                orderDetail(id: $id) {
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
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'orderDetail' => [
                    'id',
                    'order' => [
                        'id'
                    ],
                    'product' => [
                        'id'
                    ],
                    'quantity',
                    'price',
                ]
            ]
        ]);
    }
}
