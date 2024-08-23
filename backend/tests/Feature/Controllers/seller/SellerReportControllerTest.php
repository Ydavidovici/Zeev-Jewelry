<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SellerReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSellerReportGeneration()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Order::factory()->count(5)->create(['seller_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/seller/reports');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'sales',
                'customers',
                'inventory',
                'orders',
                'revenue',
                'marketing',
                'product_performance',
                'operations',
                'market',
            ]);
    }
}
