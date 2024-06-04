<?php

namespace GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateInventoryMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_inventory_mutation()
    {
        $mutation = '
            mutation($input: InventoryInput!) {
                createInventory(input: $input) {
                    id
                    product {
                        product_name
                    }
                    quantity
                    location
                }
            }
        ';

        $variables = [
            'input' => [
                'product_id' => 1,
                'quantity' => 50,
                'location' => 'Warehouse 1'
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createInventory' => [
                    'quantity' => 50,
                    'location' => 'Warehouse 1'
                ]
            ]
        ]);
    }
}
