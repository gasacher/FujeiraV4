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
            $cart = app(CartSession::class);
            $view->with('cartCount', $cart->count());
            $view->with('cartLineas', $cart->detailed());
            $view->with('cartTotal', $cart->total());
        });
    }
}
