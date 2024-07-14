<?php

namespace Tests\GraphQL;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->inventory = Inventory::factory()->create();
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $this->user->assignRole('admin');
        $this->actingAs($this->user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_an_inventory_movement()
    {
        $response = $this->graphQL('
            mutation ($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    inventory {
                        id
                    }
                    movement_type
                    quantity
                    movement_date
                }
            }
        ', [
            'input' => [
                'inventory_id' => $this->inventory->id,
                'movement_type' => 'addition',
                'quantity' => 10,
                'movement_date' => '2024-01-01',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInventoryMovement' => [
                    'inventory' => [
                        'id' => (string)$this->inventory->id,
                    ],
                    'movement_type' => 'addition',
                    'quantity' => 10,
                    'movement_date' => '2024-01-01',
                ],
            ],
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'inventory_id' => $this->inventory->id,
            'movement_type' => 'addition',
            'quantity' => 10,
            'movement_date' => '2024-01-01',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_missing_inventory_id()
    {
        $response = $this->graphQL('
            mutation ($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    inventory {
                        id
                    }
                    movement_type
                    quantity
                    movement_date
                }
            }
        ', [
            'input' => [
                'movement_type' => 'addition',
                'quantity' => 10,
                'movement_date' => '2024-01-01',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Field "inventory_id" of required type "ID!" was not provided.',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_invalid_quantity()
    {
        $response = $this->graphQL('
        mutation ($input: InventoryMovementInput!) {
            createInventoryMovement(input: $input) {
                id
                inventory {
                    id
                }
                movement_type
                quantity
                movement_date
            }
        }
    ', [
            'input' => [
                'inventory_id' => $this->inventory->id,
                'movement_type' => 'addition',
                'quantity' => 'invalid',
                'movement_date' => '2024-01-01',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Int cannot represent non-integer value: "invalid"',
                ],
            ],
        ]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sanitizes_inputs()
    {
        $response = $this->graphQL('
            mutation ($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    inventory {
                        id
                    }
                    movement_type
                    quantity
                    movement_date
                }
            }
        ', [
            'input' => [
                'inventory_id' => $this->inventory->id,
                'movement_type' => '<script>alert("XSS")</script>addition',
                'quantity' => 10,
                'movement_date' => '2024-01-01',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInventoryMovement' => [
                    'inventory' => [
                        'id' => (string)$this->inventory->id,
                    ],
                    'movement_type' => 'addition',
                    'quantity' => 10,
                    'movement_date' => '2024-01-01',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_too_many_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->graphQL('
                mutation ($input: InventoryMovementInput!) {
                    createInventoryMovement(input: $input) {
                        id
                        inventory {
                            id
                        }
                        movement_type
                        quantity
                        movement_date
                    }
                }
            ', [
                'input' => [
                    'inventory_id' => $this->inventory->id,
                    'movement_type' => 'addition',
                    'quantity' => 10,
                    'movement_date' => '2024-01-01',
                ],
            ]);
        }

        $response = $this->graphQL('
            mutation ($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    inventory {
                        id
                    }
                    movement_type
                    quantity
                    movement_date
                }
            }
        ', [
            'input' => [
                'inventory_id' => $this->inventory->id,
                'movement_type' => 'addition',
                'quantity' => 10,
                'movement_date' => '2024-01-01',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Too many attempts. Please try again later.',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_requires_authorization()
    {
        Gate::define('create-inventory-movement', function ($user) {
            return false;
        });

        $response = $this->graphQL('
            mutation ($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    inventory {
                        id
                    }
                    movement_type
                    quantity
                    movement_date
                }
            }
        ', [
            'input' => [
                'inventory_id' => $this->inventory->id,
                'movement_type' => 'addition',
                'quantity' => 10,
                'movement_date' => '2024-01-01',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Unauthorized',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_logs_actions()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Inventory movement created', \Mockery::on(function ($data) {
                return isset($data['user_id']) && isset($data['inventory_movement_id']);
            }));

        $this->graphQL('
            mutation ($input: InventoryMovementInput!) {
                createInventoryMovement(input: $input) {
                    id
                    inventory {
                        id
                    }
                    movement_type
                    quantity
                    movement_date
                }
            }
        ', [
            'input' => [
                'inventory_id' => $this->inventory->id,
                'movement_type' => 'addition',
                'quantity' => 10,
                'movement_date' => '2024-01-01',
            ],
        ]);
    }
}
