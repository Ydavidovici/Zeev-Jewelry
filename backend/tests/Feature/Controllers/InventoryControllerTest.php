<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testInventoryIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Inventory::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/inventories');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testInventoryStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'product_id' => 1,
            'quantity' => 100,
            'location' => 'Warehouse 1',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/inventories', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testInventoryShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $inventory = Inventory::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/inventories/{$inventory->id}");

        $response->assertStatus(200)
            ->assertJson($inventory->toArray());
    }

    public function testInventoryUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $inventory = Inventory::factory()->create();

        $data = [
            'quantity' => 200,
            'location' => 'Warehouse 2',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/inventories/{$inventory->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testInventoryDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $inventory = Inventory::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/inventories/{$inventory->id}");

        $response->assertStatus(204);
    }
}
