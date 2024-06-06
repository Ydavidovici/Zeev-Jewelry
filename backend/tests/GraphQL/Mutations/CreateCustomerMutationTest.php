<?php

namespace Tests\GraphQL\Mutations;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCustomerMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_customer_mutation()
    {
        $mutation = '
            mutation($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    address
                    email
                }
            }
        ';

        $variables = [
            'input' => [
                'user_id' => 1,
                'address' => '123 Main St, Anytown, USA',
                'phone_number' => '123-456-7890',
                'email' => 'customer@example.com',
                'is_guest' => false
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createCustomer' => [
                    'address' => '123 Main St, Anytown, USA',
                    'email' => 'customer@example.com'
                ]
            ]
        ]);
    }
}
