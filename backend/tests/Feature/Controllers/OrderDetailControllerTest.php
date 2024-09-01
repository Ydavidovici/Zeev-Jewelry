<?php

namespace Tests\Feature\Controllers;

use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class OrderDetailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_order_details()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('order-details.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'order_id', 'product_id', 'quantity', 'price']]);
    }

    /** @test */
    public function it_can_create_an_order_detail()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $orderDetailData = [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 50.00,
        ];

        $response = $this->postJson(route('order-details.store'), $orderDetailData);

        $response->assertStatus(201)
            ->assertJsonFragment(['quantity' => 2]);

        $this->assertDatabaseHas('order_details', ['order_id' => 1, 'quantity' => 2]);
    }

    /** @test */
    public function it_can_show_an_order_detail()
    {
        Gate::define('view', function ($user, $orderDetail) {
            return true;
        });

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->getJson(route('order-details.show', $orderDetail->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $orderDetail->id]);
    }

    /** @test */
    public function it_can_update_an_order_detail()
    {
        Gate::define('update', function ($user, $orderDetail) {
            return true;
        });

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->putJson(route('order-details.update', $orderDetail->id), ['quantity' => 5]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 5]);

        $this->assertDatabaseHas('order_details', ['id' => $orderDetail->id, 'quantity' => 5]);
    }

    /** @test */
    public function it_can_delete_an_order_detail()
    {
        Gate::define('delete', function ($user, $orderDetail) {
            return true;
        });

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->deleteJson(route('order-details.destroy', $orderDetail->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('order_details', ['id' => $orderDetail->id]);
    }
}
