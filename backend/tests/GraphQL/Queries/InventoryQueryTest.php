<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class InventoryQueryTest extends TestCase
{
    public function testInventoryQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                inventory(id: $id) {
                    id
                    product {
                        id
                    }
                    quantity
                    location
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'inventory' => [
                    'id',
                    'product' => [
                        'id'
                    ],
                    'quantity',
                    'location',
                ]
            ]
        ]);
    }
}
