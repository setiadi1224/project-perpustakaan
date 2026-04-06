<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Pagination\Paginator;
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
        // ❌ JANGAN pakai bootstrap kalau mau custom CSS
        // Paginator::useBootstrap();

        // Biarkan default (tailwind / custom override)
    }
}