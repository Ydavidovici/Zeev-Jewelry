<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class ProductCRUDTest extends TestCase
{
    public function testProductCRUDOperations()
    {
        // Create a new product
        $createResponse = $this->graphql('
            mutation {
                createProduct(input: {
                    name: "Test Product",
                    description: "This is a test product",
                    price: 99.99
                }) {
                    id
                    name
                    description
                    price
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createProduct' => [
                    'id',
                    'name',
                    'description',
                    'price'
                ]
            ]
        ]);

        $productId = $createResponse->json('data.createProduct.id');

        // Read the created product
        $readResponse = $this->graphql('
            query {
                product(id: ' . $productId . ') {
                    id
                    name
                    description
                    price
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'product' => [
                    'id' => $productId,
                    'name' => 'Test Product',
                    'description' => 'This is a test product',
                    'price' => 99.99
                ]
            ]
        ]);

        // Update the product
        $updateResponse = $this->graphql('
            mutation {
                updateProduct(id: ' . $productId . ', input: {
                    price: 149.99
                }) {
                    id
                    name
                    description
                    price
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateProduct' => [
                    'id' => $productId,
                    'name' => 'Test Product',
                    'description' => 'This is a test product',
                    'price' => 149.99
                ]
            ]
        ]);

        // Delete the product
        $deleteResponse = $this->graphql('
            mutation {
                deleteProduct(id: ' . $productId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteProduct' => [
                    'id' => $productId
                ]
            ]
        ]);

        // Verify the product has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                product(id: ' . $productId . ') {
                    id
                    name
                    description
                    price
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'product' => null
            ]
        ]);
    }
}
