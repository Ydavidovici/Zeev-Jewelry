<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class OrderTypeTest extends TestCase
{
    public function testOrderTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Order") {
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

        $this->assertSame('Order', $result->json('data.__type.name'));
        $this->assertCount(6, $result->json('data.__type.fields'));
    }
}
