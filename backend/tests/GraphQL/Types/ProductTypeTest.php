<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class ProductTypeTest extends TestCase
{
    public function testProductTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Product") {
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

        $this->assertSame('Product', $result->json('data.__type.name'));
        $this->assertCount(6, $result->json('data.__type.fields'));
    }
}
