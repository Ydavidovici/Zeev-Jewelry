<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateProductMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateProduct',
        'description' => 'Update an existing product'
    ];

    public function type(): Type
    {
        return GraphQL::type('Product');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('ProductInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-product:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-product', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the product
        $product = Product::find($args['id']);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        $validator = Validator::make($args['input'], [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $product->update($args['input']);

        // Logging
        Log::info('Product updated', ['user_id' => $user->id, 'product_id' => $args['id']]);

        return $product;
    }
}
