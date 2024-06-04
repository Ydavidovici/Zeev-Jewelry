<?php

namespace App\GraphQL\Queries;

use App\Models\OrderDetail;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class OrderDetailQuery extends Query
{
    protected $attributes = [
        'name' => 'orderDetail',
    ];

    public function type(): Type
    {
        return GraphQL::type('OrderDetail');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the order detail',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return OrderDetail::find($args['id']);
    }
}
