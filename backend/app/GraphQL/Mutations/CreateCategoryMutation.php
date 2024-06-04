<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateCategoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCategory',
        'description' => 'Create a new category',
    ];

    public function type(): Type
    {
        return GraphQL::type('Category');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('CategoryInput'),
                'description' => 'Input for category',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $category = new Category();
        $category->category_name = $input['category_name'];
        $category->save();

        return $category;
    }
}
