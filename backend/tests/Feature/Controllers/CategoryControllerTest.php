<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCategoryIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Category::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testCategoryStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'category_name' => 'Electronics',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/categories', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testCategoryShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testCategoryUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $category = Category::factory()->create();

        $data = [
            'category_name' => 'Home Appliances',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testCategoryDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);
    }
}
