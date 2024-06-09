<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class UserCRUDTest extends TestCase
{
    public function testUserCRUDOperations()
    {
        // Create a new user
        $createResponse = $this->graphql('
            mutation {
                createUser(input: {
                    name: "John Doe",
                    email: "john@example.com",
                    password: "password"
                }) {
                    id
                    name
                    email
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createUser' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);

        $userId = $createResponse->json('data.createUser.id');

        // Read the created user
        $readResponse = $this->graphql('
            query {
                user(id: ' . $userId . ') {
                    id
                    name
                    email
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'user' => [
                    'id' => $userId,
                    'name' => 'John Doe',
                    'email' => 'john@example.com'
                ]
            ]
        ]);

        // Update the user
        $updateResponse = $this->graphql('
            mutation {
                updateUser(id: ' . $userId . ', input: {
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
                'updateUser' => [
                    'id' => $userId,
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com'
                ]
            ]
        ]);

        // Delete the user
        $deleteResponse = $this->graphql('
            mutation {
                deleteUser(id: ' . $userId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteUser' => [
                    'id' => $userId
                ]
            ]
        ]);

        // Verify the user has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                user(id: ' . $userId . ') {
                    id
                    name
                    email
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'user' => null
            ]
        ]);
    }
}
