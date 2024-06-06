<?php

namespace Tests\GraphQL\Mutations;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_mutation()
    {
        $mutation = '
            mutation($input: UserInput!) {
                createUser(input: $input) {
                    id
                    username
                    email
                }
            }
        ';

        $variables = [
            'input' => [
                'username' => 'testuser',
                'email' => 'testuser@example.com',
                'password' => 'password',
                'role_id' => 2
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createUser' => [
                    'username' => 'testuser',
                    'email' => 'testuser@example.com'
                ]
            ]
        ]);
    }
}
