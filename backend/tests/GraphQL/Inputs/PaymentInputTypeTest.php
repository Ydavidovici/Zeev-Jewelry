<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class PaymentInputTypeTest extends TestCase
{
    public function testPaymentInputType()
    {
        $response = $this->graphql('
            mutation($input: PaymentInput!) {
                createPayment(input: $input) {
                    id
                    order {
                        id
                    }
                    payment_type
                    payment_status
                }
            }
        ', [
            'input' => [
                'order_id' => 1,
                'payment_type' => 'Credit Card',
                'payment_status' => 'processed'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createPayment' => [
                    'id',
                    'order' => [
                        'id'
                    ],
                    'payment_type',
                    'payment_status'
                ]
            ]
        ]);
    }
}
