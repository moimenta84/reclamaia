<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => route('home'),              'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('seo.reclamaciones'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('seo.reclamar-seguro'),'priority'=> '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('seo.hogar'),          'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('seo.coche'),          'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('seo.vida'),           'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('seo.salud'),          'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('seo.fallecidos'),     'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('seo.desastres'),      'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('blog.index'),         'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('guias.index'),        'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('claim.create'),       'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('tools.baremo.show'),  'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach (BlogController::$articles as $article) {
            $urls[] = ['loc' => route('blog.show', $article['slug']), 'priority' => '0.6', 'changefreq' => 'yearly'];
        }

        foreach (BlogController::$guias as $guia) {
            $urls[] = ['loc' => route('guias.show', $guia['slug']), 'priority' => '0.6', 'changefreq' => 'yearly'];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public function robots(): Response
    {
        $content = view('robots')->render();
        return response($content, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}
