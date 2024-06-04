<?php

namespace GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product_mutation()
    {
        $mutation = '
            mutation($input: ProductInput!) {
                createProduct(input: $input) {
                    id
                    product_name
                    price
                    description
                }
            }
        ';

        $variables = [
            'input' => [
                'product_name' => 'Test Product',
                'price' => 99.99,
                'description' => 'This is a test product',
                'category_id' => 1,
                'image_url' => 'path/to/image.jpg'
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createProduct' => [
                    'product_name' => 'Test Product',
                    'price' => 99.99,
                    'description' => 'This is a test product'
                ]
            ]
        ]);
    }
}
