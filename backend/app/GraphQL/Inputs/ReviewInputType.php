<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class ReviewInputType extends InputType
{
    protected $attributes = [
        'name' => 'ReviewInput',
        'description' => 'Input type for review',
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
                'description' => 'The ID of the customer writing the review',
            ],
            'review_text' => [
                'type' => Type::string(),
                'description' => 'The text of the review',
            ],
            'rating' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The rating given by the customer',
            ],
        ];
    }
}
