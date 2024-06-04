<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class RoleInputTypeTest extends TestCase
{
    public function testRoleInputType()
    {
        $response = $this->graphql('
            mutation($input: RoleInput!) {
                createRole(input: $input) {
                    role_id
                    role_name
                    created_at
                    updated_at
                }
            }
        ', [
            'input' => [
                'role_name' => 'Admin'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createRole' => [
                    'role_id',
                    'role_name',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }
}
