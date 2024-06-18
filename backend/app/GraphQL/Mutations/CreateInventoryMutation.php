<?php

namespace App\GraphQL\Mutations;

use App\Models\Inventory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateInventoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createInventory',
        'description' => 'Create a new inventory record',
    ];

    public function type(): Type
    {
        return GraphQL::type('Inventory');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('InventoryInput'),
                'description' => 'Input for inventory',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-inventory:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-inventory', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['product_id'] = $purifier->purify($input['product_id']);
        $input['quantity'] = $purifier->purify($input['quantity']);
        $input['location'] = $purifier->purify($input['location']);

        // Validate input data
        $validator = Validator::make($input, [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the inventory record
        $inventory = new Inventory();
        $inventory->product_id = $input['product_id'];
        $inventory->quantity = $input['quantity'];
        $inventory->location = $input['location'];
        $inventory->save();

        // Logging
        Log::info('Inventory created', ['user_id' => $user->id, 'inventory_id' => $inventory->id]);

        return $inventory;
    }
}
