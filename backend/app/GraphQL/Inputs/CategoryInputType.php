<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class CategoryInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'CategoryInput',
        'description' => 'An input type for category',
    ];

    public function fields(): array
    {
        return [
            'category_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the category',
            ],
        ];
    }
}
