<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class RoleCRUDTest extends TestCase
{
    public function testRoleCRUDOperations()
    {
        // Create a new role
        $createResponse = $this->graphql('
            mutation {
                createRole(input: {
                    role_name: "Admin"
                }) {
                    id
                    role_name
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createRole' => [
                    'id',
                    'role_name'
                ]
            ]
        ]);

        $roleId = $createResponse->json('data.createRole.id');

        // Read the created role
        $readResponse = $this->graphql('
            query {
                role(id: ' . $roleId . ') {
                    id
                    role_name
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'role' => [
                    'id' => $roleId,
                    'role_name' => 'Admin'
                ]
            ]
        ]);

        // Update the role
        $updateResponse = $this->graphql('
            mutation {
                updateRole(id: ' . $roleId . ', input: {
                    role_name: "Super Admin"
                }) {
                    id
                    role_name
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateRole' => [
                    'id' => $roleId,
                    'role_name' => 'Super Admin'
                ]
            ]
        ]);

        // Delete the role
        $deleteResponse = $this->graphql('
            mutation {
                deleteRole(id: ' . $roleId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteRole' => [
                    'id' => $roleId
                ]
            ]
        ]);

        // Verify the role has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                role(id: ' . $roleId . ') {
                    id
                    role_name
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'role' => null
            ]
        ]);
    }
}
