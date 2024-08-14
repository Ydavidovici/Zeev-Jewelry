<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_categories()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Assuming you expect an array of categories
    }

    public function test_user_can_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/categories', [
            'category_name' => 'New Category',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'category_name']);
    }

    public function test_user_can_view_single_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'category_name']);
    }

    public function test_user_can_update_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->putJson("/api/categories/{$category->id}", [
            'category_name' => 'Updated Category',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'category_name']);
    }

    public function test_user_can_delete_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
