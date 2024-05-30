<?php

// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (env('DB_CONNECTION') === 'sqlite') {
            \Illuminate\Support\Facades\DB::connection()->getPdo()->exec("PRAGMA foreign_keys=ON");
        }
    }

    public function register()
    {
        //
    }
}
