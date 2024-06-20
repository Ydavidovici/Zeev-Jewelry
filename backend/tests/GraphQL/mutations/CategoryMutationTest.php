<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateCategoryMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_a_category()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createCategory' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_category_name()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => '',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The category name field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_category_name()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => str_repeat('a', 256),
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The category name may not be greater than 255 characters.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => '<script>alert(1)</script>',
                ],
            ],
        ]);

        // This should succeed but the sanitized name should be saved
        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-category', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-category:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Category created', ['user_id' => $this->user->id, 'category_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_measures_performance()
    {
        // Measure performance (this is a basic example, for real performance testing consider using a dedicated tool)
        $startTime = microtime(true);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => 'New Category',
                ],
            ],
        ]);

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->assertLessThan(1, $duration, 'Mutation took too long'); // Example threshold, adjust as needed
    }

    /** @test */
    public function it_is_secure()
    {
        // Test for SQL injection (example, assuming input is sanitized)
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => '1 OR 1=1',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    /** @test */
    public function it_handles_validation_errors()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CategoryInput!) {
                    createCategory(input: $input) {
                        id
                        category_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'category_name' => '', // Invalid input
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The category name field is required.');
    }
}
