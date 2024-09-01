<?php

namespace Tests\Feature\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_payments()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('payments.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'order_id', 'payment_intent_id', 'payment_type', 'amount']]);
    }

    /** @test */
    public function it_can_create_a_payment()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $paymentData = [
            'order_id' => 1,
            'amount' => 100.00,
        ];

        $response = $this->postJson(route('payments.store'), $paymentData);

        $response->assertStatus(200)
            ->assertJsonStructure(['clientSecret']);

        $this->assertDatabaseHas('payments', ['order_id' => 1, 'amount' => 100.00]);
    }

    /** @test */
    public function it_can_show_a_payment()
    {
        Gate::define('view', function ($user, $payment) {
            return true;
        });

        $payment = Payment::factory()->create();

        $response = $this->getJson(route('payments.show', $payment->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $payment->id]);
    }

    /** @test */
    public function it_can_update_a_payment()
    {
        Gate::define('update', function ($user, $payment) {
            return true;
        });

        $payment = Payment::factory()->create();

        $response = $this->putJson(route('payments.update', $payment->id), ['payment_status' => 'completed']);

        $response->assertStatus(200)
            ->assertJsonFragment(['payment_status' => 'completed']);

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'payment_status' => 'completed']);
    }

    /** @test */
    public function it_can_delete_a_payment()
    {
        Gate::define('delete', function ($user, $payment) {
            return true;
        });

        $payment = Payment::factory()->create();

        $response = $this->deleteJson(route('payments.destroy', $payment->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }
}
