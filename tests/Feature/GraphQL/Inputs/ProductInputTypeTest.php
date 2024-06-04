<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class ProductInputTypeTest extends TestCase
{
    public function testProductInputType()
    {
        $response = $this->graphql('
            mutation($input: ProductInput!) {
                createProduct(input: $input) {
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
            'input' => [
                'product_name' => 'Gold Necklace',
                'description' => 'A beautiful gold necklace',
                'price' => 499.99,
                'category_id' => 1,
                'image_url' => 'path/to/image.jpg'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createProduct' => [
                    'id',
                    'product_name',
                    'description',
                    'price',
                    'category' => [
                        'id'
                    ],
                    'image_url'
                ]
            ]
        ]);
    }
}
