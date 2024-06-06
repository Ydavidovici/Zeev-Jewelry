<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_category()
    {
        $category = Category::factory()->create([
            'category_name' => 'Necklaces',
        ]);

        $this->assertDatabaseHas('categories', ['category_name' => 'Necklaces']);
    }
}
