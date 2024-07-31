<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\WebhookPolicy;
use PHPUnit\Framework\TestCase;

class WebhookPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $webhookPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin']);
        $this->sellerUser = User::factory()->make(['role' => 'seller']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);

        $this->webhookPolicy = new WebhookPolicy();
    }

    public function test_handle()
    {
        $this->assertTrue($this->webhookPolicy->handle($this->adminUser));
        $this->assertFalse($this->webhookPolicy->handle($this->sellerUser));
        $this->assertFalse($this->webhookPolicy->handle($this->customerUser));
    }
}
