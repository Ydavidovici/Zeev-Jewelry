<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class OrderCRUDTest extends TestCase
{
    public function testOrderCRUDOperations()
    {
        // Create a new order
        $createResponse = $this->graphql('
            mutation {
                createOrder(input: {
                    customer_id: 1,
                    order_date: "2024-06-10",
                    status: "Pending"
                }) {
                    id
                    customer_id
                    order_date
                    status
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createOrder' => [
                    'id',
                    'customer_id',
                    'order_date',
                    'status'
                ]
            ]
        ]);

        $orderId = $createResponse->json('data.createOrder.id');

        // Read the created order
        $readResponse = $this->graphql('
            query {
                order(id: ' . $orderId . ') {
                    id
                    customer_id
                    order_date
                    status
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'order' => [
                    'id' => $orderId,
                    'customer_id' => 1,
                    'order_date' => '2024-06-10',
                    'status' => 'Pending'
                ]
            ]
        ]);

        // Update the order
        $updateResponse = $this->graphql('
            mutation {
                updateOrder(id: ' . $orderId . ', input: {
                    status: "Completed"
                }) {
                    id
                    customer_id
                    order_date
                    status
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateOrder' => [
                    'id' => $orderId,
                    'customer_id' => 1,
                    'order_date' => '2024-06-10',
                    'status' => 'Completed'
                ]
            ]
        ]);

        // Delete the order
        $deleteResponse = $this->graphql('
            mutation {
                deleteOrder(id: ' . $orderId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteOrder' => [
                    'id' => $orderId
                ]
            ]
        ]);

        // Verify the order has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                order(id: ' . $orderId . ') {
                    id
                    customer_id
                    order_date
                    status
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'order' => null
            ]
        ]);
    }
}
