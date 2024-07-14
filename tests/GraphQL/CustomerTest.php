<?php

namespace Tests\GraphQL;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $this->user->assignRole('admin');
        $this->actingAs($this->user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_customer()
    {
        $response = $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'user_id' => $this->user->id,
                'address' => '123 Main St',
                'phone_number' => '123-456-7890',
                'email' => 'test@example.com',
                'is_guest' => false,
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createCustomer' => [
                    'user' => [
                        'id' => (string) $this->user->id,
                    ],
                    'address' => '123 Main St',
                    'phone_number' => '123-456-7890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);

        $this->assertDatabaseHas('customers', [
            'user_id' => $this->user->id,
            'address' => '123 Main St',
            'phone_number' => '123-456-7890',
            'email' => 'test@example.com',
            'is_guest' => false,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_missing_user_id()
    {
        $response = $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'address' => '123 Main St',
                'phone_number' => '123-456-7890',
                'email' => 'test@example.com',
                'is_guest' => false,
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Field "user_id" of required type "ID!" was not provided.',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_invalid_email()
    {
        $response = $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'user_id' => $this->user->id,
                'address' => '123 Main St',
                'phone_number' => '123-456-7890',
                'email' => 'invalid-email',
                'is_guest' => false,
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'The email field must be a valid email address.',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sanitizes_inputs()
    {
        $response = $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'user_id' => $this->user->id,
                'address' => '<script>alert("XSS")</script>123 Main St',
                'phone_number' => '123-456-7890',
                'email' => 'test@example.com',
                'is_guest' => false,
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createCustomer' => [
                    'user' => [
                        'id' => (string) $this->user->id,
                    ],
                    'address' => '123 Main St',
                    'phone_number' => '123-456-7890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fails_with_too_many_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->graphQL('
                mutation ($input: CustomerInput!) {
                    createCustomer(input: $input) {
                        id
                        user {
                            id
                        }
                        address
                        phone_number
                        email
                        is_guest
                    }
                }
            ', [
                'input' => [
                    'user_id' => $this->user->id,
                    'address' => '123 Main St',
                    'phone_number' => '123-456-7890',
                    'email' => 'test@example.com',
                    'is_guest' => false,
                ],
            ]);
        }

        $response = $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'user_id' => $this->user->id,
                'address' => '123 Main St',
                'phone_number' => '123-456-7890',
                'email' => 'test@example.com',
                'is_guest' => false,
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Too many attempts. Please try again later.',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_requires_authorization()
    {
        Gate::define('create-customer', function ($user) {
            return false;
        });

        $response = $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'user_id' => $this->user->id,
                'address' => 'Unauthorized Address',
                'phone_number' => '123-456-7890',
                'email' => 'unauthorized@example.com',
                'is_guest' => false,
            ],
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Unauthorized',
                ],
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_logs_actions()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Customer created', \Mockery::on(function ($data) {
                return isset($data['user_id']) && isset($data['customer_id']);
            }));

        $this->graphQL('
            mutation ($input: CustomerInput!) {
                createCustomer(input: $input) {
                    id
                    user {
                        id
                    }
                    address
                    phone_number
                    email
                    is_guest
                }
            }
        ', [
            'input' => [
                'user_id' => $this->user->id,
                'address' => 'Log Address',
                'phone_number' => '123-456-7890',
                'email' => 'log@example.com',
                'is_guest' => false,
            ],
        ]);
    }
}
