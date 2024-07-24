<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_category()
    {
        $data = [
            'category_name' => 'Electronics',
        ];

        $response = $this->post(route('categories.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['category_name' => 'Electronics']);
    }

    public function test_read_category()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show', $category->id));

        $response->assertStatus(200);
        $response->assertJson($category->toArray());
    }

    public function test_update_category()
    {
        $category = Category::factory()->create();
        $data = [
            'category_name' => 'Updated Electronics',
        ];

        $response = $this->put(route('categories.update', $category->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'category_name' => 'Updated Electronics']);
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;

        $response = $this->delete(route('categories.destroy', $categoryId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);
    }
}
