<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class CategoryCRUDTest extends TestCase
{
    public function testCategoryCRUDOperations()
    {
        // Create a new category
        $createResponse = $this->graphql('
            mutation {
                createCategory(input: {
                    category_name: "Test Category"
                }) {
                    id
                    category_name
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createCategory' => [
                    'id',
                    'category_name'
                ]
            ]
        ]);

        $categoryId = $createResponse->json('data.createCategory.id');

        // Read the created category
        $readResponse = $this->graphql('
            query {
                category(id: ' . $categoryId . ') {
                    id
                    category_name
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'category' => [
                    'id' => $categoryId,
                    'category_name' => 'Test Category'
                ]
            ]
        ]);

        // Update the category
        $updateResponse = $this->graphql('
            mutation {
                updateCategory(id: ' . $categoryId . ', input: {
                    category_name: "Updated Category"
                }) {
                    id
                    category_name
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateCategory' => [
                    'id' => $categoryId,
                    'category_name' => 'Updated Category'
                ]
            ]
        ]);

        // Delete the category
        $deleteResponse = $this->graphql('
            mutation {
                deleteCategory(id: ' . $categoryId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteCategory' => [
                    'id' => $categoryId
                ]
            ]
        ]);

        // Verify the category has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                category(id: ' . $categoryId . ') {
                    id
                    category_name
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'category' => null
            ]
        ]);
    }
}
