<?php

namespace App\GraphQL\Queries;

use App\Models\Inventory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class InventoryQuery extends Query
{
    protected $attributes = [
        'name' => 'inventory',
    ];

    public function type(): Type
    {
        return GraphQL::type('Inventory');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the inventory record',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Inventory::find($args['id']);
    }
}
