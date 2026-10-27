<?php

namespace App\Catalog;

class V5Product
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $categoria,
        public readonly string $archivo,
        public readonly array $fotos,
    ) {}

    public static function fromArray(array $row): self
    {
        return new self(
            codigo: (string) $row['codigo'],
            nombre: (string) ($row['nombre'] ?? ''),
            categoria: (string) ($row['categoria'] ?? ''),
            archivo: (string) ($row['archivo'] ?? ''),
            fotos: array_values(array_filter(
                $row['fotos'] ?? [],
                fn ($foto) => is_string($foto) && $foto !== ''
            )),
        );
    }

    public function coincide(string $texto): bool
    {
        $texto = mb_strtolower(trim($texto));

        if ($texto === '') {
            return true;
        }

        foreach ([$this->nombre, $this->codigo, $this->categoria, $this->archivo] as $campo) {
            if (str_contains(mb_strtolower($campo), $texto)) {
                return true;
            }
        }

        return false;
    }

    public function fotoPublica(int $index = 0): string
    {
        $path = ltrim((string) ($this->fotos[$index] ?? 'assets/img/Logolima_transparente.png'), '/');

        return asset(implode('/', array_map('rawurlencode', explode('/', $path))));
    }
}
