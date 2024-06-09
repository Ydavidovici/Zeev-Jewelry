<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class PaymentCRUDTest extends TestCase
{
    public function testPaymentCRUDOperations()
    {
        // Create a new payment
        $createResponse = $this->graphql('
            mutation {
                createPayment(input: {
                    order_id: 1,
                    amount: 100.00,
                    method: "Credit Card"
                }) {
                    id
                    order_id
                    amount
                    method
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createPayment' => [
                    'id',
                    'order_id',
                    'amount',
                    'method'
                ]
            ]
        ]);

        $paymentId = $createResponse->json('data.createPayment.id');

        // Read the created payment
        $readResponse = $this->graphql('
            query {
                payment(id: ' . $paymentId . ') {
                    id
                    order_id
                    amount
                    method
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'payment' => [
                    'id' => $paymentId,
                    'order_id' => 1,
                    'amount' => 100.00,
                    'method' => 'Credit Card'
                ]
            ]
        ]);

        // Update the payment
        $updateResponse = $this->graphql('
            mutation {
                updatePayment(id: ' . $paymentId . ', input: {
                    amount: 150.00
                }) {
                    id
                    order_id
                    amount
                    method
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updatePayment' => [
                    'id' => $paymentId,
                    'order_id' => 1,
                    'amount' => 150.00,
                    'method' => 'Credit Card'
                ]
            ]
        ]);

        // Delete the payment
        $deleteResponse = $this->graphql('
            mutation {
                deletePayment(id: ' . $paymentId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deletePayment' => [
                    'id' => $paymentId
                ]
            ]
        ]);

        // Verify the payment has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                payment(id: ' . $paymentId . ') {
                    id
                    order_id
                    amount
                    method
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'payment' => null
            ]
        ]);
    }
}
