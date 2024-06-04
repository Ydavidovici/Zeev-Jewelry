<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class CustomerTypeTest extends TestCase
{
    public function testCustomerTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Customer") {
                name
                fields {
                    name
                    type {
                        name
                        kind
                    }
                }
            }
        }
        ';

        $result = $this->graphql($schema);

        $this->assertSame('Customer', $result->json('data.__type.name'));
        $this->assertCount(6, $result->json('data.__type.fields'));
    }
}
