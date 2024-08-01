<?php

namespace Tests\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use PHPUnit\Framework\Attributes\Test;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function database_seeder_runs_correctly()
    {
        $this->seed(DatabaseSeeder::class);

        // Add assertions to check if seeders have populated the database correctly.
        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseCount('orders', 2);
    }
}
