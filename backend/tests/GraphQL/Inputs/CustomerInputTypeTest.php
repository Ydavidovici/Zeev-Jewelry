<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class CustomerInputTypeTest extends TestCase
{
    public function testCustomerInputType()
    {
        $query = <<<'GRAPHQL'
        {
            customer(id: 1) {
                id
                name
                email
            }
        }
        GRAPHQL;

        $response = $this->graphql($query);

        $response->assertJson([
            'data' => [
                'customer' => [
                    'id' => 1,
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                ],
            ],
        ]);
    }
}
