<?php

namespace App\GraphQL\Mutations;

use App\Models\Payment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

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
        $user = auth()->user();
        $key = 'create-payment:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-payment', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['order_id'] = $purifier->purify($input['order_id']);
        $input['payment_type'] = $purifier->purify($input['payment_type']);
        $input['payment_status'] = $purifier->purify($input['payment_status']);

        // Validate input data
        $validator = Validator::make($input, [
            'order_id' => 'required|integer|exists:orders,id',
            'payment_type' => 'required|string|max:255',
            'payment_status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the payment
        $payment = new Payment();
        $payment->order_id = $input['order_id'];
        $payment->payment_type = $input['payment_type'];
        $payment->payment_status = $input['payment_status'];
        $payment->save();

        // Logging
        Log::info('Payment created', ['user_id' => $user->id, 'payment_id' => $payment->id]);

        return $payment;
    }
}
