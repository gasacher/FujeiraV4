<?php

namespace Tests\Feature;

use Tests\TestCase;

class StorefrontTest extends TestCase
{
    public function test_legacy_html_redirects_and_the_old_preference_endpoint_is_gone(): void
    {
        $this->get('/index.html')->assertStatus(301)->assertRedirect('/');
        $this->get('/catalogo.html')->assertStatus(301)->assertRedirect('/catalogo');
        $this->get('/nosotros.html')->assertStatus(301)->assertRedirect('/nosotros');
        $this->get('/faqs.html')->assertStatus(301)->assertRedirect('/faqs');
        $this->get('/contacto.html')->assertStatus(301)->assertRedirect('/contacto');
        $this->get('/producto.html?id=FU-01')->assertStatus(301)->assertRedirect('/producto/FU-01');
        $this->get('/producto.html?id=NO-EXISTE')->assertStatus(301)->assertRedirect('/catalogo');
        $this->get('/producto.html')->assertStatus(301)->assertRedirect('/catalogo');
        $this->post('/api/crear-preferencia.php')->assertStatus(410);
    }

    public function test_sitemap_lists_live_product_urls(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('https://fujeiraretrostore.com/producto/FU-01', false)
            ->assertSee('https://fujeiraretrostore.com/producto/DEC-CUA-01', false)
            ->assertSee('https://fujeiraretrostore.com/producto/DEC-CUA-16', false)
            ->assertSee('https://fujeiraretrostore.com/catalogo', false)
            ->assertDontSee('github.io', false)
            ->assertDontSee('.html', false);
    }

    public function test_product_page_restores_the_shop_details(): void
    {
        $this->get('/producto/FU-01')
            ->assertOk()
            ->assertSee('Diseños que nacen de nosotros.')
            ->assertSee('100% algodón')
            ->assertSee('Guía de talles')
            ->assertSee('48 cm ancho × 70 cm largo')
            ->assertSee('¿No ves tu talle disponible?')
            ->assertSee('También te puede interesar')
            ->assertSee('mainProductImg', false)
            ->assertSee('data-full', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('https://fujeiraretrostore.com/', false)
            ->assertSee('"priceCurrency":"ARS"', false)
            ->assertSee('BreadcrumbList', false)
            ->assertSee('InStock', false);

        $this->get('/producto/DEC-CUA-16')
            ->assertOk()
            ->assertSee('Dinho')
            ->assertDontSee('Dinhno')
            ->assertSee('30×40 cm')
            ->assertDontSee('100% algodón');
    }

    public function test_duplicate_cuadro_names_keep_their_code(): void
    {
        $this->get('/catalogo?categoria=cuadros')
            ->assertOk()
            ->assertSee('Román · DEC-CUA-02')
            ->assertSee('Román · DEC-CUA-17')
            ->assertSee('Messi · DEC-CUA-04')
            ->assertSee('Messi · DEC-CUA-15')
            ->assertSee('id="searchInput"', false);
    }

    public function test_catalog_search_filters_on_the_server(): void
    {
        $this->get('/catalogo?q=Dinho')
            ->assertOk()
            ->assertSee('Dinho')
            ->assertDontSee('Román');
    }

    public function test_faqs_expose_faqpage_schema(): void
    {
        $this->get('/faqs')
            ->assertOk()
            ->assertSee('FAQPage', false)
            ->assertSee('¿Qué voy a encontrar en FUJEIRA?');
    }

    public function test_cart_keeps_a_way_back_and_checkout_shows_the_variant(): void
    {
        $this->from('/producto/FU-01')->post('/carrito', [
            'codigo' => 'FU-01',
            'talle' => 'L',
            'cantidad' => 1,
        ])->assertRedirect('/producto/FU-01');

        $this->get('/producto/FU-01')
            ->assertOk()
            ->assertSee('Seguir comprando')
            ->assertSee('data-show="1"', false);

        $this->get('/carrito')
            ->assertOk()
            ->assertSee('Seguir comprando')
            ->assertSee('Talle: L');

        $this->get('/checkout')
            ->assertOk()
            ->assertSee('Talle L')
            ->assertSee('El envío se confirma por WhatsApp después del pago');
    }

    public function test_checkout_summary_shows_the_frame(): void
    {
        $this->post('/carrito', [
            'codigo' => 'DEC-CUA-01',
            'marco' => 'Negro',
            'cantidad' => 1,
        ]);

        $this->get('/checkout')->assertOk()->assertSee('Marco Negro');
    }
}
