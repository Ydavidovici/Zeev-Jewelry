<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class RoleTypeTest extends TestCase
{
    public function testRoleTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Role") {
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

        $this->assertSame('Role', $result->json('data.__type.name'));
        $this->assertCount(4, $result->json('data.__type.fields'));
    }
}
