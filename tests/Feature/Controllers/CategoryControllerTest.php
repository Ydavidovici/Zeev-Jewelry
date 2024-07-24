<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_categories_index()
    {
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function user_can_view_create_category_form()
    {
        $response = $this->get(route('categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('categories.create');
    }

    /** @test */
    public function user_can_create_category()
    {
        $data = [
            'category_name' => 'Test Category',
        ];

        $response = $this->post(route('categories.store'), $data);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', $data);
    }

    /** @test */
    public function user_can_view_edit_category_form()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('categories.edit');
        $response->assertViewHas('category', $category);
    }

    /** @test */
    public function user_can_update_category()
    {
        $category = Category::factory()->create();

        $data = [
            'category_name' => 'Updated Category',
        ];

        $response = $this->put(route('categories.update', $category), $data);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->id], $data));
    }

    /** @test */
    public function user_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
