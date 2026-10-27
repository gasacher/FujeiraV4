<?php

namespace App\Catalog;

use Illuminate\Support\Collection;
use RuntimeException;

class V5Catalog
{
    /** @var list<string> */
    public const CATEGORIAS = ['Jugador', 'Retro', 'Fan'];

    /** @var Collection<string, V5Product>|null */
    private ?Collection $products = null;

    /** @var array<string, mixed> */
    private array $meta = [];

    public function all(): Collection
    {
        return $this->load()->values();
    }

    public function find(string $codigo): ?V5Product
    {
        return $this->load()->get($codigo);
    }

    public function deCategoria(?string $categoria): Collection
    {
        if ($categoria === null || $categoria === '' || $categoria === 'todos') {
            return $this->all();
        }

        return $this->all()
            ->filter(fn (V5Product $product) => strcasecmp($product->categoria, $categoria) === 0)
            ->values();
    }

    public function buscar(?string $categoria, ?string $texto): Collection
    {
        $productos = $this->deCategoria($categoria);
        $texto = trim((string) $texto);

        if ($texto === '') {
            return $productos;
        }

        return $productos
            ->filter(fn (V5Product $product) => $product->coincide($texto))
            ->values();
    }

    public function relacionados(V5Product $producto, int $limite = 4): Collection
    {
        return $this->all()
            ->filter(fn (V5Product $otro) => $otro->codigo !== $producto->codigo
                && strcasecmp($otro->categoria, $producto->categoria) === 0)
            ->take($limite)
            ->values();
    }

    /** @return array{muestra: bool, nota: string, origen: string, totales: array<string, int>, en_vista: array<string, int>} */
    public function resumen(): array
    {
        $this->load();

        $enVista = [];

        foreach (self::CATEGORIAS as $categoria) {
            $enVista[$categoria] = $this->deCategoria($categoria)->count();
        }

        $totales = is_array($this->meta['totales_en_drive'] ?? null)
            ? $this->meta['totales_en_drive']
            : [];

        return [
            'muestra' => (bool) ($this->meta['muestra'] ?? true),
            'nota' => (string) ($this->meta['nota'] ?? ''),
            'origen' => (string) ($this->meta['origen'] ?? ''),
            'totales' => $totales,
            'en_vista' => $enVista,
        ];
    }

    /** @return Collection<string, V5Product> */
    private function load(): Collection
    {
        if ($this->products !== null) {
            return $this->products;
        }

        $decoded = json_decode((string) file_get_contents(database_path('data/productos-v5.json')), true);

        if (! is_array($decoded) || ! is_array($decoded['productos'] ?? null)) {
            throw new RuntimeException('No se pudo leer el catálogo v5.');
        }

        $this->meta = $decoded;
        $products = collect();

        foreach ($decoded['productos'] as $row) {
            if (! is_array($row) || empty($row['codigo'])) {
                continue;
            }

            if (array_key_exists('precio', $row) && $row['precio'] !== null) {
                throw new RuntimeException('La vista previa v5 no puede tener precios.');
            }

            $product = V5Product::fromArray($row);
            $products->put($product->codigo, $product);
        }

        return $this->products = $products;
    }
}
