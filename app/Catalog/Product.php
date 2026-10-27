<?php

namespace App\Catalog;

class Product
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $categoria,
        public readonly string $proveedor,
        public readonly int $precio,
        public readonly array $fotos,
        public readonly array $talles,
        public readonly array $marcos,
        public readonly array $stock,
        public readonly bool $destacado,
        public readonly bool $preventa,
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            codigo: (string) $row['codigo'],
            nombre: (string) $row['nombre'],
            categoria: (string) ($row['categoria'] ?? ''),
            proveedor: (string) ($row['proveedor'] ?? ''),
            precio: (int) $row['precio'],
            fotos: array_values($row['fotos'] ?? []),
            talles: array_values($row['talles'] ?? []),
            marcos: array_values($row['marco'] ?? []),
            stock: $row['stock'] ?? [],
            destacado: (bool) ($row['destacado'] ?? false),
            preventa: (bool) ($row['preventa'] ?? false),
        );
    }

    public function esRemera(): bool
    {
        return $this->talles !== [];
    }

    public function stockDe(?string $talle): ?int
    {
        if (! $this->esRemera()) {
            return null;
        }

        if ($talle === null || ! array_key_exists($talle, $this->stock)) {
            return 0;
        }

        return (int) $this->stock[$talle];
    }

    public function sinStock(): bool
    {
        if (! $this->esRemera()) {
            return false;
        }

        foreach ($this->talles as $talle) {
            if ($this->stockDe($talle) > 0) {
                return false;
            }
        }

        return true;
    }

    public function fotoPublica(int $index = 0): string
    {
        $path = $this->fotos[$index] ?? 'assets/img/Logolima_transparente.png';
        $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));

        return asset($encoded);
    }
}
