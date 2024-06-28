<?php

namespace Tests\GraphQL;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_it_creates_a_role()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createRole' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);
    }

    public function test_it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    // Missing role_name
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'Field "createRole" argument "input" of type "RoleInput!" is required but not provided.');
    }

    public function test_it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 12345, // Invalid data type
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'Argument "input" has invalid value 12345.');
    }

    public function test_it_fails_with_duplicate_role_name()
    {
        Role::create(['role_name' => 'Duplicate Role']);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Duplicate Role', // Duplicate role name
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'The role name has already been taken.');
    }

    public function test_it_sanitizes_inputs()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => '<script>alert(1)</script>',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createRole' => [
                    'role_name' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    public function test_it_requires_authorization()
    {
        Gate::shouldReceive('denies')->with('create-role', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'Unauthorized');
    }

    public function test_it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-role:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'Too many attempts. Please try again later.');
    }

    public function test_it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Role created', ['user_id' => $this->user->id, 'role_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);
    }

    public function test_it_measures_performance()
    {
        $startTime = microtime(true);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Performance Test Role',
                ],
            ],
        ]);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertLessThan(1, $executionTime, 'The mutation took too long to execute.');
    }

    public function test_it_is_secure()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => '1 OR 1=1',
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'The given data was invalid.');
    }

    public function test_it_handles_validation_errors()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        role_id
                        role_name
                        created_at
                        updated_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => '', // Invalid input
                ],
            ],
        ]);

        $this->assertGraphQLErrorMessage($response, 'Variable "$input" got invalid value null at "input.role_name"; Expected non-nullable type "String!" not to be null.');
    }

    protected function assertGraphQLErrorMessage($response, $message)
    {
        $response->assertStatus(200);
        $errors = $response->json('errors');
        $this->assertNotEmpty($errors);
        $this->assertEquals($message, $errors[0]['message']);
    }
}
