<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class CategoryInputTypeTest extends TestCase
{
    public function testCategoryInputType()
    {
        $response = $this->graphql('
            mutation {
                createCategory(input: {
                    name: "Test Category"
                }) {
                    id
                    name
                }
            }
        ');

        $response->assertJsonStructure([
            'data' => [
                'createCategory' => [
                    'id',
                    'name'
                ]
            ]
        ]);
    }
}
