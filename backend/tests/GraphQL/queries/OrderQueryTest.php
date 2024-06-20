<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderQueryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user and order
        $this->user = User::factory()->create();
        $this->order = Order::factory()->create();
    }

    /** @test */
    public function it_returns_order_data()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => $this->order->id,
            ],
        ]);

        $response->assertJson([
            'data' => [
                'order' => [
                    'id' => (string) $this->order->id,
                    'total' => $this->order->total,
                    'status' => $this->order->status,
                    'created_at' => $this->order->created_at->toDateTimeString(),
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_invalid_order_id()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => 999999, // Non-existent ID
            ],
        ]);

        $response->assertGraphQLErrorMessage('Order not found');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
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
        Gate::shouldReceive('denies')->with('view-order', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => $this->order->id,
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('order-query:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => $this->order->id,
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Order queried', ['user_id' => $this->user->id, 'order_id' => $this->order->id]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => $this->order->id,
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
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => $this->order->id,
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
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
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
                query GetOrder($id: ID!) {
                    order(id: $id) {
                        id
                        total
                        status
                        created_at
                    }
                }
            ',
            'variables' => [
                'id' => 999999, // Non-existent ID
            ],
        ]);

        $response->assertGraphQLErrorMessage('Order not found');
    }
}
