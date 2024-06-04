<?php

namespace App\GraphQL\Mutations;

use App\Models\InventoryMovement;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

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
        $input = $args['input'];
        $inventoryMovement = new InventoryMovement();
        $inventoryMovement->inventory_id = $input['inventory_id'];
        $inventoryMovement->type = $input['type'];
        $inventoryMovement->quantity_change = $input['quantity_change'];
        $inventoryMovement->movement_date = $input['movement_date'];
        $inventoryMovement->save();

        return $inventoryMovement;
    }
}
