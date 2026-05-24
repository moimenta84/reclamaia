<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SeoController extends Controller
{
    public function reclamaciones(): View
    {
        return view('seo.reclamaciones');
    }

    public function reclamarSeguro(): View
    {
        return view('seo.reclamar-seguro');
    }

    public function hogar(): View
    {
        return view('seo.hogar');
    }

    public function coche(): View
    {
        return view('seo.coche');
    }

    public function vida(): View
    {
        return view('seo.vida');
    }

    public function salud(): View
    {
        return view('seo.salud');
    }

    public function fallecidos(): View
    {
        return view('seo.fallecidos');
    }

    public function desastres(): View
    {
        return view('seo.desastres');
    }
}
