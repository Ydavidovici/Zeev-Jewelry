<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateInventoryMovementMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateInventoryMovement',
        'description' => 'Update an existing inventory movement'
    ];

    public function type(): Type
    {
        return GraphQL::type('InventoryMovement');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('InventoryMovementInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-inventory-movement:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-inventory-movement', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the inventory movement
        $inventoryMovement = InventoryMovement::find($args['id']);
        if (!$inventoryMovement) {
            throw new \Exception('Inventory movement not found');
        }

        $validator = Validator::make($args['input'], [
            'quantity_change' => 'required|integer',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $inventoryMovement->update($args['input']);

        // Logging
        Log::info('Inventory movement updated', ['user_id' => $user->id, 'inventory_movement_id' => $args['id']]);

        return $inventoryMovement;
    }
}
