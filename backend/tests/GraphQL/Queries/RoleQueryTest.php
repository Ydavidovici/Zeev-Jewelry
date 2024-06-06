<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class RoleQueryTest extends TestCase
{
    public function testRoleQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                role(id: $id) {
                    role_id
                    role_name
                    created_at
                    updated_at
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'role' => [
                    'role_id',
                    'role_name',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }
}
