<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createUser',
        'description' => 'Create a new user',
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('UserInput'),
                'description' => 'Input for user',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-user:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-user', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['username'] = $purifier->purify($input['username']);
        $input['email'] = $purifier->purify($input['email']);
        $input['password'] = $purifier->purify($input['password']);
        $input['role_id'] = $purifier->purify($input['role_id']);

        // Validate input data
        $validator = Validator::make($input, [
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the user
        $user = new User();
        $user->username = $input['username'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->role_id = $input['role_id'];
        $user->save();

        // Logging
        Log::info('User created', ['user_id' => $user->id, 'new_user_id' => $user->id]);

        return $user;
    }
}
