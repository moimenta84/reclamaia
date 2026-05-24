@extends('layouts.app')

@section('title', 'Guías de Reclamaciones a Seguros — Manuales Completos')
@section('meta-description', 'Guías completas y manuales paso a paso para reclamar a aseguradoras en España. LCS, baremo 2024, DGSFP y más. Descarga gratuita.')
@section('canonical', route('guias.index'))

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.guias-hero { background: var(--bg-section); border-bottom: 1px solid var(--border); padding: var(--sp-16) 0 var(--sp-12); }
.guias-grid { padding: var(--sp-16) 0; }
.guia-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); padding: var(--sp-8); text-decoration: none; display: block; transition: border-color var(--t-base-d), transform var(--t-base-d); }
.guia-card:hover { border-color: var(--lime-border); transform: translateY(-2px); }
</style>
@endpush

@section('content')

<section class="guias-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-5)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">Guías</span>
    </nav>
    <h1 style="font-size:clamp(1.875rem,4vw,2.75rem);font-weight:var(--fw-bold);letter-spacing:-.03em;margin-bottom:var(--sp-4)">Guías completas de reclamaciones</h1>
    <p style="color:var(--text-3);font-size:var(--t-lg);max-width:580px;line-height:1.7">Manuales detallados con toda la normativa, plazos y documentación necesaria para reclamar a tu seguro.</p>
  </div>
</section>

<section class="guias-grid">
  <div class="container">
    <div class="row g-4">
      @foreach($guias as $g)
      <div class="col-md-6">
        <a href="{{ route('guias.show', $g['slug']) }}" class="guia-card">
          <span style="font-size:2rem;display:block;margin-bottom:var(--sp-4)">{{ $g['icon'] }}</span>
          <h2 style="font-size:var(--t-xl);font-weight:var(--fw-semibold);color:var(--text);margin-bottom:var(--sp-3)">{{ $g['title'] }}</h2>
          <p style="font-size:var(--t-sm);color:var(--text-3);line-height:1.65;margin-bottom:var(--sp-5)">{{ $g['description'] }}</p>
          <div style="font-size:var(--t-sm);color:var(--lime);font-weight:var(--fw-semibold)">Leer guía completa →</div>
        </a>
      </div>
      @endforeach
    </div>

    <div style="margin-top:var(--sp-16);padding-top:var(--sp-12);border-top:1px solid var(--border)">
      @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Generar reclamación ahora', 'sub' => 'Carta formal con base legal en 90 segundos. Sin abogado.'])
    </div>
  </div>
</section>

@endsection
