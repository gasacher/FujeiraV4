<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoreCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_lists_a_catalog_product(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Los diablos rojos')
            ->assertSee('hero-slide__mockup', false)
            ->assertSee('id="destacados"', false)
            ->assertSee('Cuadros destacados')
            ->assertDontSee('DEC-CUA-05');
    }

    public function test_catalog_filters_retro_series(): void
    {
        $this->get('/catalogo?categoria=Fujeira%20Retro%20Series')
            ->assertOk()
            ->assertSee('catalog-page', false)
            ->assertSee('Los diablos rojos')
            ->assertDontSee('Román');
    }

    public function test_out_of_stock_size_cannot_be_added(): void
    {
        $this->post('/carrito', [
            'codigo' => 'FU-01',
            'talle' => 'S',
            'cantidad' => 1,
        ])->assertRedirect()->assertSessionHasErrors('carrito');

        $this->get('/carrito')->assertSee('Tu carrito está vacío');
    }

    public function test_checkout_charges_the_catalog_price_and_redirects_to_mercado_pago(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-placeholder-token']);

        Http::fake([
            'https://api.mercadopago.com/checkout/preferences' => Http::response([
                'id' => 'pref-1',
                'sandbox_init_point' => 'https://sandbox.mercadopago.com.ar/checkout/v1/redirect?pref_id=pref-1',
            ], 201),
        ]);

        $this->post('/carrito', [
            'codigo' => 'FU-01',
            'talle' => 'L',
            'cantidad' => 2,
        ])->assertRedirect('/carrito');

        $response = $this->post('/checkout', [
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'email' => 'ana@example.com',
            'telefono' => '1112345678',
            'direccion' => 'Calle 123, CABA',
        ]);

        $response->assertRedirect('https://sandbox.mercadopago.com.ar/checkout/v1/redirect?pref_id=pref-1');

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame(80000, $order->total);
        $this->assertSame('pref-1', $order->mp_preference_id);
        $this->assertSame('pendiente_pago', $order->estado);

        Http::assertSent(function ($request) {
            $item = $request->data()['items'][0];

            return $request->hasHeader('Authorization', 'Bearer TEST-placeholder-token')
                && (float) $item['unit_price'] === 40000.0
                && $item['quantity'] === 2
                && $item['currency_id'] === 'ARS';
        });
    }

    public function test_checkout_without_a_token_does_not_call_mercado_pago(): void
    {
        Http::fake();

        $this->post('/carrito', [
            'codigo' => 'DEC-CUA-01',
            'marco' => 'Negro',
            'cantidad' => 1,
        ]);

        $this->post('/checkout', [
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'email' => 'ana@example.com',
            'telefono' => '1112345678',
            'direccion' => 'Calle 123',
        ])->assertRedirect()->assertSessionHasErrors('pago');

        Http::assertNothingSent();
        $this->assertSame('error_mp', Order::first()->estado);
    }

    public function test_webhook_approves_the_order_when_the_amount_matches(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-placeholder-token']);

        $order = Order::create([
            'numero' => 'TMP',
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'email' => 'ana@example.com',
            'telefono' => '1112345678',
            'direccion' => 'Calle 123',
            'items' => [],
            'total' => 40000,
            'estado' => 'pendiente_pago',
        ]);

        Http::fake([
            'https://api.mercadopago.com/v1/payments/99' => Http::response([
                'id' => 99,
                'status' => 'approved',
                'currency_id' => 'ARS',
                'transaction_amount' => 40000,
                'external_reference' => (string) $order->id,
            ]),
        ]);

        $this->postJson('/webhooks/mercadopago', [
            'type' => 'payment',
            'data' => ['id' => '99'],
        ])->assertNoContent();

        $this->assertSame('aprobado', $order->fresh()->estado);
        $this->assertSame('99', $order->fresh()->mp_payment_id);
    }
}
