<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\InventoryMovement;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateInventoryMovementMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_an_inventory_movement()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => 'add',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInventoryMovement' => [
                    'inventory_id' => 1,
                    'type' => 'add',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    // Missing type, quantity_change, movement_date
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The type field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => 'add',
                    'quantity_change' => 'ten', // Invalid data type
                    'movement_date' => 'invalid-date', // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The quantity change must be an integer.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => '<script>alert(1)</script>',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
                ],
            ],
        ]);

        // This should succeed but the sanitized type should be saved
        $response->assertJson([
            'data' => [
                'createInventoryMovement' => [
                    'type' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-inventory-movement', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => 'add',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-inventory-movement:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => 'add',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Inventory movement created', ['user_id' => $this->user->id, 'inventory_movement_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => 'add',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
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
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => 'add',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
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
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => '1 OR 1=1',
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
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
                mutation CreateInventoryMovement($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory_id
                        type
                        quantity_change
                        movement_date
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'inventory_id' => 1, // Ensure an inventory with this ID exists in your test setup
                    'type' => '', // Invalid input
                    'quantity_change' => 10,
                    'movement_date' => '2023-01-01',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The type field is required.');
    }
}
