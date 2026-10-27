<?php

namespace App\Catalog;

use Illuminate\Support\Collection;
use RuntimeException;

class Catalog
{
    /** @var Collection<string, Product>|null */
    private ?Collection $products = null;

    public function all(): Collection
    {
        return $this->load()->values();
    }

    public function find(string $codigo): ?Product
    {
        return $this->load()->get($codigo);
    }

    public function destacados(): Collection
    {
        return $this->all()->filter(fn (Product $product) => $product->destacado)->values();
    }

    public function deCategoria(?string $categoria): Collection
    {
        if ($categoria === null || $categoria === '' || $categoria === 'todos') {
            return $this->all();
        }

        return $this->all()
            ->filter(fn (Product $product) => strcasecmp($product->categoria, $categoria) === 0)
            ->values();
    }

    /** @return Collection<string, Product> */
    private function load(): Collection
    {
        if ($this->products !== null) {
            return $this->products;
        }

        $products = collect();

        foreach ([
            database_path('data/productos-retroseries.json'),
            database_path('data/productos-cuadros.json'),
        ] as $path) {
            $decoded = json_decode((string) file_get_contents($path), true);

            if (! is_array($decoded)) {
                throw new RuntimeException('No se pudo leer el catálogo.');
            }

            foreach ($decoded as $row) {
                if (! is_array($row) || empty($row['codigo'])) {
                    continue;
                }

                $product = Product::fromArray($row);
                $products->put($product->codigo, $product);
            }
        }

        $counts = [];

        foreach ($products as $product) {
            $key = mb_strtolower($product->nombre);
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        $products = $products->map(function (Product $product) use ($counts) {
            $key = mb_strtolower($product->nombre);

            if (($counts[$key] ?? 0) < 2) {
                return $product;
            }

            return $product->conEtiqueta($product->nombre.' · '.$product->codigo);
        });

        return $this->products = $products;
    }

    public function relacionados(Product $producto, int $limite = 4): Collection
    {
        return $this->all()
            ->filter(function (Product $otro) use ($producto) {
                return $otro->codigo !== $producto->codigo
                    && strcasecmp($otro->categoria, $producto->categoria) === 0
                    && $otro->fotos !== [];
            })
            ->take($limite)
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
            ->filter(fn (Product $product) => $product->coincide($texto))
            ->values();
    }
}
