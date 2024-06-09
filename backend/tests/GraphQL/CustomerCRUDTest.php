<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class CustomerCRUDTest extends TestCase
{
    public function testCustomerCRUDOperations()
    {
        // Create a new customer
        $createResponse = $this->graphql('
            mutation {
                createCustomer(input: {
                    name: "John Doe",
                    email: "john@example.com"
                }) {
                    id
                    name
                    email
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createCustomer' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);

        $customerId = $createResponse->json('data.createCustomer.id');

        // Read the created customer
        $readResponse = $this->graphql('
            query {
                customer(id: ' . $customerId . ') {
                    id
                    name
                    email
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'customer' => [
                    'id' => $customerId,
                    'name' => 'John Doe',
                    'email' => 'john@example.com'
                ]
            ]
        ]);

        // Update the customer
        $updateResponse = $this->graphql('
            mutation {
                updateCustomer(id: ' . $customerId . ', input: {
                    name: "Jane Doe",
                    email: "jane@example.com"
                }) {
                    id
                    name
                    email
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateCustomer' => [
                    'id' => $customerId,
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com'
                ]
            ]
        ]);

        // Delete the customer
        $deleteResponse = $this->graphql('
            mutation {
                deleteCustomer(id: ' . $customerId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteCustomer' => [
                    'id' => $customerId
                ]
            ]
        ]);

        // Verify the customer has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                customer(id: ' . $customerId . ') {
                    id
                    name
                    email
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'customer' => null
            ]
        ]);
    }
}
