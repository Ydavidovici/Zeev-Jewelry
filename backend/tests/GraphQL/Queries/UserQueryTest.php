<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class UserQueryTest extends TestCase
{
    public function testUserQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                user(id: $id) {
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
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'user' => [
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
