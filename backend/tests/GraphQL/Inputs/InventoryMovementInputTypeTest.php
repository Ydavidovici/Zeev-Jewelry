<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class InventoryMovementInputTypeTest extends TestCase
{
    public function testInventoryMovementInputType()
    {
        $response = $this->graphql('
            mutation($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
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
            'input' => [
                'inventory_id' => 1,
                'type' => 'addition',
                'quantity_change' => 10,
                'movement_date' => now()
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createInventoryMovement' => [
                    'id',
                    'inventory' => [
                        'id'
                    ],
                    'type',
                    'quantity_change',
                    'movement_date'
                ]
            ]
        ]);
    }
}
