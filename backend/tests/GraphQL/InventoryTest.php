<?php

namespace Tests\GraphQL;

use App\Models\Product;
use Tests\TestCase;

class CreateInventoryMutationTest extends TestCase
{
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a product to be used in the tests
        $this->product = Product::factory()->create();
    }

    /** @test */
    public function it_creates_an_inventory_record()
    {
        $response = $this->graphql('
            mutation ($input: InventoryInput!) {
                createInventory(input: $input) {
                    id
                    product {
                        id
                    }
                    quantity
                    location
                }
            }
        ', [
            'input' => [
                'product_id' => $this->product->id,
                'quantity' => 10,
                'location' => 'Warehouse A',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInventory' => [
                    'product' => [
                        'id' => (string) $this->product->id,
                    ],
                    'quantity' => 10,
                    'location' => 'Warehouse A',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_product_id()
    {
        $response = $this->graphql('
            mutation ($input: InventoryInput!) {
                createInventory(input: $input) {
                    id
                    product {
                        id
                    }
                    quantity
                    location
                }
            }
        ', [
            'input' => [
                'quantity' => 10,
                'location' => 'Warehouse A',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'The product id field is required.',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_invalid_quantity()
    {
        $response = $this->graphql('
            mutation ($input: InventoryInput!) {
                createInventory(input: $input) {
                    id
                    product {
                        id
                    }
                    quantity
                    location
                }
            }
        ', [
            'input' => [
                'product_id' => $this->product->id,
                'quantity' => 'invalid',
                'location' => 'Warehouse A',
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Int cannot represent non-integer value: "invalid"',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        $response = $this->graphql('
            mutation ($input: InventoryInput!) {
                createInventory(input: $input) {
                    id
                    product {
                        id
                    }
                    quantity
                    location
                }
            }
        ', [
            'input' => [
                'product_id' => '<script>alert(1)</script>' . $this->product->id,
                'quantity' => 10,
                'location' => '<script>alert(1)</script>Warehouse A',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInventory' => [
                    'product' => [
                        'id' => (string) $this->product->id,
                    ],
                    'quantity' => 10,
                    'location' => 'Warehouse A',
                ],
            ],
        ]);
    }
}
