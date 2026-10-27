<?php

namespace App\Http\Controllers;

use App\Catalog\V5Catalog;
use App\Catalog\V5Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class V5Controller extends Controller
{
    public function home(V5Catalog $catalog): View
    {
        $grupos = [];

        foreach (V5Catalog::CATEGORIAS as $categoria) {
            $grupos[$categoria] = $catalog->deCategoria($categoria)->take(4)->values();
        }

        return view('v5.home', [
            'grupos' => $grupos,
            'slides' => $this->slides($catalog),
        ]);
    }

    public function catalogo(Request $request, V5Catalog $catalog): View
    {
        $categoria = $request->string('categoria')->toString();
        $q = trim($request->string('q')->toString());

        return view('v5.catalogo', [
            'productos' => $catalog->buscar($categoria !== '' ? $categoria : null, $q),
            'categoria' => $categoria,
            'q' => $q,
            'categorias' => V5Catalog::CATEGORIAS,
        ]);
    }

    public function producto(string $codigo, V5Catalog $catalog): View
    {
        $producto = $catalog->find($codigo);
        abort_if($producto === null, 404);

        return view('v5.producto', [
            'producto' => $producto,
            'relacionados' => $catalog->relacionados($producto),
        ]);
    }

    public function sinCobro(Request $request): Response|RedirectResponse
    {
        $path = trim($request->path(), '/');

        if ($path === 'v5' || str_starts_with($path, 'v5/')) {
            $resto = ltrim(substr($path, strlen('v5')), '/');

            return redirect('/'.ltrim($resto, '/'));
        }

        return response()->view('v5.sin-cobro', [], 404);
    }

    /** @return list<array{modifier: string, titulo: string, producto: V5Product, izquierda: V5Product, derecha: V5Product, eager: bool}> */
    private function slides(V5Catalog $catalog): array
    {
        $estilos = [
            'Jugador' => 'diablos',
            'Retro' => 'xeneizes',
            'Fan' => 'cuervos',
        ];

        $porCategoria = [];

        foreach ($estilos as $categoria => $estilo) {
            $porCategoria[$categoria] = $catalog->deCategoria($categoria)->first();
        }

        $slides = [];
        $categorias = array_keys($estilos);

        foreach ($categorias as $indice => $categoria) {
            $producto = $porCategoria[$categoria] ?? null;

            if (! $producto instanceof V5Product) {
                continue;
            }

            $izquierda = $porCategoria[$categorias[($indice + 2) % 3]] ?? $producto;
            $derecha = $porCategoria[$categorias[($indice + 1) % 3]] ?? $producto;

            $slides[] = [
                'modifier' => $estilos[$categoria],
                'titulo' => mb_strtoupper($categoria),
                'producto' => $producto,
                'izquierda' => $izquierda,
                'derecha' => $derecha,
                'eager' => $indice === 0,
            ];
        }

        return $slides;
    }
}
