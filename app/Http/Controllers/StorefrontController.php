<?php

namespace App\Http\Controllers;

use App\Catalog\Catalog;
use App\Catalog\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(Catalog $catalog): View
    {
        return view('home', [
            'remeras' => $catalog->deCategoria('Fujeira Retro Series')->take(4),
            'cuadros' => $catalog->destacados()
                ->filter(fn ($product) => $product->categoria === 'cuadros')
                ->take(4)
                ->values(),
            'indice' => $catalog->all()->map(fn (Product $product) => [
                'nombre' => $product->nombreVisible(),
                'codigo' => $product->codigo,
                'categoria' => $product->categoria,
                'url' => route('producto.show', $product->codigo),
            ])->values(),
        ]);
    }

    public function catalogo(Request $request, Catalog $catalog): View
    {
        $categoria = $request->string('categoria')->toString();
        $q = trim($request->string('q')->toString());

        return view('catalogo', [
            'productos' => $catalog->buscar($categoria !== '' ? $categoria : null, $q),
            'categoria' => $categoria,
            'q' => $q,
        ]);
    }

    public function producto(string $codigo, Catalog $catalog): View
    {
        $producto = $catalog->find($codigo);
        abort_if($producto === null, 404);

        return view('producto', [
            'producto' => $producto,
            'relacionados' => $catalog->relacionados($producto),
        ]);
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
