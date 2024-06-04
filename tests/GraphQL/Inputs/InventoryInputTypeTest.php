<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class InventoryInputTypeTest extends TestCase
{
    public function testInventoryInputType()
    {
        $response = $this->graphql('
            mutation($input: InventoryInput!) {
                createInventory(input: $input) {
                    id
                    product {
                        id
                    }
                    quantity
                    location
                }
            }
        ', [
            'input' => [
                'product_id' => 1,
                'quantity' => 50,
                'location' => 'Warehouse 1'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createInventory' => [
                    'id',
                    'product' => [
                        'id'
                    ],
                    'quantity',
                    'location'
                ]
            ]
        ]);
    }
}
