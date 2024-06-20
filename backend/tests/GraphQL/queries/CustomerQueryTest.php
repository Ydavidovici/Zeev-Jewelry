<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerQueryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user and customer
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
    }

    /** @test */
    public function it_returns_customer_data()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => $this->customer->id,
            ],
        ]);

        $response->assertJson([
            'data' => [
                'customer' => [
                    'id' => (string) $this->customer->id,
                    'name' => $this->customer->name,
                    'email' => $this->customer->email,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_invalid_customer_id()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => 999999, // Non-existent ID
            ],
        ]);

        $response->assertGraphQLErrorMessage('Customer not found');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => '<script>alert(1)</script>',
            ],
        ]);

        // This should fail due to validation, confirming that sanitation occurred
        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('view-customer', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => $this->customer->id,
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('customer-query:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => $this->customer->id,
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Customer queried', ['user_id' => $this->user->id, 'customer_id' => $this->customer->id]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => $this->customer->id,
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
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => $this->customer->id,
            ],
        ]);

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->assertLessThan(1, $duration, 'Query took too long'); // Example threshold, adjust as needed
    }

    /** @test */
    public function it_is_secure()
    {
        // Test for SQL injection (example, assuming ID is sanitized)
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => '1 OR 1=1',
            ],
        ]);

        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    /** @test */
    public function it_handles_errors_gracefully()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetCustomer($id: ID!) {
                    customer(id: $id) {
                        id
                        name
                        email
                    }
                }
            ',
            'variables' => [
                'id' => 999999, // Non-existent ID
            ],
        ]);

        $response->assertGraphQLErrorMessage('Customer not found');
    }
}
