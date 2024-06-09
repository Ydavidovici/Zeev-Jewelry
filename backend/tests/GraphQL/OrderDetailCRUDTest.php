<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class OrderDetailCRUDTest extends TestCase
{
    public function testOrderDetailCRUDOperations()
    {
        // Create a new order detail
        $createResponse = $this->graphql('
            mutation {
                createOrderDetail(input: {
                    order_id: 1,
                    product_id: 1,
                    quantity: 2,
                    price: 49.99
                }) {
                    id
                    order_id
                    product_id
                    quantity
                    price
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createOrderDetail' => [
                    'id',
                    'order_id',
                    'product_id',
                    'quantity',
                    'price'
                ]
            ]
        ]);

        $orderDetailId = $createResponse->json('data.createOrderDetail.id');

        // Read the created order detail
        $readResponse = $this->graphql('
            query {
                orderDetail(id: ' . $orderDetailId . ') {
                    id
                    order_id
                    product_id
                    quantity
                    price
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'orderDetail' => [
                    'id' => $orderDetailId,
                    'order_id' => 1,
                    'product_id' => 1,
                    'quantity' => 2,
                    'price' => 49.99
                ]
            ]
        ]);

        // Update the order detail
        $updateResponse = $this->graphql('
            mutation {
                updateOrderDetail(id: ' . $orderDetailId . ', input: {
                    quantity: 3,
                    price: 59.99
                }) {
                    id
                    order_id
                    product_id
                    quantity
                    price
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateOrderDetail' => [
                    'id' => $orderDetailId,
                    'order_id' => 1,
                    'product_id' => 1,
                    'quantity' => 3,
                    'price' => 59.99
                ]
            ]
        ]);

        // Delete the order detail
        $deleteResponse = $this->graphql('
            mutation {
                deleteOrderDetail(id: ' . $orderDetailId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteOrderDetail' => [
                    'id' => $orderDetailId
                ]
            ]
        ]);

        // Verify the order detail has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                orderDetail(id: ' . $orderDetailId . ') {
                    id
                    order_id
                    product_id
                    quantity
                    price
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'orderDetail' => null
            ]
        ]);
    }
}
