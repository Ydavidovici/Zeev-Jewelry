<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class PaymentQueryTest extends TestCase
{
    public function testPaymentQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                payment(id: $id) {
                    id
                    order {
                        id
                    }
                    payment_type
                    payment_status
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'payment' => [
                    'id',
                    'order' => [
                        'id'
                    ],
                    'payment_type',
                    'payment_status',
                ]
            ]
        ]);
    }
}
