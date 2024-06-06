<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class InventoryInputTypeTest extends TestCase
{
    public function testInventoryInputType()
    {
        $query = '
            mutation {
                createInventory(input: {
                    product_id: 1,
                    quantity: 100,
                    location: "Warehouse 1"
                }) {
                    product_id
                    quantity
                    location
                }
            }
        ';

        $response = $this->graphql($query);

        $response->assertJsonStructure([
            'data' => [
                'createInventory' => [
                    'product_id',
                    'quantity',
                    'location',
                ],
            ],
        ]);
    }
}
