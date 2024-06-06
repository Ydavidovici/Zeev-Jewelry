<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class CategoryTypeTest extends TestCase
{
    public function testCategoryTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Category") {
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

        $this->assertSame('Category', $result->json('data.__type.name'));
        $this->assertCount(2, $result->json('data.__type.fields'));
    }
}
