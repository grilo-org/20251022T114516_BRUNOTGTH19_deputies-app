<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            \App\Repositories\DeputyRepository::class,
            function ($app) {
                return new \App\Repositories\DeputyRepository();
            }
        );
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}