<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateCustomerMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_a_customer()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createCustomer' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    // Missing address, phone_number, email, is_guest
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The address field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => 123, // Invalid data type
                    'email' => 'test@example.com',
                    'is_guest' => 'not a boolean', // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The phone number must be a string.');
    }

    /** @test */
    public function it_fails_with_duplicate_email()
    {
        Customer::factory()->create(['email' => 'test@example.com']);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The email has already been taken.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '<script>alert(1)</script>',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        // This should succeed but the sanitized address should be saved
        $response->assertJson([
            'data' => [
                'createCustomer' => [
                    'address' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-customer', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-customer:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Customer created', ['user_id' => $this->user->id, 'customer_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
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
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Test St',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
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
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '1 OR 1=1',
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
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
                mutation CreateCustomer($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user_id
                        address
                        phone_number
                        email
                        is_guest
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '', // Invalid input
                    'phone_number' => '1234567890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The address field is required.');
    }
}
