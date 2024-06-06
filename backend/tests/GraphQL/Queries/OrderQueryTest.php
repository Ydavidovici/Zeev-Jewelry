<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class OrderQueryTest extends TestCase
{
    public function testOrderQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                order(id: $id) {
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
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'order' => [
                    'id',
                    'customer' => [
                        'id'
                    ],
                    'order_date',
                    'total_amount',
                    'is_guest',
                    'status',
                ]
            ]
        ]);
    }
}
