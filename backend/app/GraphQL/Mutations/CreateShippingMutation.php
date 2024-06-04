<?php

namespace App\GraphQL\Mutations;

use App\Models\Shipping;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateShippingMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createShipping',
        'description' => 'Create a new shipping record',
    ];

    public function type(): Type
    {
        return GraphQL::type('Shipping');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('ShippingInput'),
                'description' => 'Input for shipping',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $shipping = new Shipping();
        $shipping->order_id = $input['order_id'];
        $shipping->shipping_type = $input['shipping_type'];
        $shipping->shipping_cost = $input['shipping_cost'];
        $shipping->shipping_status = $input['shipping_status'];
        $shipping->tracking_number = $input['tracking_number'];
        $shipping->shipping_address = $input['shipping_address'];
        $shipping->save();

        return $shipping;
    }
}
