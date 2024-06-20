<?php

namespace App\GraphQL\Mutations;

use App\Models\Role;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateRoleMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createRole',
        'description' => 'Create a new role',
    ];

    public function type(): Type
    {
        return GraphQL::type('Role');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('RoleInput'),
                'description' => 'Input for role',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-role:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-role', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['role_name'] = $purifier->purify($input['role_name']);

        // Validate input data
        $validator = Validator::make($input, [
            'role_name' => 'required|string|max:255|unique:roles,role_name',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the role
        $role = new Role();
        $role->role_name = $input['role_name'];
        $role->save();

        // Logging
        Log::info('Role created', ['user_id' => $user->id, 'role_id' => $role->id]);

        return $role;
    }
}
