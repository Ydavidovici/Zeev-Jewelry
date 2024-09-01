<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_categories()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'category_name']]);
    }

    /** @test */
    public function it_can_create_a_category()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $categoryData = ['category_name' => 'New Category'];

        $response = $this->postJson(route('categories.store'), $categoryData);

        $response->assertStatus(201)
            ->assertJsonFragment(['category_name' => 'New Category']);

        $this->assertDatabaseHas('categories', ['category_name' => 'New Category']);
    }

    /** @test */
    public function it_can_show_a_category()
    {
        Gate::define('view', function ($user, $category) {
            return true;
        });

        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.show', $category->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $category->id]);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        Gate::define('update', function ($user, $category) {
            return true;
        });

        $category = Category::factory()->create();

        $response = $this->putJson(route('categories.update', $category->id), ['category_name' => 'Updated Category']);

        $response->assertStatus(200)
            ->assertJsonFragment(['category_name' => 'Updated Category']);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'category_name' => 'Updated Category']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        Gate::define('delete', function ($user, $category) {
            return true;
        });

        $category = Category::factory()->create();

        $response = $this->deleteJson(route('categories.destroy', $category->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
