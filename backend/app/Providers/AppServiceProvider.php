<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Controllers\Admin\SettingsController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Alias the Spatie Role model
       // $this->app->alias(SpatieRole::class, 'role');
       // $this->app->make(SettingsController::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
