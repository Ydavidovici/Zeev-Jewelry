<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateOrderMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_an_order()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => 'Pending',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createOrder' => [
                    'customer_id' => 1,
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => 'Pending',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    // Missing total_amount, is_guest, status
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The total amount field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 'one hundred', // Invalid data type
                    'is_guest' => 'yes', // Invalid data type
                    'status' => 123, // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The total amount must be a number.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => '<script>alert(1)</script>',
                ],
            ],
        ]);

        // This should succeed but the sanitized status should be saved
        $response->assertJson([
            'data' => [
                'createOrder' => [
                    'status' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-order', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => 'Pending',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-order:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => 'Pending',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Order created', ['user_id' => $this->user->id, 'order_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => 'Pending',
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
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => 'Pending',
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
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => 150.0,
                    'is_guest' => false,
                    'status' => '1 OR 1=1',
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
                mutation CreateOrder($input: OrderInput!) {
                    createOrder(input: $input) {
                        id
                        customer_id
                        total_amount
                        is_guest
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'total_amount' => '', // Invalid input
                    'is_guest' => false,
                    'status' => 'Pending',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The total amount field is required.');
    }
}
