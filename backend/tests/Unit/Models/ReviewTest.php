<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function seedRoles()
    {
        if (Role::where('name', 'seller')->doesntExist()) {
            Role::create(['name' => 'seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    #[Test]
    public function review_belongs_to_product()
    {
        $product = Product::factory()->create();
        $review = Review::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $review->product);
        $this->assertEquals($product->id, $review->product->id);
    }

    #[Test]
    public function review_belongs_to_user() // Ensure 'user' is used instead of 'customer'
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($user->id, $review->user->id);
    }
}
