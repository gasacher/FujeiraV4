<?php

namespace Tests\Feature;

use App\Catalog\Catalog;
use App\Catalog\V5Catalog;
use Tests\TestCase;

class V5PreviewTest extends TestCase
{
    public function test_the_live_store_still_renders_and_ignores_v5_products(): void
    {
        $codigo = $this->codigoDe('Jugador');

        $this->get('/')
            ->assertOk()
            ->assertSee('Destacados')
            ->assertSee('Diablos')
            ->assertDontSee('Vista previa, sin compra')
            ->assertDontSee($codigo);

        $this->get('/catalogo')
            ->assertOk()
            ->assertDontSee($codigo)
            ->assertDontSee('Jugador 01');

        $this->get('/producto/'.$codigo)->assertNotFound();
        $this->get('/producto/FU-01')->assertOk();

        $this->assertNull(app(Catalog::class)->find($codigo));
        $this->assertNotNull(app(Catalog::class)->find('FU-01'));
        $this->assertNull(app(V5Catalog::class)->find('FU-01'));

        $this->post('/carrito', [
            'codigo' => $codigo,
            'talle' => 'L',
            'cantidad' => 1,
        ])->assertSessionHasErrors('carrito');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/v5', false)
            ->assertDontSee($codigo, false);
    }

    public function test_v5_preview_returns_the_sample_catalog_without_a_price(): void
    {
        $codigo = $this->codigoDe('Jugador');
        $resumen = app(V5Catalog::class)->resumen();

        $this->get('/v5')
            ->assertOk()
            ->assertSee('Vista previa, sin compra')
            ->assertSee('18 de 256 en Jugador')
            ->assertSee('18 de 292 en Retro')
            ->assertSee('18 de 202 en Fan')
            ->assertSee('Jugador 01')
            ->assertSee('Retro 01')
            ->assertSee('Fan 01')
            ->assertDontSee('Agregar al carrito')
            ->assertDontSee('Mercado Pago');

        $this->get('/v5/catalogo')
            ->assertOk()
            ->assertSee($codigo)
            ->assertSee('Sin precio')
            ->assertDontSee('FU-01');

        $this->get('/v5/catalogo?categoria=Fan')
            ->assertOk()
            ->assertSee('Fan 01')
            ->assertDontSee('Jugador 01');

        $this->get('/v5/producto/'.$codigo)
            ->assertOk()
            ->assertSee('Jugador 01')
            ->assertSee('Vista previa, sin compra')
            ->assertSee('a definir')
            ->assertSee($codigo)
            ->assertDontSee('Agregar al carrito')
            ->assertDontSee('priceCurrency', false)
            ->assertDontSee('$');

        $this->get('/v5/producto/FU-01')->assertNotFound();

        $this->assertTrue($resumen['muestra']);
        $this->assertSame(256, $resumen['totales']['Jugador']);
        $this->assertSame(292, $resumen['totales']['Retro']);
        $this->assertSame(202, $resumen['totales']['Fan']);
        $this->assertSame(['Jugador' => 18, 'Retro' => 18, 'Fan' => 18], $resumen['en_vista']);
    }

    public function test_v5_subdomain_serves_the_preview_at_the_root_and_blocks_checkout(): void
    {
        $codigo = $this->codigoDe('Retro');

        $this->get('http://v5.fujeiraretrostore.com/')
            ->assertOk()
            ->assertSee('Vista previa, sin compra')
            ->assertSee('Retro 01')
            ->assertDontSee('Diablos');

        $this->get('http://v5.fujeiraretrostore.com/catalogo?categoria=Retro')
            ->assertOk()
            ->assertSee('Retro 01')
            ->assertDontSee('Jugador 01');

        $this->get('http://v5.fujeiraretrostore.com/producto/'.$codigo)
            ->assertOk()
            ->assertSee('Vista previa, sin compra')
            ->assertSee('a definir')
            ->assertDontSee('Agregar al carrito');

        $this->get('http://v5.fujeiraretrostore.com/producto/FU-01')->assertNotFound();

        $this->get('http://v5.fujeiraretrostore.com/checkout')
            ->assertNotFound()
            ->assertSee('Vista previa, sin compra')
            ->assertDontSee('Continuar a Mercado Pago');

        $this->post('http://v5.fujeiraretrostore.com/carrito', [
            'codigo' => $codigo,
            'cantidad' => 1,
        ])->assertNotFound();

        $this->get('http://v5.fujeiraretrostore.com/v5/catalogo')
            ->assertRedirect('/catalogo');

        $this->get('/checkout')->assertOk()->assertSee('Continuar a Mercado Pago');
    }

    private function codigoDe(string $categoria): string
    {
        $producto = app(V5Catalog::class)->deCategoria($categoria)->first();

        $this->assertNotNull($producto);

        return $producto->codigo;
    }
}
