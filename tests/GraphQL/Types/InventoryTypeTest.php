<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class InventoryTypeTest extends TestCase
{
    public function testInventoryTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Inventory") {
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

        $this->assertSame('Inventory', $result->json('data.__type.name'));
        $this->assertCount(4, $result->json('data.__type.fields'));
    }
}
