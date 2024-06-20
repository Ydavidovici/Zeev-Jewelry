<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreatePaymentMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_a_payment()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'Pending',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createPayment' => [
                    'order_id' => 1,
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'Pending',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    // Missing payment_type, payment_status
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The payment type field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => 12345, // Invalid data type
                    'payment_status' => true, // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The payment type must be a string.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => '<script>alert(1)</script>',
                    'payment_status' => 'Pending',
                ],
            ],
        ]);

        // This should succeed but the sanitized payment_type should be saved
        $response->assertJson([
            'data' => [
                'createPayment' => [
                    'payment_type' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-payment', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'Pending',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-payment:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'Pending',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Payment created', ['user_id' => $this->user->id, 'payment_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'Pending',
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
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => 'Credit Card',
                    'payment_status' => 'Pending',
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
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => '1 OR 1=1',
                    'payment_status' => 'Pending',
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
                mutation CreatePayment($input: PaymentInput!) {
                    createPayment(input: $input) {
                        id
                        order_id
                        payment_type
                        payment_status
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'payment_type' => '', // Invalid input
                    'payment_status' => 'Pending',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The payment type field is required.');
    }
}
