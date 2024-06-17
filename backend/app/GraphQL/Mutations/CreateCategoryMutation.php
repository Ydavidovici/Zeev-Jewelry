<?php

namespace App\GraphQL\Mutations;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Category;

class CreateCategoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCategory',
        'description' => 'Create a new category'
    ];

    public function type(): Type
    {
        return GraphQL::type('Category');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('CategoryInput')
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $category = new Category();
        $category->category_name = $args['input']['category_name'];
        $category->save();

        return $category;
    }
}
