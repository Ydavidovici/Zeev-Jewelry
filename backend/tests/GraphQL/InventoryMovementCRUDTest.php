<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class InventoryMovementCRUDTest extends TestCase
{
    public function testInventoryMovementCRUDOperations()
    {
        // Create a new inventory movement
        $createResponse = $this->graphql('
            mutation {
                createInventoryMovement(input: {
                    inventory_id: 1,
                    quantity: 10,
                    type: "in"
                }) {
                    id
                    inventory_id
                    quantity
                    type
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createInventoryMovement' => [
                    'id',
                    'inventory_id',
                    'quantity',
                    'type'
                ]
            ]
        ]);

        $inventoryMovementId = $createResponse->json('data.createInventoryMovement.id');

        // Read the created inventory movement
        $readResponse = $this->graphql('
            query {
                inventoryMovement(id: ' . $inventoryMovementId . ') {
                    id
                    inventory_id
                    quantity
                    type
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'inventoryMovement' => [
                    'id' => $inventoryMovementId,
                    'inventory_id' => 1,
                    'quantity' => 10,
                    'type' => 'in'
                ]
            ]
        ]);

        // Update the inventory movement
        $updateResponse = $this->graphql('
            mutation {
                updateInventoryMovement(id: ' . $inventoryMovementId . ', input: {
                    quantity: 20,
                    type: "out"
                }) {
                    id
                    inventory_id
                    quantity
                    type
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateInventoryMovement' => [
                    'id' => $inventoryMovementId,
                    'inventory_id' => 1,
                    'quantity' => 20,
                    'type' => 'out'
                ]
            ]
        ]);

        // Delete the inventory movement
        $deleteResponse = $this->graphql('
            mutation {
                deleteInventoryMovement(id: ' . $inventoryMovementId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteInventoryMovement' => [
                    'id' => $inventoryMovementId
                ]
            ]
        ]);

        // Verify the inventory movement has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                inventoryMovement(id: ' . $inventoryMovementId . ') {
                    id
                    inventory_id
                    quantity
                    type
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'inventoryMovement' => null
            ]
        ]);
    }
}
