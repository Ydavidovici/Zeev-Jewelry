<?php

namespace Tests\Feature\Seller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Shipping;
use ConsoleTVs\Charts\Facades\Charts;

class ReportControllerTest extends TestCase
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
    public function seller_can_view_reports_index()
    {
        $response = $this->get(route('seller-page.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('seller-page.reports.index');
        $response->assertViewHasAll(['orderChart', 'productChart', 'paymentChart', 'shippingChart']);
    }
}
