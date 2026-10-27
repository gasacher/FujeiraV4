<?php

namespace App\Http\Controllers;

use App\Catalog\V5Catalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class V5Controller extends Controller
{
    private const POR_PAGINA = 24;

    public function home(V5Catalog $catalog): View
    {
        return view('v5.home', [
            'total' => $catalog->all()->count(),
            'regiones' => $catalog->contarPorRegion(),
            'destacados' => $catalog->destacados(),
            'versiones' => V5Catalog::VERSIONES,
        ]);
    }

    public function catalogo(Request $request, V5Catalog $catalog): View
    {
        $filtros = $this->filtros($request);
        $resultado = $catalog->filtrar($filtros['q'], $filtros['club'], $filtros['region'], $filtros['version']);
        $porPagina = self::POR_PAGINA;
        $ultima = max(1, (int) ceil(max($resultado->count(), 1) / $porPagina));
        $pagina = min(max(1, (int) $request->query('page', 1)), $resultado->count() === 0 ? 1 : $ultima);

        $paginator = new LengthAwarePaginator(
            $resultado->forPage($pagina, $porPagina)->values(),
            $resultado->count(),
            $porPagina,
            $pagina,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page',
            ],
        );

        return view('v5.catalogo', [
            'productos' => $paginator,
            'filtros' => $filtros,
            'total' => $resultado->count(),
            'clubes' => $catalog->clubes(),
            'regiones' => $catalog->regiones(),
            'versiones' => V5Catalog::VERSIONES,
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

            return redirect('/'.$resto);
        }

        return response()->view('v5.sin-cobro', [], 404);
    }

    /** @return array{q: string, club: string, region: string, version: string} */
    private function filtros(Request $request): array
    {
        $version = trim($request->string('version')->toString());

        if (! array_key_exists($version, V5Catalog::VERSIONES)) {
            $version = '';
        }

        return [
            'q' => trim($request->string('q')->toString()),
            'club' => trim($request->string('club')->toString()),
            'region' => trim($request->string('region')->toString()),
            'version' => $version,
        ];
    }
}
