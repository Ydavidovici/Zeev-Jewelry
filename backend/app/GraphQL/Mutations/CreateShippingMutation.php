<?php

namespace App\GraphQL\Mutations;

use App\Models\Shipping;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateShippingMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createShipping',
        'description' => 'Create a new shipping record',
    ];

    public function type(): Type
    {
        return GraphQL::type('Shipping');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('ShippingInput'),
                'description' => 'Input for shipping',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-shipping:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-shipping', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['order_id'] = $purifier->purify($input['order_id']);
        $input['shipping_type'] = $purifier->purify($input['shipping_type']);
        $input['shipping_cost'] = $purifier->purify($input['shipping_cost']);
        $input['shipping_status'] = $purifier->purify($input['shipping_status']);
        $input['tracking_number'] = $purifier->purify($input['tracking_number']);
        $input['shipping_address'] = $purifier->purify($input['shipping_address']);

        // Validate input data
        $validator = Validator::make($input, [
            'order_id' => 'required|integer|exists:orders,id',
            'shipping_type' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_status' => 'required|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'shipping_address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the shipping record
        $shipping = new Shipping();
        $shipping->order_id = $input['order_id'];
        $shipping->shipping_type = $input['shipping_type'];
        $shipping->shipping_cost = $input['shipping_cost'];
        $shipping->shipping_status = $input['shipping_status'];
        $shipping->tracking_number = $input['tracking_number'];
        $shipping->shipping_address = $input['shipping_address'];
        $shipping->save();

        // Logging
        Log::info('Shipping record created', ['user_id' => $user->id, 'shipping_id' => $shipping->id]);

        return $shipping;
    }
}
