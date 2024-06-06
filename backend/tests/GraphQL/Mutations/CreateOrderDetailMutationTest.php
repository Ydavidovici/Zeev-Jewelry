<?php

namespace tests\GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderDetailMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_detail_mutation()
    {
        $mutation = '
            mutation($input: OrderDetailInput!) {
                createOrderDetail(input: $input) {
                    id
                    quantity
                    price
                }
            }
        ';

        $variables = [
            'input' => [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 50.00
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createOrderDetail' => [
                    'quantity' => 2,
                    'price' => 50.00
                ]
            ]
        ]);
    }
}
