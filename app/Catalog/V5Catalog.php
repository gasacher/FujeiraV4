<?php

namespace App\Catalog;

use Illuminate\Support\Collection;
use RuntimeException;

class V5Catalog
{
    /** @var list<string> */
    public const REGION_ORDEN = [
        'Europa',
        'Selecciones',
        'Brasil',
        'Fútbol Argentino',
        'Norteamérica / Resto del Mundo',
        'Resto del Mundo',
    ];

    /** @var array<string, string> */
    public const VERSIONES = [
        'jugador' => 'Jugador',
        'retro' => 'Retro',
        'fan' => 'Fan',
    ];

    /** @var Collection<string, V5Product>|null */
    private ?Collection $products = null;

    public function all(): Collection
    {
        return $this->load()->values();
    }

    public function find(string $codigo): ?V5Product
    {
        return $this->load()->get($codigo);
    }

    public function filtrar(?string $texto, ?string $club, ?string $region, ?string $version): Collection
    {
        $texto = trim((string) $texto);
        $club = trim((string) $club);
        $region = trim((string) $region);
        $version = trim((string) $version);

        if ($version !== '' && ! array_key_exists($version, self::VERSIONES)) {
            $version = '';
        }

        return $this->all()
            ->filter(function (V5Product $product) use ($texto, $club, $region, $version) {
                if ($club !== '' && $product->club !== $club) {
                    return false;
                }

                if ($region !== '' && $product->region !== $region) {
                    return false;
                }

                if ($version !== '' && $product->versionClave() !== $version) {
                    return false;
                }

                return $texto === '' || $product->coincide($texto);
            })
            ->values();
    }

    /** @return list<string> */
    public function clubes(): array
    {
        return $this->all()
            ->map(fn (V5Product $product) => $product->club)
            ->filter()
            ->unique()
            ->sort(fn (string $a, string $b) => strcasecmp($a, $b))
            ->values()
            ->all();
    }

    /** @return list<string> */
    public function regiones(): array
    {
        $presentes = $this->all()
            ->map(fn (V5Product $product) => $product->region)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $ordenadas = [];

        foreach (self::REGION_ORDEN as $region) {
            if (in_array($region, $presentes, true)) {
                $ordenadas[] = $region;
            }
        }

        foreach ($presentes as $region) {
            if (! in_array($region, $ordenadas, true)) {
                $ordenadas[] = $region;
            }
        }

        return $ordenadas;
    }

    /** @return list<array{region: string, total: int}> */
    public function contarPorRegion(): array
    {
        $conteo = $this->all()->countBy(fn (V5Product $product) => $product->region);

        return array_map(
            fn (string $region) => ['region' => $region, 'total' => (int) ($conteo[$region] ?? 0)],
            $this->regiones(),
        );
    }

    public function destacados(int $limite = 8): Collection
    {
        $marcados = $this->all()->filter(fn (V5Product $product) => $product->destacado)->values();

        if ($marcados->count() >= $limite) {
            return $marcados->take($limite)->values();
        }

        return $marcados
            ->concat($this->all()->reject(fn (V5Product $product) => $product->destacado))
            ->take($limite)
            ->values();
    }

    public function relacionados(V5Product $producto, int $limite = 4): Collection
    {
        return $this->all()
            ->filter(fn (V5Product $otro) => $otro->codigo !== $producto->codigo && $otro->club === $producto->club)
            ->take($limite)
            ->values();
    }

    /** @return Collection<string, V5Product> */
    private function load(): Collection
    {
        if ($this->products !== null) {
            return $this->products;
        }

        $path = database_path('data/productos-importadas-g5.json');
        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            throw new RuntimeException('No se pudo leer el catálogo V5.');
        }

        $products = collect();

        foreach ($decoded as $row) {
            if (! is_array($row) || empty($row['codigo'])) {
                continue;
            }

            $product = V5Product::fromArray($row);
            $products->put($product->codigo, $product);
        }

        return $this->products = $products;
    }
}
