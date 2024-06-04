<?php

namespace tests\GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Tests\Feature\GraphQL\Mutations\now;

class CreateInventoryMovementMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_inventory_movement_mutation()
    {
        $mutation = '
            mutation($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    type
                    quantity_change
                }
            }
        ';

        $variables = [
            'input' => [
                'inventory_id' => 1,
                'type' => 'addition',
                'quantity_change' => 10,
                'movement_date' => now()->toDateTimeString()
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createInventoryMovement' => [
                    'type' => 'addition',
                    'quantity_change' => 10
                ]
            ]
        ]);
    }
}
