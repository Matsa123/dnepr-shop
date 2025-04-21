<?php

namespace App\Providers;
use Blade;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Регистрируем директиву @active
        Blade::directive('active', function ($expression) {
            return "<?php echo request()->routeIs($expression) ? 'active' : ''; ?>";
        });
    }
}
