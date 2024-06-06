<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class ShippingTypeTest extends TestCase
{
    public function testShippingTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Shipping") {
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

        $this->assertSame('Shipping', $result->json('data.__type.name'));
        $this->assertCount(7, $result->json('data.__type.fields'));
    }
}
