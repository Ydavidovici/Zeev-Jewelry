<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function graphql($query, $variables = [])
    {
        return $this->postJson('/graphql', [
            'query' => $query,
            'variables' => $variables,
        ]);
    }
}
