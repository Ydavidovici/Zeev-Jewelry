<?php

namespace Tests\Feature\Seller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class SellerReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a seller user
        $this->sellerUser = User::factory()->create([
            'role' => 'seller',
        ]);

        // Create some products, orders, and customers for the seller
        $this->product = Product::factory()->create([
            'seller_id' => $this->sellerUser->id,
        ]);

        $this->order = Order::factory()->create([
            'seller_id' => $this->sellerUser->id,
            'product_id' => $this->product->id,
            'total' => 100,
        ]);

        $this->customer = Customer::factory()->create();
    }

    /** @test */
    public function seller_can_access_dashboard_and_generate_reports()
    {
        $response = $this->actingAs($this->sellerUser)->getJson('/seller/reports');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'sales' => [
                    'total_sales',
                    'sales_growth_rate',
                    'average_order_value',
                    'sales_by_product',
                    'sales_by_category',
                    'sales_by_channel',
                ],
                'customers' => [
                    'customer_lifetime_value',
                    'customer_acquisition_cost',
                    'repeat_purchase_rate',
                    'new_vs_returning_customers',
                    'customer_segmentation',
                ],
                'inventory' => [
                    'inventory_turnover_rate',
                    'stock_levels',
                    'sell_through_rate',
                    'aging_inventory',
                    'backorder_rate',
                ],
                'orders' => [
                    'order_fulfillment_time',
                    'order_cancellation_rate',
                    'return_rate',
                    'on_time_delivery_rate',
                ],
                'revenue' => [
                    'gross_margin',
                    'net_profit_margin',
                    'discount_usage',
                    'refund_rate',
                ],
                'marketing' => [
                    'conversion_rate',
                    'traffic_sources',
                    'cart_abandonment_rate',
                ],
                'product_performance' => [
                    'product_return_rate',
                    'product_profitability',
                    'top_selling_products',
                    'underperforming_products',
                ],
                'operations' => [
                    'shipping_costs',
                    'customer_support_metrics',
                    'fulfillment_cost',
                    'return_processing_time',
                ],
                'market' => [
                    'market_share',
                    'price_competitiveness',
                    'demand_forecasting',
                ],
            ]);
    }

    /** @test */
    public function seller_can_generate_sales_report()
    {
        $response = $this->actingAs($this->sellerUser)->getJson('/seller/reports', [
            'start_date' => now()->subMonth(),
            'end_date' => now(),
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'total_sales' => 100,
            ]);
    }

    /** @test */
    public function non_seller_cannot_access_seller_reports()
    {
        $nonSellerUser = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($nonSellerUser)->getJson('/seller/reports');

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_seller_reports()
    {
        $response = $this->getJson('/seller/reports');

        $response->assertStatus(401);
    }
}
