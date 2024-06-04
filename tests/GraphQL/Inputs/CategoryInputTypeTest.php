<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class CategoryInputTypeTest extends TestCase
{
    public function testCategoryInputType()
    {
        $response = $this->graphql('
            mutation($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => 'Necklaces'
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createCategory' => [
                    'id',
                    'category_name'
                ]
            ]
        ]);
    }
}
