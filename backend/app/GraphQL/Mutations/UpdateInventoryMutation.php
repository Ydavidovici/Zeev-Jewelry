<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateInventoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateInventory',
        'description' => 'Update an existing inventory'
    ];

    public function type(): Type
    {
        return GraphQL::type('Inventory');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('InventoryInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-inventory:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-inventory', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the inventory
        $inventory = Inventory::find($args['id']);
        if (!$inventory) {
            throw new \Exception('Inventory not found');
        }

        $validator = Validator::make($args['input'], [
            'quantity' => 'required|integer|min:0',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $inventory->update($args['input']);

        // Logging
        Log::info('Inventory updated', ['user_id' => $user->id, 'inventory_id' => $args['id']]);

        return $inventory;
    }
}
