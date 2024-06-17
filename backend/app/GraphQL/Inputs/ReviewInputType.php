<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class ReviewInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'ReviewInput',
        'description' => 'An input type for reviews',
    ];

    public function fields(): array
    {
        return [
            'product_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the product being reviewed',
            ],
            'customer_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the customer who wrote the review',
            ],
            'review_text' => [
                'type' => Type::string(),
                'description' => 'The text of the review',
            ],
            'rating' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The rating given by the customer',
            ],
            'review_date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date the review was written',
            ],
        ];
    }
}
