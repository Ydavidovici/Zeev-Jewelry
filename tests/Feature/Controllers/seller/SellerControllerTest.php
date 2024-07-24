<?php

namespace Tests\Feature\Seller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a seller user and set them as the current authenticated user
        $this->seller = User::factory()->create(['role_id' => 2]); // Assuming role_id 2 is seller
        $this->actingAs($this->seller);
    }

    /** @test */
    public function seller_can_view_dashboard()
    {
        // Create sample data for the authenticated seller
        $products = Product::factory()->count(5)->create(['seller_id' => $this->seller->id]);
        $orders = Order::factory()->count(5)->create(['seller_id' => $this->seller->id]);
        $inventory = Inventory::factory()->count(5)->create(['seller_id' => $this->seller->id]);
        $shipping = Shipping::factory()->count(5)->create(['seller_id' => $this->seller->id]);
        $payments = Payment::factory()->count(5)->create(['seller_id' => $this->seller->id]);

        $response = $this->get(route('seller-page.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('seller-page.dashboard');
        $response->assertViewHas('products', function ($viewProducts) use ($products) {
            return $viewProducts->count() === $products->count();
        });
        $response->assertViewHas('orders', function ($viewOrders) use ($orders) {
            return $viewOrders->count() === $orders->count();
        });
        $response->assertViewHas('inventory', function ($viewInventory) use ($inventory) {
            return $viewInventory->count() === $inventory->count();
        });
        $response->assertViewHas('shipping', function ($viewShipping) use ($shipping) {
            return $viewShipping->count() === $shipping->count();
        });
        $response->assertViewHas('payments', function ($viewPayments) use ($payments) {
            return $viewPayments->count() === $payments->count();
        });
    }
}
