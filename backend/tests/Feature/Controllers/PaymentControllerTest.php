<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Stripe\StripeClient;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $stripeClientMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Set dummy Stripe secret key
        config(['services.stripe.secret' => 'dummy_key']);

        // Seed roles if necessary
        if (!\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }

        // Create an admin user and authenticate
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');
        $this->actingAs($adminUser, 'api');

        // Initialize the StripeClient mock and bind it
        $this->stripeClientMock = Mockery::mock(StripeClient::class);
        $this->app->instance(StripeClient::class, $this->stripeClientMock);
    }

    #[Test]
    public function it_can_view_all_payments()
    {
        $order = Order::factory()->create();
        Payment::factory()->create(['order_id' => $order->id]);

        $response = $this->getJson(route('payments.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'order_id', 'payment_intent_id', 'payment_type', 'amount']]);
    }

    #[Test]
    public function it_can_create_a_payment()
    {
        $user = auth()->user();

        // Create an order with a seller_id
        $order = Order::factory()->create([
            'seller_id' => $user->id, // Assuming seller_id is the user ID
        ]);

        $paymentData = [
            'order_id' => $order->id,
            'amount' => 100.00,
        ];

        // Set expectations on the StripeClient mock
        $this->stripeClientMock->paymentIntents = Mockery::mock();
        $this->stripeClientMock->paymentIntents->shouldReceive('create')
            ->once()
            ->with([
                'amount' => 10000, // amount in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ])
            ->andReturn((object)[
                'id' => 'pi_test_123',
                'client_secret' => 'test_client_secret',
            ]);

        $response = $this->postJson(route('payments.store'), $paymentData);

        $response->assertStatus(200)
            ->assertJsonStructure(['clientSecret']);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'amount' => 100.00,
            'payment_type' => 'stripe',
            'payment_status' => 'pending',
            'payment_intent_id' => 'pi_test_123',
            'seller_id' => $order->seller_id, // Assert seller_id is stored
        ]);
    }



    #[Test]
    public function it_can_show_a_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->getJson(route('payments.show', $payment->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $payment->id]);
    }

    #[Test]
    public function it_can_update_a_payment()
    {
        $payment = Payment::factory()->create(['payment_status' => 'pending']);

        $response = $this->putJson(route('payments.update', $payment->id), [
            'payment_status' => 'completed',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['payment_status' => 'completed']);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'completed',
        ]);
    }

    #[Test]
    public function it_can_delete_a_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->deleteJson(route('payments.destroy', $payment->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
