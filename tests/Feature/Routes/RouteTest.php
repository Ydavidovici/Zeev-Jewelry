<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_home_route()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Welcome');
    }

    /** @test */
    public function test_login_route()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /** @test */
    public function test_register_route()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    /** @test */
    public function test_password_reset_route()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertSee('Reset Password');
    }

    /** @test */
    public function test_cart_route()
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('Your Cart');
    }

    /** @test */
    public function test_product_index_route()
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Products');
    }

    // Authenticated Routes
    /** @test */
    public function test_authenticated_routes()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Categories');

        $response = $this->get(route('customers.index'));
        $response->assertStatus(200);
        $response->assertSee('Customers');

        $response = $this->get(route('inventories.index'));
        $response->assertStatus(200);
        $response->assertSee('Inventories');

        $response = $this->get(route('inventory-movements.index'));
        $response->assertStatus(200);
        $response->assertSee('Inventory Movements');

        $response = $this->get(route('order-details.index'));
        $response->assertStatus(200);
        $response->assertSee('Order Details');

        $response = $this->get(route('payments.index'));
        $response->assertStatus(200);
        $response->assertSee('Payments');

        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Products');

        $response = $this->get(route('reviews.index'));
        $response->assertStatus(200);
        $response->assertSee('Reviews');

        $response = $this->get(route('roles.index'));
        $response->assertStatus(200);
        $response->assertSee('Roles');

        $response = $this->get(route('shipping.index'));
        $response->assertStatus(200);
        $response->assertSee('Shipping');

        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('Users');

        $response = $this->get(route('orders.index'));
        $response->assertStatus(200);
        $response->assertSee('Orders');
    }

    // Admin Routes
    /** @test */
    public function test_admin_routes()
    {
        $admin = User::factory()->create(['role_id' => 1]); // Assuming role_id 1 is admin

        $this->actingAs($admin);

        $response = $this->get(route('admin-page.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');

        $response = $this->get(route('admin-page.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Admin Users');

        $response = $this->get(route('admin-page.roles.index'));
        $response->assertStatus(200);
        $response->assertSee('Admin Roles');

        $response = $this->get(route('admin-page.permissions.index'));
        $response->assertStatus(200);
        $response->assertSee('Admin Permissions');
    }

    // Seller Routes
    /** @test */
    public function test_seller_routes()
    {
        $seller = User::factory()->create(['role_id' => 2]); // Assuming role_id 2 is seller

        $this->actingAs($seller);

        $response = $this->get(route('seller-page.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Seller Dashboard');

        $response = $this->get(route('seller-page.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Seller Products');

        $response = $this->get(route('seller-page.orders.index'));
        $response->assertStatus(200);
        $response->assertSee('Seller Orders');

        $response = $this->get(route('seller-page.inventory.index'));
        $response->assertStatus(200);
        $response->assertSee('Seller Inventory');

        $response = $this->get(route('seller-page.shipping.index'));
        $response->assertStatus(200);
        $response->assertSee('Seller Shipping');

        $response = $this->get(route('seller-page.payments.index'));
        $response->assertStatus(200);
        $response->assertSee('Seller Payments');

        $response = $this->get(route('seller-page.reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Seller Reports');
    }

    // Checkout Routes
    /** @test */
    public function test_checkout_routes()
    {
        $response = $this->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertSee('Checkout');

        $response = $this->post(route('checkout.store'), [
            'cart_id' => 1,
            'payment_method' => 'credit_card',
        ]);
        $response->assertStatus(201);

        $response = $this->get(route('checkout.success'));
        $response->assertStatus(200);
        $response->assertSee('Checkout Success');

        $response = $this->get(route('checkout.failure'));
        $response->assertStatus(200);
        $response->assertSee('Checkout Failure');
    }

    // Stripe Webhook Route
    /** @test */
    public function test_stripe_webhook_route()
    {
        $response = $this->post(route('stripe.webhook'), [
            // Simulate Stripe webhook payload here
        ]);

        $response->assertStatus(200);
    }
}
