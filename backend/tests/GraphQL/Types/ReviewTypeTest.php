<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class ReviewTypeTest extends TestCase
{
    public function testReviewTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Review") {
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

        $this->assertSame('Review', $result->json('data.__type.name'));
        $this->assertCount(6, $result->json('data.__type.fields'));
    }
}
