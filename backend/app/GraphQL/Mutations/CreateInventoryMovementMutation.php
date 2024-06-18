<?php

namespace App\GraphQL\Mutations;

use App\Models\InventoryMovement;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateInventoryMovementMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createInventoryMovement',
        'description' => 'Create a new inventory movement',
    ];

    public function type(): Type
    {
        return GraphQL::type('InventoryMovement');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('InventoryMovementInput'),
                'description' => 'Input for inventory movement',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-inventory-movement:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-inventory-movement', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['inventory_id'] = $purifier->purify($input['inventory_id']);
        $input['type'] = $purifier->purify($input['type']);
        $input['quantity_change'] = $purifier->purify($input['quantity_change']);
        $input['movement_date'] = $purifier->purify($input['movement_date']);

        // Validate input data
        $validator = Validator::make($input, [
            'inventory_id' => 'required|integer|exists:inventories,id',
            'type' => 'required|string|max:255',
            'quantity_change' => 'required|integer',
            'movement_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the inventory movement
        $inventoryMovement = new InventoryMovement();
        $inventoryMovement->inventory_id = $input['inventory_id'];
        $inventoryMovement->type = $input['type'];
        $inventoryMovement->quantity_change = $input['quantity_change'];
        $inventoryMovement->movement_date = $input['movement_date'];
        $inventoryMovement->save();

        // Logging
        Log::info('Inventory movement created', ['user_id' => $user->id, 'inventory_movement_id' => $inventoryMovement->id]);

        return $inventoryMovement;
    }
}
