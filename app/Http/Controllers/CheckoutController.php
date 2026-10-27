<?php

namespace App\Http\Controllers;

use App\Cart\CartSession;
use App\Models\Order;
use App\Services\MercadoPagoClient;
use App\Services\MercadoPagoException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(CartSession $cart): View|RedirectResponse
    {
        if ($cart->detailed() === []) {
            return redirect()->route('carrito.show')->withErrors(['carrito' => 'El carrito está vacío.']);
        }

        return view('checkout', [
            'lineas' => $cart->detailed(),
            'total' => $cart->total(),
        ]);
    }

    public function store(Request $request, CartSession $cart, MercadoPagoClient $mercadoPago): RedirectResponse
    {
        $lineas = $cart->detailed();

        if ($lineas === []) {
            return redirect()->route('carrito.show')->withErrors(['carrito' => 'El carrito está vacío.']);
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'apellido' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:160'],
            'telefono' => ['required', 'string', 'max:40'],
            'direccion' => ['required', 'string', 'max:200'],
        ]);

        $items = [];

        foreach ($lineas as $linea) {
            $items[] = [
                'codigo' => $linea['product']->codigo,
                'nombre' => $linea['product']->nombre,
                'precio' => $linea['product']->precio,
                'cantidad' => $linea['cantidad'],
                'talle' => $linea['talle'],
                'marco' => $linea['marco'],
            ];
        }

        $order = Order::create([
            'numero' => 'TMP',
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'items' => $items,
            'total' => $cart->total(),
            'estado' => 'pendiente_pago',
        ]);

        $order->update(['numero' => 'FJ-'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT)]);

        try {
            $preference = $mercadoPago->createPreference($order->fresh());
        } catch (MercadoPagoException $exception) {
            $order->update(['estado' => 'error_mp']);

            return back()->withInput()->withErrors(['pago' => $exception->getMessage()]);
        }

        $order->update(['mp_preference_id' => $preference['id']]);
        $cart->clear();

        return redirect()->away($preference['init_point']);
    }

    public function pago(Request $request, string $estado, MercadoPagoClient $mercadoPago): View
    {
        $order = null;
        $reference = $request->string('external_reference')->toString();

        if ($reference !== '') {
            $order = Order::find($reference);
        }

        $paymentId = $request->string('payment_id')->toString() ?: $request->string('collection_id')->toString();

        if ($order && $paymentId !== '' && $paymentId !== 'null') {
            try {
                $order->aplicarPago($mercadoPago->payment($paymentId));
                $order->refresh();
            } catch (MercadoPagoException) {
                // The return page still renders. The webhook can confirm the payment later.
            }
        }

        return view('pago', [
            'estado' => $estado,
            'pedido' => $order,
        ]);
    }
}
