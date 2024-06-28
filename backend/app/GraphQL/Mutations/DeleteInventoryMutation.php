<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Inventory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteInventoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteInventory',
        'description' => 'Delete an existing inventory item'
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
        $key = 'delete-inventory:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-inventory', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the inventory item
        $inventory = Inventory::find($args['id']);
        if (!$inventory) {
            throw new \Exception('Inventory item not found');
        }

        $inventory->delete();

        // Logging
        Log::info('Inventory item deleted', ['user_id' => $user->id, 'inventory_id' => $args['id']]);

        return true;
    }
}
