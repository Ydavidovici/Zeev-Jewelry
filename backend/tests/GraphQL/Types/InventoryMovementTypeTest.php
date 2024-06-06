<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class InventoryMovementTypeTest extends TestCase
{
    public function testInventoryMovementTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "InventoryMovement") {
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

        $this->assertSame('InventoryMovement', $result->json('data.__type.name'));
        $this->assertCount(5, $result->json('data.__type.fields'));
    }
}
