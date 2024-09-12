<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan; // Import Artisan

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with roles and permissions
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }
}
