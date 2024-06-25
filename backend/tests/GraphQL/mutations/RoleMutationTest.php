<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateRoleMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_a_role()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
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

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    // Missing role_name
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The role name field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 12345, // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The role name must be a string.');
    }

    /** @test */
    public function it_fails_with_duplicate_role_name()
    {
        // First, create a role
        Role::create(['role_name' => 'Duplicate Role']);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Duplicate Role', // Duplicate role name
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The role name has already been taken.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => '<script>alert(1)</script>',
                ],
            ],
        ]);

        // This should succeed but the sanitized role_name should be saved
        $response->assertJson([
            'data' => [
                'createRole' => [
                    'role_name' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-role', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-role:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Role created', ['user_id' => $this->user->id, 'role_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
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

    /** @test */
    public function it_measures_performance()
    {
        // Measure performance (this is a basic example, for real performance testing consider using a dedicated tool)
        $startTime = microtime(true);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => 'Test Role',
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
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => '1 OR 1=1',
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
                mutation CreateRole($input: RoleInput!) {
                    createRole(input: $input) {
                        id
                        role_name
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'role_name' => '', // Invalid input
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The role name field is required.');
    }
}
