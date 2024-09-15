<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function category_has_category_name()
    {
        $category = Category::factory()->create(['category_name' => 'Electronics']);

        $this->assertEquals('Electronics', $category->category_name);
    }
}
