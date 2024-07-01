<?php

namespace App\GraphQL\Queries;

use App\Models\InventoryMovement;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use HTMLPurifier;
use HTMLPurifier_Config;

class InventoryMovementQuery extends Query
{
    protected $attributes = [
        'name' => 'inventoryMovement',
    ];

    public function type(): Type
    {
        return GraphQL::type('InventoryMovement');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the inventory movement',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'inventory-movement-query:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('view-inventory-movement', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $args['id'] = $purifier->purify($args['id']);

        // Validate input data
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:inventory_movements,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Fetch the inventory movement
        $inventoryMovement = InventoryMovement::find($args['id']);

        // Error handling
        if (!$inventoryMovement) {
            throw new \Exception('Inventory movement not found');
        }

        // Logging
        Log::info('Inventory movement queried', ['user_id' => $user->id, 'inventory_movement_id' => $args['id']]);

        return $inventoryMovement;
    }
}
