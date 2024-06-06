<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class UserInputTypeTest extends TestCase
{
    public function testUserInputType()
    {
        $response = $this->graphql('
            mutation($input: UserInput!) {
                createUser(input: $input) {
                    user_id
                    username
                    email
                    role {
                        role_id
                    }
                    created_at
                    updated_at
                }
            }
        ', [
            'input' => [
                'username' => 'testuser',
                'email' => 'testuser@example.com',
                'password' => 'password',
                'role_id' => 1
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createUser' => [
                    'user_id',
                    'username',
                    'email',
                    'role' => [
                        'role_id'
                    ],
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }
}
