<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateInventoryMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_an_inventory_record()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => 'Warehouse 1',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInventory' => [
                    'product_id' => 1,
                    'quantity' => 10,
                    'location' => 'Warehouse 1',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    // Missing quantity, location
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The quantity field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 'ten', // Invalid data type
                    'location' => 123, // Invalid data type
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
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => '<script>alert(1)</script>',
                ],
            ],
        ]);

        // This should succeed but the sanitized location should be saved
        $response->assertJson([
            'data' => [
                'createInventory' => [
                    'location' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-inventory', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => 'Warehouse 1',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-inventory:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => 'Warehouse 1',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Inventory created', ['user_id' => $this->user->id, 'inventory_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => 'Warehouse 1',
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
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => 'Warehouse 1',
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
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => 10,
                    'location' => '1 OR 1=1',
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
                mutation CreateInventory($input: InventoryInput!) {
                    createInventory(input: $input) {
                        id
                        product_id
                        quantity
                        location
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'quantity' => '', // Invalid input
                    'location' => 'Warehouse 1',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The quantity field is required.');
    }
}
