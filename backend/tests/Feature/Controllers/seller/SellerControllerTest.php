<?php

namespace Tests\Feature\Seller;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_view_dashboard()
    {
        $seller = User::factory()->create();
        $this->actingAs($seller, 'sanctum');

        Product::factory()->create(['seller_id' => $seller->id]);
        Order::factory()->create(['seller_id' => $seller->id]);
        Inventory::factory()->create(['seller_id' => $seller->id]);
        Shipping::factory()->create(['seller_id' => $seller->id]);
        Payment::factory()->create(['seller_id' => $seller->id]);

        $response = $this->getJson('/api/seller/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'products', 'orders', 'inventory', 'shipping', 'payments'
            ]);
    }
}
