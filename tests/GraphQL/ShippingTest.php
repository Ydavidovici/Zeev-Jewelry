<?php

namespace Tests\GraphQL;


use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ShippingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_shipping_record()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createShipping' => [
                    'order_id' => 1,
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    // Missing shipping_type, shipping_cost, shipping_status, shipping_address
                    'tracking_number' => 'TRACK123',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The shipping type field is required.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 12345, // Invalid data type
                    'shipping_cost' => 'ten', // Invalid data type
                    'shipping_status' => true, // Invalid data type
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => 12345, // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The shipping type must be a string.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => '<script>alert(1)</script>',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);

        // This should succeed but the sanitized shipping_type should be saved
        $response->assertJson([
            'data' => [
                'createShipping' => [
                    'shipping_type' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-shipping', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-shipping:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Shipping record created', ['user_id' => $this->user->id, 'shipping_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_measures_performance()
    {
        // Measure performance (this is a basic example, for real performance testing consider using a dedicated tool)
        $startTime = microtime(true);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->assertLessThan(1, $duration, 'Mutation took too long'); // Example threshold, adjust as needed
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_is_secure()
    {
        // Test for SQL injection (example, assuming input is sanitized)
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => '1 OR 1=1',
                    'shipping_address' => '123 Test St, Test City, TS 12345',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_validation_errors()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateShipping($input: ShippingInput!) {
                    createShipping(input: $input) {
                        id
                        order_id
                        shipping_type
                        shipping_cost
                        shipping_status
                        tracking_number
                        shipping_address
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 10.0,
                    'shipping_status' => 'Pending',
                    'tracking_number' => 'TRACK123',
                    'shipping_address' => '', // Invalid input
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The shipping address field is required.');
    }
}
