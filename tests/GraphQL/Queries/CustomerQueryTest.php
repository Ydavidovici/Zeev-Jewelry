<?php

namespace tests\GraphQL\Queries;

use Tests\TestCase;

class CustomerQueryTest extends TestCase
{
    public function testCustomerQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                customer(id: $id) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'customer' => [
                    'id',
                    'user' => [
                        'id'
                    ],
                    'address',
                    'phone_number',
                    'email',
                    'is_guest',
                ]
            ]
        ]);
    }
}
