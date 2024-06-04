<?php

namespace Tests\GraphQL\Types;

use Tests\TestCase;

class PaymentTypeTest extends TestCase
{
    public function testPaymentTypeFields()
    {
        $schema = /** @lang GraphQL */ '
        {
            __type(name: "Payment") {
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

        $this->assertSame('Payment', $result->json('data.__type.name'));
        $this->assertCount(5, $result->json('data.__type.fields'));
    }
}
