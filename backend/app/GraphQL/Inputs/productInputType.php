<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class ProductInputType extends InputType
{
    protected $attributes = [
        'name' => 'ProductInput',
        'description' => 'Input type for product',
    ];

    public function fields(): array
    {
        return [
            'product_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the product',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of the product',
            ],
            'price' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The price of the product',
            ],
            'category_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The category ID of the product',
            ],
            'image_url' => [
                'type' => Type::string(),
                'description' => 'The image URL of the product',
            ],
        ];
    }
}
