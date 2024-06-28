<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdatePaymentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updatePayment',
        'description' => 'Update an existing payment'
    ];

    public function type(): Type
    {
        return GraphQL::type('Payment');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('PaymentInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-payment:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-payment', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the payment
        $payment = Payment::find($args['id']);
        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        $validator = Validator::make($args['input'], [
            'amount' => 'required|numeric|min:0',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $payment->update($args['input']);

        // Logging
        Log::info('Payment updated', ['user_id' => $user->id, 'payment_id' => $args['id']]);

        return $payment;
    }
}
