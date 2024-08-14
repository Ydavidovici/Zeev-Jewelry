<?php

namespace Tests\Feature\Seller;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Shipping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_view_reports()
    {
        $seller = User::factory()->create();
        $this->actingAs($seller, 'sanctum');

        Order::factory()->count(2)->create(['seller_id' => $seller->id]);
        Product::factory()->count(3)->create(['seller_id' => $seller->id]);
        Payment::factory()->count(4)->create(['seller_id' => $seller->id]);
        Shipping::factory()->count(1)->create(['seller_id' => $seller->id]);

        $response = $this->getJson('/api/seller/reports');

        $response->assertStatus(200)
            ->assertJson([
                'order_count' => 2,
                'product_count' => 3,
                'payment_count' => 4,
                'shipping_count' => 1,
            ]);
    }
}
