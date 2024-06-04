<?php

namespace tests\GraphQL\Queries;

use Tests\TestCase;

class InventoryMovementQueryTest extends TestCase
{
    public function testInventoryMovementQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                inventoryMovement(id: $id) {
                    id
                    inventory {
                        id
                    }
                    type
                    quantity_change
                    movement_date
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'inventoryMovement' => [
                    'id',
                    'inventory' => [
                        'id'
                    ],
                    'type',
                    'quantity_change',
                    'movement_date',
                ]
            ]
        ]);
    }
}
