<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class UserTypeTest extends TestCase
{
    public function testUserTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "User") {
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

        $this->assertSame('User', $result->json('data.__type.name'));
        $this->assertCount(5, $result->json('data.__type.fields'));
    }
}
