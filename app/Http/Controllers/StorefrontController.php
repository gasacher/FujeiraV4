<?php

namespace App\Http\Controllers;

use App\Catalog\Catalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(Catalog $catalog): View
    {
        return view('home', [
            'destacados' => $catalog->destacados(),
            'remeras' => $catalog->deCategoria('Fujeira Retro Series')->take(4),
            'cuadros' => $catalog->all()->filter(fn ($product) => $product->proveedor === 'cuadros')->take(4),
        ]);
    }

    public function catalogo(Request $request, Catalog $catalog): View
    {
        $categoria = $request->string('categoria')->toString();

        return view('catalogo', [
            'productos' => $catalog->deCategoria($categoria !== '' ? $categoria : null),
            'categoria' => $categoria,
        ]);
    }

    public function producto(string $codigo, Catalog $catalog): View
    {
        $producto = $catalog->find($codigo);
        abort_if($producto === null, 404);

        return view('producto', ['producto' => $producto]);
    }

    public function nosotros(): View
    {
        return view('nosotros');
    }

    public function faqs(): View
    {
        return view('faqs');
    }

    public function contacto(): View
    {
        return view('contacto');
    }
}
