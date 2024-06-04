<?php

namespace App\GraphQL\Types;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Product',
        'description' => 'A product',
        'model' => Product::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the product',
            ],
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
            'category' => [
                'type' => GraphQL::type('Category'),
                'description' => 'The category of the product',
            ],
            'image_url' => [
                'type' => Type::string(),
                'description' => 'The image URL of the product',
            ],
        ];
    }
}
