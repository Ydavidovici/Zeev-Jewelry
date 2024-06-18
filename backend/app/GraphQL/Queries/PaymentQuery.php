<?php

namespace App\GraphQL\Queries;

use App\Models\Payment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use HTMLPurifier;
use HTMLPurifier_Config;

class PaymentQuery extends Query
{
    protected $attributes = [
        'name' => 'payment',
    ];

    public function type(): Type
    {
        return GraphQL::type('Payment');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the payment',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'payment-query:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('view-payment', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $args['id'] = $purifier->purify($args['id']);

        // Validate input data
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:payments,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Fetch the payment
        $payment = Payment::find($args['id']);

        // Error handling
        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        // Logging
        Log::info('Payment queried', ['user_id' => $user->id, 'payment_id' => $args['id']]);

        return $payment;
    }
}
