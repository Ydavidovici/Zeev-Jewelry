<?php

namespace GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePaymentMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment_mutation()
    {
        $mutation = '
            mutation($input: PaymentInput!) {
                createPayment(input: $input) {
                    id
                    payment_type
                    payment_status
                }
            }
        ';

        $variables = [
            'input' => [
                'order_id' => 1,
                'payment_type' => 'Credit Card',
                'payment_status' => 'processed'
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createPayment' => [
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'processed'
                ]
            ]
        ]);
    }
}
