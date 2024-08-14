<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\FilePolicy;
use PHPUnit\Framework\TestCase;

class FilePolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $filePolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin']);
        $this->sellerUser = User::factory()->make(['role' => 'seller']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);

        $this->filePolicy = new FilePolicy();
    }

    public function test_upload()
    {
        $this->assertTrue($this->filePolicy->upload($this->adminUser));
        $this->assertTrue($this->filePolicy->upload($this->sellerUser));
        $this->assertFalse($this->filePolicy->upload($this->customerUser));
    }

    public function test_delete()
    {
        $this->assertTrue($this->filePolicy->delete($this->adminUser));
        $this->assertFalse($this->filePolicy->delete($this->sellerUser));
        $this->assertFalse($this->filePolicy->delete($this->customerUser));
    }
}
