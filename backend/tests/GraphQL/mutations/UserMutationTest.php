<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateUserMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_a_user()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'password' => 'password123',
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createUser' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'role_id' => 1,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    // Missing username, email, password, role_id
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The username field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 12345, // Invalid data type
                    'email' => 'not-an-email', // Invalid data type
                    'password' => true, // Invalid data type
                    'role_id' => 'one', // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The username must be a string.');
    }

    /** @test */
    public function it_fails_with_duplicate_username_and_email()
    {
        // First, create a user
        User::create([
            'username' => 'DuplicateUser',
            'email' => 'duplicate@example.com',
            'password' => bcrypt('password123'),
            'role_id' => 1,
        ]);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'DuplicateUser', // Duplicate username
                    'email' => 'duplicate@example.com', // Duplicate email
                    'password' => 'password123',
                    'role_id' => 1,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The username has already been taken.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => '<script>alert(1)</script>',
                    'email' => 'testuser@example.com',
                    'password' => 'password123',
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
                ],
            ],
        ]);

        // This should succeed but the sanitized username should be saved
        $response->assertJson([
            'data' => [
                'createUser' => [
                    'username' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-user', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'UnauthorizedUser',
                    'email' => 'unauthorizeduser@example.com',
                    'password' => 'password123',
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-user:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'password' => 'password123',
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('User created', ['user_id' => $this->user->id, 'new_user_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'password' => 'password123',
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
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
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'password' => 'password123',
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
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
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'password' => 'password123',
                    'role_id' => '1 OR 1=1',
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
                mutation CreateUser($input: UserInput!) {
                    createUser(input: $input) {
                        id
                        username
                        email
                        role_id
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'username' => 'TestUser',
                    'email' => 'testuser@example.com',
                    'password' => '', // Invalid input
                    'role_id' => 1, // Ensure a role with this ID exists in your test setup
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The password field is required.');
    }
}
