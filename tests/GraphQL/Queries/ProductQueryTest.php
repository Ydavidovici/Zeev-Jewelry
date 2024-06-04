<?php

namespace tests\GraphQL\Queries;

use Tests\TestCase;

class ProductQueryTest extends TestCase
{
    public function testProductQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                product(id: $id) {
                    id
                    product_name
                    description
                    price
                    category {
                        id
                    }
                    image_url
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'product' => [
                    'id',
                    'product_name',
                    'description',
                    'price',
                    'category' => [
                        'id'
                    ],
                    'image_url',
                ]
            ]
        ]);
    }
}
