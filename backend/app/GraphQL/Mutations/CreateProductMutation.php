<?php

namespace App\GraphQL\Mutations;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateProductMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createProduct',
        'description' => 'Create a new product',
    ];

    public function type(): Type
    {
        return GraphQL::type('Product');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('ProductInput'),
                'description' => 'Input for product',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $product = new Product();
        $product->product_name = $input['product_name'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->category_id = $input['category_id'];
        $product->image_url = $input['image_url'];
        $product->save();

        return $product;
    }
}
