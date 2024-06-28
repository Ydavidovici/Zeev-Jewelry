<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteInventoryMovementMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteInventoryMovement',
        'description' => 'Delete an existing inventory movement'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::boolean());
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'delete-inventory-movement:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-inventory-movement', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the inventory movement
        $inventoryMovement = InventoryMovement::find($args['id']);
        if (!$inventoryMovement) {
            throw new \Exception('Inventory movement not found');
        }

        $inventoryMovement->delete();

        // Logging
        Log::info('Inventory movement deleted', ['user_id' => $user->id, 'inventory_movement_id' => $args['id']]);

        return true;
    }
}
