<?php

use App\Catalog\Catalog;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/catalogo', [StorefrontController::class, 'catalogo'])->name('catalogo');
Route::get('/producto/{codigo}', [StorefrontController::class, 'producto'])->name('producto.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::redirect('/index.html', '/', 301);
Route::redirect('/catalogo.html', '/catalogo', 301);
Route::redirect('/nosotros.html', '/nosotros', 301);
Route::redirect('/faqs.html', '/faqs', 301);
Route::redirect('/contacto.html', '/contacto', 301);
Route::redirect('/pago-exitoso.html', '/pago/exitoso', 301);
Route::redirect('/pago-pendiente.html', '/pago/pendiente', 301);
Route::redirect('/pago-fallido.html', '/pago/fallido', 301);

Route::get('/producto.html', function (Request $request) {
    $codigo = $request->query('id', $request->query('codigo'));

    if (is_string($codigo) && preg_match('/^[A-Za-z0-9-]+$/', $codigo)) {
        $producto = app(Catalog::class)->find($codigo);

        if ($producto) {
            return redirect()->route('producto.show', ['codigo' => $producto->codigo], 301);
        }
    }

    return redirect()->route('catalogo', [], 301);
});

Route::any('/api/crear-preferencia.php', function () {
    return response('Esta dirección ya no existe.', 410);
});
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
