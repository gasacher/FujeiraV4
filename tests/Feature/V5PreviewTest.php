<?php

namespace Tests\Feature;

use App\Catalog\Catalog;
use App\Catalog\V5Catalog;
use App\Catalog\V5Product;
use Tests\TestCase;

class V5PreviewTest extends TestCase
{
    public function test_the_live_home_still_works_and_is_not_the_v5_catalog(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Diablos')
            ->assertDontSee('IMP-FAN-001')
            ->assertDontSee('Vista previa V5');

        $this->get('/producto/FU-01')->assertOk();
        $this->get('/producto/IMP-FAN-001')->assertNotFound();
        $this->get('/catalogo')->assertOk()->assertDontSee('IMP-FAN-001');
    }

    public function test_v5_preview_lists_the_imported_catalog_without_charging(): void
    {
        $this->get('/v5')
            ->assertOk()
            ->assertSee('Vista previa V5')
            ->assertSee('689')
            ->assertSee('Europa')
            ->assertSee('358')
            ->assertSee('Inter Miami CF')
            ->assertSee('Suplente Negra/Rosa')
            ->assertDontSee('Mercado Pago')
            ->assertDontSee('Agregar al carrito')
            ->assertDontSee('MYSQL_ATTR_SSL_CA');

        $this->get('/v5/catalogo')
            ->assertOk()
            ->assertSee('689 camisetas')
            ->assertSee('Inter Miami CF')
            ->assertSee('Suplente Negra/Rosa')
            ->assertSee('IMP-FAN-001')
            ->assertDontSee('FU-01')
            ->assertDontSee('No hay camisetas')
            ->assertDontSee('MYSQL_ATTR_SSL_CA');

        $this->get('/v5/catalogo?page=2')
            ->assertOk()
            ->assertSee('Página 2');

        $ficha = $this->get('/v5/producto/IMP-FAN-001')
            ->assertOk()
            ->assertSee('Inter Miami CF')
            ->assertSee('Norteamérica / Resto del Mundo')
            ->assertSee('Versión Fan')
            ->assertSee('Vista previa')
            ->assertSee('$80.000')
            ->assertDontSee('Mercado Pago')
            ->assertDontSee('Agregar al carrito')
            ->assertDontSee('action="'.route('carrito.add').'"', false);

        $foto = public_path('assets/catalogo/importadas_g5/webp/full/version-fan/IMP-FAN-001.webp');

        if (is_file($foto)) {
            $ficha->assertSee('assets/catalogo/importadas_g5/webp/full/version-fan/IMP-FAN-001.webp', false);
            $ficha->assertDontSee('Foto no disponible');
        } else {
            $ficha->assertSee('Foto no disponible');
        }

        $this->get('/v5/producto/FU-01')->assertNotFound();
    }

    public function test_v5_catalog_filters_by_club_region_version_and_search(): void
    {
        $this->get('/v5/catalogo?region=Europa')
            ->assertOk()
            ->assertSee('IMP-FAN-004')
            ->assertDontSee('IMP-FAN-002')
            ->assertDontSee('IMP-RET-001');

        $this->get('/v5/catalogo?club=Boca+Juniors')
            ->assertOk()
            ->assertSee('IMP-FAN-002')
            ->assertDontSee('IMP-JUG-001');

        $this->get('/v5/catalogo?version=retro&region=Brasil')
            ->assertOk()
            ->assertSee('IMP-RET-001')
            ->assertDontSee('IMP-FAN-001')
            ->assertDontSee('IMP-JUG-001');

        $this->get('/v5/catalogo?q=Manchester+United&version=jugador')
            ->assertOk()
            ->assertSee('IMP-JUG-011')
            ->assertDontSee('IMP-RET-001');
    }

    public function test_v5_subdomain_serves_the_preview_and_blocks_checkout(): void
    {
        $this->get('http://v5.fujeiraretrostore.com/')
            ->assertOk()
            ->assertSee('Vista previa V5')
            ->assertSee('689')
            ->assertDontSee('Diablos');

        $this->get('http://v5.fujeiraretrostore.com/producto/IMP-FAN-001')
            ->assertOk()
            ->assertSee('Inter Miami CF')
            ->assertDontSee('Agregar al carrito');

        $this->get('http://v5.fujeiraretrostore.com/checkout')
            ->assertNotFound()
            ->assertSee('no se cobra')
            ->assertDontSee('Continuar a Mercado Pago');

        $this->post('http://v5.fujeiraretrostore.com/checkout', [
            'nombre' => 'Gabriela',
        ])->assertNotFound();

        $this->post('http://v5.fujeiraretrostore.com/webhooks/mercadopago')
            ->assertNotFound();

        $this->get('http://v5.fujeiraretrostore.com/v5/catalogo')
            ->assertRedirect('/catalogo');
    }

    public function test_v5_catalog_counts_match_the_imported_json_and_not_the_live_one(): void
    {
        $v5 = app(V5Catalog::class);
        $enVivo = app(Catalog::class);

        $this->assertSame(689, $v5->all()->count());
        $this->assertSame(689, $v5->all()->unique(fn ($product) => $product->codigo)->count());
        $this->assertNull($enVivo->find('IMP-FAN-001'));
        $this->assertNotNull($enVivo->find('FU-01'));
        $this->assertNull($v5->find('FU-01'));

        $porRegion = collect($v5->contarPorRegion())->mapWithKeys(
            fn (array $fila) => [$fila['region'] => $fila['total']]
        );

        $this->assertSame(358, $porRegion['Europa']);
        $this->assertSame(183, $porRegion['Selecciones']);
        $this->assertSame(70, $porRegion['Brasil']);
        $this->assertSame(45, $porRegion['Fútbol Argentino']);
        $this->assertSame(29, $porRegion['Norteamérica / Resto del Mundo']);
        $this->assertSame(4, $porRegion['Resto del Mundo']);
        $this->assertSame(33, $porRegion['Norteamérica / Resto del Mundo'] + $porRegion['Resto del Mundo']);
    }

    public function test_v5_does_not_invent_a_price_or_a_missing_photo(): void
    {
        $producto = V5Product::fromArray([
            'codigo' => 'TEST-SIN-PRECIO',
            'nombre' => 'Sin precio',
            'club_seleccion' => 'Club de prueba',
            'liga_region' => 'Europa',
            'categoria' => 'version-fan',
            'categoria_display' => 'Versión Fan',
            'fotos' => ['assets/catalogo/importadas_g5/webp/full/version-fan/no-existe.webp'],
            'thumb' => 'assets/catalogo/importadas_g5/webp/thumb/version-fan/no-existe.webp',
        ]);

        $this->assertNull($producto->precio);
        $this->assertFalse($producto->tieneFoto());
        $this->assertNull($producto->fotoPublica());
        $this->assertNull($producto->miniaturaPublica());
    }

    public function test_database_config_does_not_read_the_deprecated_mysql_ssl_constant_on_php_85(): void
    {
        $source = file_get_contents(config_path('database.php'));

        $this->assertStringContainsString("defined('Pdo\\Mysql::ATTR_SSL_CA')", $source);
        $this->assertDoesNotMatchRegularExpression('/^\s*PDO::MYSQL_ATTR_SSL_CA\s*=>/m', $source);

        $deprecations = [];
        set_error_handler(function (int $level, string $message) use (&$deprecations): bool {
            if (in_array($level, [E_DEPRECATED, E_USER_DEPRECATED], true)) {
                $deprecations[] = $message;

                return true;
            }

            return false;
        });

        try {
            $config = include config_path('database.php');
        } finally {
            restore_error_handler();
        }

        $this->assertSame([], $deprecations);
        $this->assertIsArray($config['connections']['mysql']['options']);
        $this->assertIsArray($config['connections']['mariadb']['options']);
        $this->assertSame('sqlite', $config['connections']['sqlite']['driver']);
    }
}
