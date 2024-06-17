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

        // Output the response for debugging
        var_dump($createResponse->json());

        $createResponse->assertJsonStructure([
            'data' => [
                'createCategory' => [
                    'id',
                    'category_name'
                ]
            ]
        ]);

        // Retrieve the created category by ID
        $categoryId = $createResponse->json('data.createCategory.id');
        $retrieveResponse = $this->graphql("
            query {
                category(id: \"$categoryId\") {
                    id
                    category_name
                }
            }
        ");

        // Output the response for debugging
        var_dump($retrieveResponse->json());

        $retrieveResponse->assertJsonStructure([
            'data' => [
                'category' => [
                    'id',
                    'category_name'
                ]
            ]
        ]);

        // Update the category
        $updateResponse = $this->graphql("
            mutation {
                updateCategory(id: \"$categoryId\", input: {
                    category_name: \"Updated Category\"
                }) {
                    id
                    category_name
                }
            }
        ");

        // Output the response for debugging
        var_dump($updateResponse->json());

        $updateResponse->assertJsonStructure([
            'data' => [
                'updateCategory' => [
                    'id',
                    'category_name'
                ]
            ]
        ]);

        // Delete the category
        $deleteResponse = $this->graphql("
            mutation {
                deleteCategory(id: \"$categoryId\") {
                    id
                }
            }
        ");

        // Output the response for debugging
        var_dump($deleteResponse->json());

        $deleteResponse->assertJsonStructure([
            'data' => [
                'deleteCategory' => [
                    'id'
                ]
            ]
        ]);

        // Confirm the category is deleted
        $confirmDeleteResponse = $this->graphql("
            query {
                category(id: \"$categoryId\") {
                    id
                    category_name
                }
            }
        ");

        // Output the response for debugging
        var_dump($confirmDeleteResponse->json());

        $confirmDeleteResponse->assertJsonMissing(['data' => ['category']]);
    }
}
