<?php

namespace Tests\GraphQL;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $this->user->assignRole('admin');
        $this->actingAs($this->user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_category()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_reads_a_category()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_updates_a_category()
    {
        $category = Category::factory()->create([
            'category_name' => 'Old Category',
        ]);

        $response = $this->graphQL('
            mutation ($id: ID!, $input: CategoryInput!) {
                updateCategory(id: $id, input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'id' => $category->id,
            'input' => [
                'category_name' => 'Updated Category',
            ],
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_deletes_a_category()
    {
        $category = Category::factory()->create([
            'category_name' => 'Delete Category',
        ]);

        $response = $this->graphQL('
            mutation ($id: ID!) {
                deleteCategory(id: $id) {
                    id
                }
            }
        ', [
            'id' => $category->id,
        ]);

        $response->assertJson([
            'data' => [
                'deleteCategory' => [
                    'id' => (string) $category->id,
                ],
            ],
        ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_missing_category_name()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_invalid_category_name()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sanitizes_inputs()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_too_many_attempts()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_requires_authorization()
    {
        Gate::define('create-category', function ($user) {
            return false;
        });

        $response = $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => 'Unauthorized Category',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Unauthorized',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_logs_actions()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Category created', \Mockery::on(function ($data) {
                return isset($data['user_id']) && isset($data['category_id']);
            }));

        $this->graphQL('
            mutation ($input: CategoryInput!) {
                createCategory(input: $input) {
                    id
                    category_name
                }
            }
        ', [
            'input' => [
                'category_name' => 'Log Category',
            ],
        ]);
    }
}
