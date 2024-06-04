<?php

namespace App\GraphQL\Mutations;

use App\Models\Inventory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

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
        $input = $args['input'];
        $inventory = new Inventory();
        $inventory->product_id = $input['product_id'];
        $inventory->quantity = $input['quantity'];
        $inventory->location = $input['location'];
        $inventory->save();

        return $inventory;
    }
}
