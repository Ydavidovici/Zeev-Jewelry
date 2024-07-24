<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    public function test_category_has_category_name()
    {
        $category = new Category(['category_name' => 'Electronics']);

        $this->assertEquals('Electronics', $category->category_name);
    }
}
