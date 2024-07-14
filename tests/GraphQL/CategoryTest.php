<?php

namespace Tests\GraphQL;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $this->actingAs($this->user, 'web'); // Use 'web' guard
    }

    protected function setUpPermissions(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $permissions = [
            'create category', 'update category', 'delete category'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole->syncPermissions(Permission::all());
    }

    public function test_it_creates_a_category()
    {
        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => 'New Category',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createCategory' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);

        $this->assertDatabaseHas('categories', [
            'category_name' => 'New Category',
        ]);
    }

    public function test_it_reads_a_category()
    {
        $category = Category::factory()->create([
            'category_name' => 'Read Category',
        ]);

        $response = $this->graphQL('
            query ($id: ID!) {
                category(id: $id) {
                    id
                    category_name
                }
            }
        ', [
            'id' => $category->id,
        ]);

        $response->assertJson([
            'data' => [
                'category' => [
                    'id' => (string) $category->id,
                    'category_name' => 'Read Category',
                ],
            ],
        ]);
    }

    public function test_it_updates_a_category()
    {
        $category = Category::factory()->create([
            'category_name' => 'Old Category',
        ]);

        $response = $this->graphQL('
            mutation ($id: ID!, $category_name: String!) {
                updateCategory(id: $id, category_name: $category_name) {
                    id
                    category_name
                }
            }
        ', [
            'id' => $category->id,
            'category_name' => 'Updated Category',
        ]);

        $response->assertJson([
            'data' => [
                'updateCategory' => [
                    'id' => (string) $category->id,
                    'category_name' => 'Updated Category',
                ],
            ],
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'category_name' => 'Updated Category',
        ]);
    }

    public function test_it_deletes_a_category()
    {
        $category = Category::factory()->create([
            'category_name' => 'Delete Category',
        ]);

        $response = $this->graphQL('
            mutation ($id: ID!) {
                deleteCategory(id: $id)
            }
        ', [
            'id' => $category->id,
        ]);

        $response->assertJson([
            'data' => [
                'deleteCategory' => true,
            ],
        ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_it_fails_with_missing_category_name()
    {
        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Field "category_name" of required type "String!" was not provided.',
                ],
            ],
        ]);
    }

    public function test_it_fails_with_invalid_category_name()
    {
        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => str_repeat('a', 256),
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'The category name field must not be greater than 255 characters.',
                ],
            ],
        ]);
    }

    public function test_it_sanitizes_inputs()
    {
        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => '<script>alert("XSS")</script>Clean Category',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createCategory' => [
                    'category_name' => 'Clean Category',
                ],
            ],
        ]);
    }

    public function test_it_fails_with_too_many_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->graphQL('
                mutation ($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                    }
                }
            ', [
                'input' => [
                    'category_name' => 'Test Category',
                ],
            ]);
        }

        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => 'Test Category',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Too many attempts. Please try again later.',
                ],
            ],
        ]);
    }

    public function test_it_fails_when_user_is_not_authenticated()
    {
        // Do not set up any authenticated user for this test

        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => 'Test Category',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Unauthenticated.',
                ],
            ],
        ]);
    }

    // Helper function to make GraphQL requests
    protected function graphQL($query, $variables = [])
    {
        return $this->postJson('/graphql', [
            'query' => $query,
            'variables' => $variables,
        ]);
    }
}
