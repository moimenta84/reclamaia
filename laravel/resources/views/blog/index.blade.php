@extends('layouts.app')

@section('title', 'Blog de Reclamaciones a Aseguradoras — Reclama')
@section('meta-description', 'Artículos prácticos sobre reclamaciones a aseguradoras en España. Qué hacer si te rechazan el siniestro, cómo reclamar por inundación, plazos legales y más. Actualizado 2024.')
@section('canonical', route('blog.index'))

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.blog-hero { background: var(--bg-section); border-bottom: 1px solid var(--border); padding: var(--sp-16) 0 var(--sp-12); }
.blog-grid { padding: var(--sp-16) 0; }
.blog-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); padding: var(--sp-7); text-decoration: none; display: block; transition: border-color var(--t-base-d), transform var(--t-base-d); }
.blog-card:hover { border-color: var(--lime-border); transform: translateY(-2px); }
.blog-card .bc-cat { font-size: var(--t-xs); font-weight: var(--fw-bold); letter-spacing: .06em; text-transform: uppercase; color: var(--lime); margin-bottom: var(--sp-3); display: block; }
.blog-card .bc-title { font-size: var(--t-lg); font-weight: var(--fw-semibold); color: var(--text); line-height: 1.3; margin-bottom: var(--sp-3); }
.blog-card .bc-desc { font-size: var(--t-sm); color: var(--text-3); line-height: 1.65; margin-bottom: var(--sp-5); }
.blog-card .bc-meta { display: flex; gap: var(--sp-4); font-size: var(--t-xs); color: var(--text-4); }
</style>
@endpush

@section('content')

<section class="blog-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-5)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">Blog</span>
    </nav>
    <h1 style="font-size:clamp(1.875rem,4vw,2.75rem);font-weight:var(--fw-bold);letter-spacing:-.03em;margin-bottom:var(--sp-4)">Blog de reclamaciones a aseguradoras</h1>
    <p style="color:var(--text-3);font-size:var(--t-lg);max-width:580px;line-height:1.7">Guías prácticas, normativa actualizada y casos reales para que puedas reclamar a tu seguro con conocimiento.</p>
  </div>
</section>

<section class="blog-grid">
  <div class="container">
    <div class="row g-4">
      @foreach($articles as $a)
      <div class="col-md-6 col-lg-4">
        <a href="{{ route('blog.show', $a['slug']) }}" class="blog-card">
          <span class="bc-cat">{{ $a['category'] }}</span>
          <div class="bc-title">{{ $a['title'] }}</div>
          <p class="bc-desc">{{ $a['description'] }}</p>
          <div class="bc-meta">
            <span>{{ \Carbon\Carbon::parse($a['date'])->locale('es')->isoFormat('D MMM YYYY') }}</span>
            <span>·</span>
            <span>{{ $a['read_min'] }} min de lectura</span>
          </div>
        </a>
      </div>
      @endforeach
    </div>

    <div style="margin-top:var(--sp-16);padding-top:var(--sp-12);border-top:1px solid var(--border)">
      @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Genera tu reclamación ahora', 'sub' => 'Carta formal con base legal en 90 segundos. Sin abogado.'])
    </div>

    <div style="margin-top:var(--sp-12)">
      <h2 style="margin-bottom:var(--sp-6)">Guías especializadas</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:var(--sp-4)">
        @foreach(\App\Http\Controllers\BlogController::$guias as $g)
        <a href="{{ route('guias.show', $g['slug']) }}" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--r-lg);padding:var(--sp-5);text-decoration:none;display:block;transition:border-color var(--t-base-d)">
          <span style="font-size:1.5rem;display:block;margin-bottom:var(--sp-3)">{{ $g['icon'] }}</span>
          <div style="font-size:var(--t-sm);font-weight:var(--fw-semibold);color:var(--text);margin-bottom:var(--sp-1)">{{ $g['title'] }}</div>
          <div style="font-size:var(--t-xs);color:var(--lime)">Leer guía →</div>
        </a>
        @endforeach
      </div>
    </div>
  </div>
</section>

@endsection
