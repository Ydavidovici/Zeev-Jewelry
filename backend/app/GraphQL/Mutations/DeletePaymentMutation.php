<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeletePaymentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deletePayment',
        'description' => 'Delete an existing payment'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::boolean());
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'delete-payment:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-payment', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the payment
        $payment = Payment::find($args['id']);
        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        $payment->delete();

        // Logging
        Log::info('Payment deleted', ['user_id' => $user->id, 'payment_id' => $args['id']]);

        return true;
    }
}
