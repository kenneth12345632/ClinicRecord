<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

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
    public function boot(): void
    {
        // 1. If you are using Bootstrap for pagination (optional)
        Paginator::useTailwind();

        // 2. Force HTTPS if in production (optional but good practice)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        /**
         * NOTE: In Laravel 11/12, if your login is still not redirecting,
         * you should check your AuthController or bootstrap/app.php.
         * * However, adding this logic here ensures your app knows 
         * the 'dashboard' is the intended home.
         */
    }
}