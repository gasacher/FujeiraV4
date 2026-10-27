<?php

namespace App\Cart;

use App\Catalog\Catalog;
use App\Catalog\Product;

class CartSession
{
    private const KEY = 'fujeira.cart';

    public function __construct(private readonly Catalog $catalog) {}

    public function lines(): array
    {
        return array_values(session(self::KEY, []));
    }

    /**
     * @return array<int, array{product: Product, talle: ?string, marco: ?string, cantidad: int, subtotal: int}>
     */
    public function detailed(): array
    {
        $detailed = [];

        foreach ($this->lines() as $line) {
            $product = $this->catalog->find($line['codigo'] ?? '');

            if (! $product) {
                continue;
            }

            $talle = $line['talle'] ?? null;
            $marco = $line['marco'] ?? null;
            $cantidad = (int) ($line['cantidad'] ?? 0);
            $stock = $product->stockDe($talle);

            if ($stock !== null) {
                $cantidad = min($cantidad, $stock);
            }

            if ($cantidad < 1) {
                continue;
            }

            $detailed[] = [
                'product' => $product,
                'talle' => $talle,
                'marco' => $marco,
                'cantidad' => $cantidad,
                'subtotal' => $product->precio * $cantidad,
            ];
        }

        return $detailed;
    }

    public function count(): int
    {
        return array_sum(array_column($this->detailed(), 'cantidad'));
    }

    public function total(): int
    {
        return array_sum(array_column($this->detailed(), 'subtotal'));
    }

    public function add(string $codigo, int $cantidad, ?string $talle, ?string $marco): ?string
    {
        $product = $this->catalog->find($codigo);

        if (! $product) {
            return 'Ese producto no está en el catálogo.';
        }

        if ($cantidad < 1) {
            return 'La cantidad no es válida.';
        }

        if ($product->esRemera()) {
            if ($talle === null || ! in_array($talle, $product->talles, true)) {
                return 'Elegí un talle.';
            }

            if ($product->stockDe($talle) < 1) {
                return 'Ese talle no tiene stock.';
            }

            $marco = null;
        } else {
            $talle = null;

            if ($product->marcos !== [] && ($marco === null || ! in_array($marco, $product->marcos, true))) {
                return 'Elegí un marco.';
            }
        }

        $lines = $this->lines();
        $found = false;

        foreach ($lines as $index => $line) {
            $same = $line['codigo'] === $product->codigo
                && ($line['talle'] ?? null) === $talle
                && ($line['marco'] ?? null) === $marco;

            if (! $same) {
                continue;
            }

            $lines[$index]['cantidad'] = (int) $line['cantidad'] + $cantidad;
            $found = true;
            break;
        }

        if (! $found) {
            $lines[] = [
                'codigo' => $product->codigo,
                'talle' => $talle,
                'marco' => $marco,
                'cantidad' => $cantidad,
            ];
        }

        $error = $this->guardStock($product, $lines, $talle);

        if ($error) {
            return $error;
        }

        session([self::KEY => $lines]);

        return null;
    }

    public function update(int $index, int $cantidad): ?string
    {
        $lines = $this->lines();

        if (! isset($lines[$index])) {
            return 'Ese producto no está en el carrito.';
        }

        if ($cantidad < 1) {
            unset($lines[$index]);
            session([self::KEY => array_values($lines)]);

            return null;
        }

        $product = $this->catalog->find($lines[$index]['codigo']);

        if (! $product) {
            unset($lines[$index]);
            session([self::KEY => array_values($lines)]);

            return 'Ese producto ya no está en el catálogo.';
        }

        $lines[$index]['cantidad'] = $cantidad;
        $error = $this->guardStock($product, $lines, $lines[$index]['talle'] ?? null);

        if ($error) {
            return $error;
        }

        session([self::KEY => array_values($lines)]);

        return null;
    }

    public function remove(int $index): void
    {
        $lines = $this->lines();
        unset($lines[$index]);
        session([self::KEY => array_values($lines)]);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    private function guardStock(Product $product, array $lines, ?string $talle): ?string
    {
        $stock = $product->stockDe($talle);

        if ($stock === null) {
            return null;
        }

        $total = 0;

        foreach ($lines as $line) {
            if ($line['codigo'] === $product->codigo && ($line['talle'] ?? null) === $talle) {
                $total += (int) $line['cantidad'];
            }
        }

        if ($total > $stock) {
            return 'No hay tantas unidades de ese talle.';
        }

        return null;
    }
}
