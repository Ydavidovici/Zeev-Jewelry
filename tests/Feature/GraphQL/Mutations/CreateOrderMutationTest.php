<?php

namespace GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Tests\Feature\GraphQL\Mutations\now;

class CreateOrderMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_mutation()
    {
        $mutation = '
            mutation($input: OrderInput!) {
                createOrder(input: $input) {
                    id
                    total_amount
                    status
                }
            }
        ';

        $variables = [
            'input' => [
                'customer_id' => 1,
                'order_date' => now()->toDateTimeString(),
                'total_amount' => 100.00,
                'is_guest' => false,
                'status' => 'pending'
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createOrder' => [
                    'total_amount' => 100.00,
                    'status' => 'pending'
                ]
            ]
        ]);
    }
}
