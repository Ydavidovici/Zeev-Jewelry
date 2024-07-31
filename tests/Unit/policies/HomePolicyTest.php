<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\HomePolicy;
use PHPUnit\Framework\TestCase;

class HomePolicyTest extends TestCase
{
    protected $homePolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->homePolicy = new HomePolicy();
    }

    public function test_view()
    {
        $user = User::factory()->make();
        $this->assertTrue($this->homePolicy->view($user));
    }
}
