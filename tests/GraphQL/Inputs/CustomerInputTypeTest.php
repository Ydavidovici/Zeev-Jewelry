<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class CustomerInputTypeTest extends TestCase
{
    public function testCustomerInputType()
    {
        $response = $this->graphql('
            mutation($input: CustomerInput!) {
                createCustomer(input: $input) {
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
            'input' => [
                'user_id' => 1,
                'address' => '123 Main St',
                'phone_number' => '123-456-7890',
                'email' => 'customer@example.com',
                'is_guest' => false
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createCustomer' => [
                    'id',
                    'user' => [
                        'id'
                    ],
                    'address',
                    'phone_number',
                    'email',
                    'is_guest'
                ]
            ]
        ]);
    }
}
