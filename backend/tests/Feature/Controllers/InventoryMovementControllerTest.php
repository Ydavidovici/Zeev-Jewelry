<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\InventoryMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class InventoryMovementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testInventoryMovementIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        InventoryMovement::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/inventory_movements');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testInventoryMovementStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'inventory_id' => 1,
            'quantity' => 50,
            'movement_type' => 'Restock',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/inventory_movements', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testInventoryMovementShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/inventory_movements/{$inventoryMovement->id}");

        $response->assertStatus(200)
            ->assertJson($inventoryMovement->toArray());
    }

    public function testInventoryMovementUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $inventoryMovement = InventoryMovement::factory()->create();

        $data = [
            'quantity' => 100,
            'movement_type' => 'Adjustment',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/inventory_movements/{$inventoryMovement->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testInventoryMovementDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/inventory_movements/{$inventoryMovement->id}");

        $response->assertStatus(204);
    }
}
