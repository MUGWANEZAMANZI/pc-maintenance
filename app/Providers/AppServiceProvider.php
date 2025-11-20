<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

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
        // Force HTTPS in production behind Render's proxy to avoid mixed content.
        if (app()->environment('production')) {
            // Trust X-Forwarded-Proto header so Laravel detects https correctly.
            URL::forceScheme('https');
        }
    }
}
