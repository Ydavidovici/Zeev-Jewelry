<?php

namespace Tests\Feature\Controllers\seller;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class SellerReportControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testSellerReportGeneration()
    {
        $user = User::factory()->create();
        $user->assignRole('seller');  // Assign 'seller' role
        $token = JWTAuth::fromUser($user);

        // Create 5 orders for the seller
        Order::factory()->count(5)->create(['seller_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/Seller/reports');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'sales' => [
                    'total_sales',
                    'order_count',
                ],
                // You can add other sections like customers, inventory if needed
            ]);

        // Additional assertions to check the sales data
        $responseData = $response->json();
        $this->assertEquals(5, $responseData['sales']['order_count']); // Ensure we have 5 orders
        // Assuming you know the expected total sales, you can add:
        // $this->assertEquals(expected_total_sales, $responseData['sales']['total_sales']);
    }
}
