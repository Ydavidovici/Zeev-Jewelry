<?php

namespace Tests\GraphQL\Queries;

use Tests\TestCase;

class CategoryQueryTest extends TestCase
{
    public function testCategoryQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                category(id: $id) {
                    id
                    category_name
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'category' => [
                    'id',
                    'category_name',
                ]
            ]
        ]);
    }
}
