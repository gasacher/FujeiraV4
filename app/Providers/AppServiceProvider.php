<?php

namespace App\Providers;

use App\Cart\CartSession;
use App\Catalog\V5Catalog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartSession::class);
        $this->app->singleton(V5Catalog::class);
    }

    public function boot(): void
    {
        View::composer('layouts.store', function ($view) {
            $cart = app(CartSession::class);
            $view->with('cartCount', $cart->count());
            $view->with('cartLineas', $cart->detailed());
            $view->with('cartTotal', $cart->total());
        });

        View::composer('v5.layout', function ($view) {
            $view->with('v5Resumen', app(V5Catalog::class)->resumen());
        });
    }
}
