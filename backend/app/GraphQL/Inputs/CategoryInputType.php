<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class CategoryInputType extends InputType
{
    protected $attributes = [
        'name' => 'CategoryInput',
        'description' => 'Input type for category',
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
