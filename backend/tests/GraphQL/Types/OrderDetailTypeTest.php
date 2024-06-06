<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class OrderDetailTypeTest extends TestCase
{
    public function testOrderDetailTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "OrderDetail") {
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

        $this->assertSame('OrderDetail', $result->json('data.__type.name'));
        $this->assertCount(5, $result->json('data.__type.fields'));
    }
}
