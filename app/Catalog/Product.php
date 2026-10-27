<?php

namespace App\Catalog;

use App\Support\Site;

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
        public readonly array $medidas = [],
        public readonly ?string $descripcion = null,
        public readonly ?string $preventaDetalle = null,
        public readonly ?string $etiqueta = null,
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
            medidas: array_values($row['medidas'] ?? []),
            descripcion: isset($row['descripcion']) ? (string) $row['descripcion'] : null,
            preventaDetalle: isset($row['preventa_detalle']) ? (string) $row['preventa_detalle'] : null,
        );
    }

    public function conEtiqueta(string $etiqueta): self
    {
        return new self(
            codigo: $this->codigo,
            nombre: $this->nombre,
            categoria: $this->categoria,
            proveedor: $this->proveedor,
            precio: $this->precio,
            fotos: $this->fotos,
            talles: $this->talles,
            marcos: $this->marcos,
            stock: $this->stock,
            destacado: $this->destacado,
            preventa: $this->preventa,
            medidas: $this->medidas,
            descripcion: $this->descripcion,
            preventaDetalle: $this->preventaDetalle,
            etiqueta: $etiqueta,
        );
    }

    public function nombreVisible(): string
    {
        return $this->etiqueta ?? $this->nombre;
    }

    public function esRemera(): bool
    {
        return $this->talles !== [];
    }

    public function esCuadro(): bool
    {
        return strcasecmp($this->categoria, 'cuadros') === 0;
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

    public function coincide(string $texto): bool
    {
        $texto = mb_strtolower(trim($texto));

        if ($texto === '') {
            return true;
        }

        $campos = [
            $this->nombre,
            $this->nombreVisible(),
            $this->codigo,
            $this->categoria,
        ];

        foreach ($campos as $campo) {
            if (str_contains(mb_strtolower($campo), $texto)) {
                return true;
            }
        }

        return false;
    }

    public function descripcionPublica(): string
    {
        if ($this->precio === 80000) {
            return 'Camisetas importadas calidad G5';
        }

        if ($this->descripcion) {
            return $this->descripcion;
        }

        if ($this->preventa && $this->preventaDetalle) {
            return $this->preventaDetalle;
        }

        if ($this->categoria === 'Fujeira Retro Series') {
            return 'Fujeira Retro Series. Diseños que nacen de nosotros. El apodo. La fecha. La mística que nunca se estampó.';
        }

        if ($this->esCuadro()) {
            return 'Una pieza decorativa con identidad futbolera, ideal para llevar la pasión a tu espacio.';
        }

        return 'Una retro con historia, seleccionada por FUJEIRA.';
    }

    public function descripcionEsIdentidadRetro(): bool
    {
        return $this->categoria === 'Fujeira Retro Series'
            && $this->descripcionPublica() === 'Fujeira Retro Series. Diseños que nacen de nosotros. El apodo. La fecha. La mística que nunca se estampó.';
    }

    public function medidasLegibles(): string
    {
        if ($this->medidas === []) {
            return $this->esCuadro() ? '30×40 cm' : '';
        }

        $partes = [];

        foreach ($this->medidas as $medida) {
            $texto = str_replace(['x', 'X'], '×', trim((string) $medida));

            if ($texto !== '' && ! str_contains(mb_strtolower($texto), 'cm')) {
                $texto .= ' cm';
            }

            if ($texto !== '') {
                $partes[] = $texto;
            }
        }

        return $partes === [] ? '30×40 cm' : implode(' · ', $partes);
    }

    public function fotoRelativa(int $index = 0): string
    {
        $path = $this->fotos[$index] ?? 'assets/img/Logolima_transparente.png';
        $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);

        if (is_string($webp) && is_file(public_path($webp))) {
            $path = $webp;
        }

        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }

    public function fotoPublica(int $index = 0): string
    {
        return asset($this->fotoRelativa($index));
    }

    public function fotoAbsoluta(int $index = 0): string
    {
        return Site::url($this->fotoRelativa($index));
    }

    public function whatsappTalle(): string
    {
        $texto = 'Hola FUJEIRA! Me interesa '.$this->nombre.' y no veo mi talle disponible. ¿Me avisan cuando vuelva?';

        return 'https://wa.me/5491130124589?text='.rawurlencode($texto);
    }
}
