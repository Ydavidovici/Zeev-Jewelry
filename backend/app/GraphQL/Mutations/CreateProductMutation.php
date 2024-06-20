<?php

namespace App\GraphQL\Mutations;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

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
        $user = auth()->user();
        $key = 'create-product:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-product', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['product_name'] = $purifier->purify($input['product_name']);
        $input['description'] = $purifier->purify($input['description']);
        $input['price'] = $purifier->purify($input['price']);
        $input['category_id'] = $purifier->purify($input['category_id']);
        $input['image_url'] = $purifier->purify($input['image_url']);

        // Validate input data
        $validator = Validator::make($input, [
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'image_url' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the product
        $product = new Product();
        $product->product_name = $input['product_name'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->category_id = $input['category_id'];
        $product->image_url = $input['image_url'];
        $product->save();

        // Logging
        Log::info('Product created', ['user_id' => $user->id, 'product_id' => $product->id]);

        return $product;
    }
}
