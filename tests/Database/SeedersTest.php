<?php

namespace Tests\Unit\Seeders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function database_seeder_runs_correctly()
    {
        $this->seed(DatabaseSeeder::class);

        // Add assertions to check if seeders have populated the database correctly.
        // For example:
        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseCount('orders', 2);
    }
}
