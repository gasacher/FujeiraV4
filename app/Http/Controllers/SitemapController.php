<?php

namespace App\Http\Controllers;

use App\Catalog\Catalog;
use App\Support\Site;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(Catalog $catalog): Response
    {
        $urls = [
            [Site::url('/'), 'weekly', '1.0'],
            [Site::url('/catalogo'), 'weekly', '0.9'],
            [Site::url('/nosotros'), 'monthly', '0.6'],
            [Site::url('/faqs'), 'monthly', '0.6'],
            [Site::url('/contacto'), 'monthly', '0.5'],
        ];

        foreach ($catalog->all() as $producto) {
            $urls[] = [Site::url('/producto/'.$producto->codigo), 'weekly', '0.8'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as [$loc, $changefreq, $priority]) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($loc, ENT_XML1)."</loc>\n";
            $xml .= '    <changefreq>'.$changefreq."</changefreq>\n";
            $xml .= '    <priority>'.$priority."</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
