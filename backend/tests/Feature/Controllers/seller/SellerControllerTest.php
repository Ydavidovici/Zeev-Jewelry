<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSellerDashboard()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Product::factory()->create(['seller_id' => $user->id]);
        Order::factory()->create(['seller_id' => $user->id]);
        Inventory::factory()->create(['seller_id' => $user->id]);
        Shipping::factory()->create(['seller_id' => $user->id]);
        Payment::factory()->create(['seller_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/seller/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'products',
                'orders',
                'inventory',
                'shipping',
                'payments'
            ]);
    }
}
