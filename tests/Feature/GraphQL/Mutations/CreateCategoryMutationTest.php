<?php

namespace GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCategoryMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_category_mutation()
    {
        $mutation = '
            mutation($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ';

        $variables = [
            'input' => [
                'category_name' => 'New Category'
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createCategory' => [
                    'category_name' => 'New Category'
                ]
            ]
        ]);
    }
}
