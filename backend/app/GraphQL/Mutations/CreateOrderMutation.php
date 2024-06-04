<?php

namespace App\GraphQL\Mutations;

use App\Models\Order;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateOrderMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createOrder',
        'description' => 'Create a new order',
    ];

    public function type(): Type
    {
        return GraphQL::type('Order');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('OrderInput'),
                'description' => 'Input for order',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $order = new Order();
        $order->customer_id = $input['customer_id'];
        $order->order_date = now();
        $order->total_amount = $input['total_amount'];
        $order->is_guest = $input['is_guest'];
        $order->status = $input['status'];
        $order->save();

        return $order;
    }
}
