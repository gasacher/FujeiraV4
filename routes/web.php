<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/catalogo', [StorefrontController::class, 'catalogo'])->name('catalogo');
Route::get('/producto/{codigo}', [StorefrontController::class, 'producto'])->name('producto.show');
Route::get('/nosotros', [StorefrontController::class, 'nosotros'])->name('nosotros');
Route::get('/faqs', [StorefrontController::class, 'faqs'])->name('faqs');
Route::get('/contacto', [StorefrontController::class, 'contacto'])->name('contacto');

Route::get('/carrito', [CartController::class, 'show'])->name('carrito.show');
Route::post('/carrito', [CartController::class, 'add'])->name('carrito.add');
Route::patch('/carrito/{index}', [CartController::class, 'update'])->name('carrito.update');
Route::delete('/carrito/{index}', [CartController::class, 'remove'])->name('carrito.remove');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pago/{estado}', [CheckoutController::class, 'pago'])->name('pago.show');

Route::post('/webhooks/mercadopago', MercadoPagoWebhookController::class)->name('webhooks.mercadopago');
