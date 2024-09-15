<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Only create the role if it doesn't exist
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Create admin user and assign the 'admin' role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');
    }

    #[Test]
    public function it_can_view_all_categories()
    {
        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'category_name']]);
    }

    #[Test]
    public function it_can_create_a_category()
    {
        $categoryData = ['category_name' => 'New Category'];

        $response = $this->postJson(route('categories.store'), $categoryData);

        $response->assertStatus(201)
            ->assertJsonFragment(['category_name' => 'New Category']);

        $this->assertDatabaseHas('categories', ['category_name' => 'New Category']);
    }

    #[Test]
    public function it_can_show_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.show', $category->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $category->id]);
    }

    #[Test]
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson(route('categories.update', $category->id), ['category_name' => 'Updated Category']);

        $response->assertStatus(200)
            ->assertJsonFragment(['category_name' => 'Updated Category']);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'category_name' => 'Updated Category']);
    }

    #[Test]
    public function it_can_delete_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson(route('categories.destroy', $category->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
