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

        // Verify that the seeder ran without errors.
        $this->assertTrue(true);
    }
}
