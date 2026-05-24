@extends('layouts.app')

@section('title', $guia['title'] . ' — Reclama Guías')
@section('meta-description', $guia['description'])
@section('canonical', route('guias.show', $guia['slug']))
@section('og-title', $guia['title'])
@section('og-description', $guia['description'])

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "{{ $guia['title'] }}",
  "description": "{{ $guia['description'] }}",
  "publisher": { "@type": "Organization", "name": "Reclama" }
}
</script>
@endpush

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.guia-hero { background: var(--bg-section); border-bottom: 1px solid var(--border); padding: var(--sp-16) 0 var(--sp-12); }
.guia-body { padding: var(--sp-12) 0; }
.guia-body .prose h2 { font-size: var(--t-2xl); margin-top: var(--sp-10); margin-bottom: var(--sp-4); }
.guia-body .prose h3 { font-size: var(--t-xl); margin-top: var(--sp-8); margin-bottom: var(--sp-3); }
.guia-body .prose p { color: var(--text-3); line-height: 1.8; margin-bottom: var(--sp-5); }
.guia-body .prose strong { color: var(--text-2); }
.guia-body .prose ul { color: var(--text-3); line-height: 1.8; margin-bottom: var(--sp-5); padding-left: var(--sp-6); }
.guia-body .prose li { margin-bottom: var(--sp-2); }
.guia-body .prose blockquote { border-left: 3px solid var(--lime); padding: var(--sp-4) var(--sp-6); background: var(--bg-elevated); border-radius: 0 var(--r-md) var(--r-md) 0; margin: var(--sp-6) 0; color: var(--text-2); }
</style>
@endpush

@section('content')

<section class="guia-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-5)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <a href="{{ route('guias.index') }}" style="color:var(--text-3);text-decoration:none">Guías</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">{{ Str::limit($guia['title'], 50) }}</span>
    </nav>
    <div style="max-width:760px">
      <span style="font-size:2.5rem;display:block;margin-bottom:var(--sp-4)">{{ $guia['icon'] }}</span>
      <h1 style="font-size:clamp(1.75rem,4vw,2.75rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.15;margin-bottom:var(--sp-5)">{{ $guia['title'] }}</h1>
      <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;max-width:640px">{{ $guia['description'] }}</p>
    </div>
  </div>
</section>

<section class="guia-body">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="prose">
          <p>Esta guía te explica en detalle todo lo que necesitas saber sobre el tema indicado, con la normativa vigente en España, los plazos legales y los pasos concretos que debes seguir.</p>

          <h2>Marco legal aplicable</h2>
          <p>La <strong>Ley 50/1980 de Contrato de Seguro (LCS)</strong> es la norma fundamental que regula todos los contratos de seguro en España. Sus artículos principales para las reclamaciones son:</p>
          <ul>
            <li><strong>Art. 18 LCS</strong>: Plazo máximo de 3 meses para resolver</li>
            <li><strong>Art. 20 LCS</strong>: Intereses de mora del 20% anual</li>
            <li><strong>Art. 23 LCS</strong>: Plazos de prescripción (2 años daños, 5 años vida)</li>
            <li><strong>Art. 3 LCS</strong>: Nulidad de cláusulas no comunicadas</li>
            <li><strong>Art. 38 LCS</strong>: Derecho al peritaje contradictorio</li>
          </ul>

          <blockquote>La DGSFP es el organismo supervisor con poder sancionador sobre las aseguradoras. Presentar una reclamación ante la DGSFP es completamente gratuito.</blockquote>

          <h2>Proceso paso a paso</h2>
          <p>El proceso de reclamación a una aseguradora sigue siempre el mismo esquema legal, independientemente del ramo o tipo de siniestro. Siguiendo estos pasos en orden, maximizas las posibilidades de éxito extrajudicial y, si llega al juzgado, tienes toda la documentación en orden.</p>

          <h2>Generar tu carta de reclamación</h2>
          <p>Reclama genera automáticamente la carta de reclamación con los artículos de la LCS aplicables a tu caso, adaptada a tu aseguradora y con los datos del siniestro. En menos de 90 segundos tienes un documento listo para enviar por burofax.</p>
        </div>

        <div style="margin-top:var(--sp-12)">
          @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Generar tu reclamación ahora', 'sub' => 'Carta con base legal en 90 segundos. Personalizada para tu aseguradora.'])
        </div>

        <div style="margin-top:var(--sp-10);padding-top:var(--sp-8);border-top:1px solid var(--border)">
          <h3 style="margin-bottom:var(--sp-5)">Artículos del blog relacionados</h3>
          <div style="display:flex;flex-direction:column;gap:var(--sp-3)">
            @foreach(array_slice(\App\Http\Controllers\BlogController::$articles, 0, 3) as $a)
            <a href="{{ route('blog.show', $a['slug']) }}" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--r-lg);padding:var(--sp-4);text-decoration:none;display:flex;gap:var(--sp-3);align-items:flex-start;transition:border-color var(--t-base-d)">
              <div style="flex-shrink:0;font-size:var(--t-xs);color:var(--lime);font-weight:var(--fw-bold)">{{ $a['read_min'] }}m</div>
              <div>
                <div style="font-size:var(--t-sm);font-weight:var(--fw-medium);color:var(--text);margin-bottom:var(--sp-1)">{{ $a['title'] }}</div>
                <div style="font-size:var(--t-xs);color:var(--text-3)">{{ $a['category'] }}</div>
              </div>
            </a>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div style="position:sticky;top:calc(var(--nav-h) + var(--sp-6))">
          <div style="background:var(--bg-card);border:1px solid var(--lime-border);border-radius:var(--r-xl);padding:var(--sp-6);margin-bottom:var(--sp-5)">
            <h3 style="font-size:var(--t-base);margin-bottom:var(--sp-3)">¿Tienes un siniestro pendiente?</h3>
            <p style="font-size:var(--t-sm);color:var(--text-3);margin-bottom:var(--sp-4);line-height:1.65">Genera tu carta de reclamación con base legal española en 90 segundos.</p>
            <a href="{{ route('claim.create') }}" class="btn btn-primary w-100">Reclamar ahora →</a>
          </div>
          <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-6)">
            <h3 style="font-size:var(--t-base);margin-bottom:var(--sp-4)">Otras guías</h3>
            @foreach(\App\Http\Controllers\BlogController::$guias as $g)
            @if($g['slug'] !== $guia['slug'])
            <a href="{{ route('guias.show', $g['slug']) }}" style="display:flex;gap:var(--sp-3);padding:var(--sp-3) 0;border-bottom:1px solid var(--border);text-decoration:none">
              <span>{{ $g['icon'] }}</span>
              <span style="font-size:var(--t-sm);color:var(--text-2)">{{ Str::limit($g['title'], 45) }}</span>
            </a>
            @endif
            @endforeach
            <a href="{{ route('guias.index') }}" style="display:block;margin-top:var(--sp-4);font-size:var(--t-xs);color:var(--lime)">Ver todas las guías →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
