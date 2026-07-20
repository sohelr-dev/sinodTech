<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\SaleCompleted;
use App\Listeners\SaleCompletedListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\Eloquent\ProductRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\SaleRepositoryInterface::class,
            \App\Repositories\Eloquent\SaleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Event → Listener mappings
        Event::listen(
            SaleCompleted::class,
            SaleCompletedListener::class
        );
    }
}
