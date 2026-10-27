<?php

namespace App\Catalog;

class V5Product
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $club,
        public readonly string $region,
        public readonly string $categoria,
        public readonly string $version,
        public readonly ?int $precio,
        public readonly array $fotos,
        public readonly ?string $thumb,
        public readonly array $talles,
        public readonly ?string $modalidad,
        public readonly bool $destacado,
    ) {}

    public static function fromArray(array $row): self
    {
        $categoria = (string) ($row['categoria'] ?? '');

        return new self(
            codigo: (string) $row['codigo'],
            nombre: (string) ($row['nombre'] ?? ''),
            club: (string) ($row['club_seleccion'] ?? ''),
            region: (string) ($row['liga_region'] ?? ''),
            categoria: $categoria,
            version: (string) ($row['categoria_display'] ?? self::etiquetaDeClave(self::claveDeCategoria($categoria))),
            precio: self::precioDe($row),
            fotos: array_values(array_filter($row['fotos'] ?? [], fn ($foto) => is_string($foto) && $foto !== '')),
            thumb: isset($row['thumb']) && is_string($row['thumb']) && $row['thumb'] !== '' ? $row['thumb'] : null,
            talles: array_values($row['talles'] ?? []),
            modalidad: isset($row['modalidad']) ? (string) $row['modalidad'] : null,
            destacado: (bool) ($row['destacado'] ?? false),
        );
    }

    public static function claveDeCategoria(string $categoria): string
    {
        return match ($categoria) {
            'version-jugador' => 'jugador',
            'version-retro' => 'retro',
            'version-fan' => 'fan',
            default => '',
        };
    }

    public static function etiquetaDeClave(string $clave): string
    {
        return match ($clave) {
            'jugador' => 'Jugador',
            'retro' => 'Retro',
            'fan' => 'Fan',
            default => '',
        };
    }

    public function versionClave(): string
    {
        return self::claveDeCategoria($this->categoria);
    }

    public function porEncargo(): bool
    {
        return $this->modalidad === 'por_encargo';
    }

    public function coincide(string $texto): bool
    {
        $texto = mb_strtolower(trim($texto));

        if ($texto === '') {
            return true;
        }

        $campos = [
            $this->nombre,
            $this->club,
            $this->codigo,
            $this->region,
            $this->version,
            self::etiquetaDeClave($this->versionClave()),
        ];

        foreach ($campos as $campo) {
            if (str_contains(mb_strtolower($campo), $texto)) {
                return true;
            }
        }

        return false;
    }

    public function tieneFoto(int $index = 0): bool
    {
        return $this->fotoRelativa($index) !== null;
    }

    public function fotoRelativa(int $index = 0): ?string
    {
        return $this->archivoExistente($this->fotos[$index] ?? null);
    }

    public function miniaturaRelativa(): ?string
    {
        return $this->archivoExistente($this->thumb) ?? $this->fotoRelativa();
    }

    public function fotoPublica(int $index = 0): ?string
    {
        $path = $this->fotoRelativa($index);

        return $path === null ? null : asset(self::codificar($path));
    }

    public function miniaturaPublica(): ?string
    {
        $path = $this->miniaturaRelativa();

        return $path === null ? null : asset(self::codificar($path));
    }

    private function archivoExistente(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        $path = ltrim($path, '/');

        return is_file(public_path($path)) ? $path : null;
    }

    private static function codificar(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }

    private static function precioDe(array $row): ?int
    {
        if (! array_key_exists('precio', $row) || $row['precio'] === null || $row['precio'] === '') {
            return null;
        }

        return (int) $row['precio'];
    }
}
