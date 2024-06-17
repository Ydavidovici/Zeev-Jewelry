<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class SimpleQuery extends Query
{
    protected $attributes = [
        'name' => 'simpleQuery',
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function resolve($root, $args)
    {
        return 'Hello, World!';
    }
}
