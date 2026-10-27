<?php

namespace App\Providers;

use App\Cart\CartSession;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartSession::class);
    }

    public function boot(): void
    {
        View::composer('layouts.store', function ($view) {
            $view->with('cartCount', app(CartSession::class)->count());
        });
    }
}
