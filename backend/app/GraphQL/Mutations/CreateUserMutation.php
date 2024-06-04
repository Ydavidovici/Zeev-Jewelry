<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

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
        $input = $args['input'];
        $user = new User();
        $user->username = $input['username'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->role_id = $input['role_id'];
        $user->save();

        return $user;
    }
}
