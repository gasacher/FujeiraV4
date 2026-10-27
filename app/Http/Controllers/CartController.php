<?php

namespace App\Http\Controllers;

use App\Cart\CartSession;
use App\Catalog\Catalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(CartSession $cart): View
    {
        return view('carrito', [
            'lineas' => $cart->detailed(),
            'total' => $cart->total(),
        ]);
    }

    public function add(Request $request, CartSession $cart, Catalog $catalog): RedirectResponse
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:40'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:10'],
            'talle' => ['nullable', 'string', 'max:10'],
            'marco' => ['nullable', 'string', 'max:40'],
        ]);

        $error = $cart->add(
            $data['codigo'],
            (int) $data['cantidad'],
            $data['talle'] ?? null,
            $data['marco'] ?? null,
        );

        if ($error) {
            return back()->withErrors(['carrito' => $error]);
        }

        $producto = $catalog->find($data['codigo']);

        return back()->with('agregado', $producto?->nombreVisible() ?? 'El producto');
    }

    public function update(Request $request, CartSession $cart, int $index): RedirectResponse
    {
        $data = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $error = $cart->update($index, (int) $data['cantidad']);

        if ($error) {
            return back()->withErrors(['carrito' => $error]);
        }

        return back();
    }

    public function remove(CartSession $cart, int $index): RedirectResponse
    {
        $cart->remove($index);

        return back();
    }
}
