<?php

namespace Tests\GraphQL;


use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_product()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createProduct' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1,
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    // Missing product_name, price, category_id
                    'description' => 'Test Description',
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The product name field is required.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 'one hundred', // Invalid data type
                    'category_id' => 'one', // Invalid data type
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The price must be a number.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => '<script>alert(1)</script>',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        // This should succeed but the sanitized product_name should be saved
        $response->assertJson([
            'data' => [
                'createProduct' => [
                    'product_name' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-product', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-product:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Product created', ['user_id' => $this->user->id, 'product_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_measures_performance()
    {
        // Measure performance (this is a basic example, for real performance testing consider using a dedicated tool)
        $startTime = microtime(true);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->assertLessThan(1, $duration, 'Mutation took too long'); // Example threshold, adjust as needed
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_is_secure()
    {
        // Test for SQL injection (example, assuming input is sanitized)
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 100.0,
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => '1 OR 1=1',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_validation_errors()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateProduct($input: ProductInput!) {
                    createProduct(input: $input) {
                        id
                        product_name
                        description
                        price
                        category_id
                        image_url
                        created_at
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => '', // Invalid input
                    'category_id' => 1, // Ensure a category with this ID exists in your test setup
                    'image_url' => 'http://example.com/image.png',
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The price field is required.');
    }
}
