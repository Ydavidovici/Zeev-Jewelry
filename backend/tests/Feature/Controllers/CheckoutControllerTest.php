<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_the_checkout_cart()
    {
        $response = $this->getJson(route('checkout.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $orderData = [
            'address' => '123 Main St',
            'city' => 'Testville',
            'postal_code' => '12345'
        ];

        session()->put('cart', [
            ['product' => Product::factory()->create(), 'quantity' => 1]
        ]);

        $response = $this->postJson(route('checkout.store'), $orderData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Order placed successfully.']);

        $this->assertDatabaseHas('orders', ['user_id' => auth()->id(), 'status' => 'Pending']);
    }

    /** @test */
    public function it_can_confirm_checkout_success()
    {
        $response = $this->getJson(route('checkout.success'));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Order completed successfully.']);
    }

    /** @test */
    public function it_can_confirm_checkout_failure()
    {
        $response = $this->getJson(route('checkout.failure'));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Order failed.']);
    }
}
