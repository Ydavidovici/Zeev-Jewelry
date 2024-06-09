<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class InventoryCRUDTest extends TestCase
{
    public function testInventoryCRUDOperations()
    {
        // Create a new inventory item
        $createResponse = $this->graphql('
            mutation {
                createInventory(input: {
                    product_id: 1,
                    quantity: 100
                }) {
                    id
                    product_id
                    quantity
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createInventory' => [
                    'id',
                    'product_id',
                    'quantity'
                ]
            ]
        ]);

        $inventoryId = $createResponse->json('data.createInventory.id');

        // Read the created inventory item
        $readResponse = $this->graphql('
            query {
                inventory(id: ' . $inventoryId . ') {
                    id
                    product_id
                    quantity
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'inventory' => [
                    'id' => $inventoryId,
                    'product_id' => 1,
                    'quantity' => 100
                ]
            ]
        ]);

        // Update the inventory item
        $updateResponse = $this->graphql('
            mutation {
                updateInventory(id: ' . $inventoryId . ', input: {
                    quantity: 200
                }) {
                    id
                    product_id
                    quantity
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateInventory' => [
                    'id' => $inventoryId,
                    'product_id' => 1,
                    'quantity' => 200
                ]
            ]
        ]);

        // Delete the inventory item
        $deleteResponse = $this->graphql('
            mutation {
                deleteInventory(id: ' . $inventoryId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteInventory' => [
                    'id' => $inventoryId
                ]
            ]
        ]);

        // Verify the inventory item has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                inventory(id: ' . $inventoryId . ') {
                    id
                    product_id
                    quantity
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'inventory' => null
            ]
        ]);
    }
}
