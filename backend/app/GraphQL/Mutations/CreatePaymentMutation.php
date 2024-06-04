<?php

namespace App\GraphQL\Mutations;

use App\Models\Payment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreatePaymentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createPayment',
        'description' => 'Create a new payment',
    ];

    public function type(): Type
    {
        return GraphQL::type('Payment');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('PaymentInput'),
                'description' => 'Input for payment',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $payment = new Payment();
        $payment->order_id = $input['order_id'];
        $payment->payment_type = $input['payment_type'];
        $payment->payment_status = $input['payment_status'];
        $payment->save();

        return $payment;
    }
}
