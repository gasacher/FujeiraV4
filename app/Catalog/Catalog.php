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

        return $this->products = $products;
    }
}
