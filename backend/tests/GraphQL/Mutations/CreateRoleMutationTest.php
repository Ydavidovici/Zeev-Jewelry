<?php

namespace Tests\GraphQL\Mutations;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRoleMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_role_mutation()
    {
        $mutation = '
            mutation($input: RoleInput!) {
                createRole(input: $input) {
                    id
                    role_name
                }
            }
        ';

        $variables = [
            'input' => [
                'role_name' => 'New Role'
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createRole' => [
                    'role_name' => 'New Role'
                ]
            ]
        ]);
    }
}
