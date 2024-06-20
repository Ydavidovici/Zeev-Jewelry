<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateOrderDetailMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_an_order_detail()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => 100.0,
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createOrderDetail' => [
                    'order_id' => 1,
                    'product_id' => 1,
                    'quantity' => 2,
                    'price' => 100.0,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    // Missing product_id, quantity, price
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The product id field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 'two', // Invalid data type
                    'price' => 'hundred', // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The quantity must be an integer.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => 100.0,
                    'additional_field' => '<script>alert(1)</script>',
                ],
            ],
        ]);

        // This should succeed but the sanitized additional_field should be saved
        $response->assertJson([
            'data' => [
                'createOrderDetail' => [
                    'additional_field' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-order-detail', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => 100.0,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-order-detail:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => 100.0,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Order detail created', ['user_id' => $this->user->id, 'order_detail_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => 100.0,
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
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => 100.0,
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
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 2,
                    'price' => '1 OR 1=1',
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
                mutation CreateOrderDetail($input: OrderDetailInput!) {
                    createOrderDetail(input: $input) {
                        id
                        order_id
                        product_id
                        quantity
                        price
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'order_id' => 1, // Ensure an order with this ID exists in your test setup
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => '', // Invalid input
                    'price' => 100.0,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The quantity field is required.');
    }
}
