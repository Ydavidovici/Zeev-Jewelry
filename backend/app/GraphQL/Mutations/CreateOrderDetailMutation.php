<?php

namespace App\GraphQL\Mutations;

use App\Models\OrderDetail;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateOrderDetailMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createOrderDetail',
        'description' => 'Create a new order detail',
    ];

    public function type(): Type
    {
        return GraphQL::type('OrderDetail');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('OrderDetailInput'),
                'description' => 'Input for order detail',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $orderDetail = new OrderDetail();
        $orderDetail->order_id = $input['order_id'];
        $orderDetail->product_id = $input['product_id'];
        $orderDetail->quantity = $input['quantity'];
        $orderDetail->price = $input['price'];
        $orderDetail->save();

        return $orderDetail;
    }
}
