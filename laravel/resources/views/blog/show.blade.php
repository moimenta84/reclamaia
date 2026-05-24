@extends('layouts.app')

@section('title', $article['title'] . ' — Reclama Blog')
@section('meta-description', $article['description'])
@section('canonical', route('blog.show', $article['slug']))
@section('og-title', $article['title'])
@section('og-description', $article['description'])

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ $article['title'] }}",
  "description": "{{ $article['description'] }}",
  "datePublished": "{{ $article['date'] }}",
  "author": { "@type": "Organization", "name": "Reclama" },
  "publisher": {
    "@type": "Organization",
    "name": "Reclama",
    "url": "{{ route('home') }}"
  }
}
</script>
@endpush

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.article-hero { background: var(--bg-section); border-bottom: 1px solid var(--border); padding: var(--sp-16) 0 var(--sp-12); }
.article-body { padding: var(--sp-12) 0; }
.article-body .prose { max-width: 720px; }
.article-body .prose h2 { font-size: var(--t-2xl); margin-top: var(--sp-10); margin-bottom: var(--sp-4); }
.article-body .prose h3 { font-size: var(--t-xl); margin-top: var(--sp-8); margin-bottom: var(--sp-3); }
.article-body .prose p { color: var(--text-3); line-height: 1.8; margin-bottom: var(--sp-5); }
.article-body .prose strong { color: var(--text-2); }
.article-body .prose ul { color: var(--text-3); line-height: 1.8; margin-bottom: var(--sp-5); padding-left: var(--sp-6); }
.article-body .prose li { margin-bottom: var(--sp-2); }
.article-body .prose blockquote { border-left: 3px solid var(--lime); padding: var(--sp-4) var(--sp-6); background: var(--bg-elevated); border-radius: 0 var(--r-md) var(--r-md) 0; margin: var(--sp-6) 0; color: var(--text-2); font-style: normal; }
.rel-card { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--r-lg); padding: var(--sp-5); text-decoration: none; display: block; transition: border-color var(--t-base-d); }
.rel-card:hover { border-color: var(--lime-border); }
</style>
@endpush

@section('content')

<section class="article-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-5)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <a href="{{ route('blog.index') }}" style="color:var(--text-3);text-decoration:none">Blog</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page" style="color:var(--text-3)">{{ Str::limit($article['title'], 50) }}</span>
    </nav>
    <div style="max-width:760px">
      <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">
        {{ $article['category'] }}
      </div>
      <h1 style="font-size:clamp(1.75rem,4vw,2.75rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.15;margin-bottom:var(--sp-5)">{{ $article['title'] }}</h1>
      <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-6);max-width:640px">{{ $article['description'] }}</p>
      <div style="display:flex;gap:var(--sp-4);font-size:var(--t-xs);color:var(--text-4)">
        <span>{{ \Carbon\Carbon::parse($article['date'])->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
        <span>·</span>
        <span>{{ $article['read_min'] }} min de lectura</span>
        <span>·</span>
        <span>Reclama</span>
      </div>
    </div>
  </div>
</section>

<section class="article-body">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="prose">

          {{-- Article body placeholder - in production this comes from a CMS/DB --}}
          <p>Este artículo te explica paso a paso cómo actuar en el caso concreto descrito en el título. El contenido está basado en la <strong>Ley 50/1980 de Contrato de Seguro (LCS)</strong>, la normativa de la DGSFP y jurisprudencia reciente del Tribunal Supremo y Audiencias Provinciales.</p>

          <h2>Lo primero que tienes que saber</h2>
          <p>Cuando una aseguradora no cumple con sus obligaciones, tienes varios mecanismos legales para reclamar: la <strong>carta formal de reclamación</strong>, el <strong>Defensor del Asegurado</strong>, la <strong>DGSFP</strong> y, en último extremo, los tribunales. La mayoría de los casos se resuelven en la fase extrajudicial.</p>

          <blockquote>
            El artículo 18 de la LCS obliga a la aseguradora a pagar o rechazar motivadamente en el plazo de 3 meses. Si no lo hace, incurre en mora y debe intereses del 20% anual.
          </blockquote>

          <h2>Documentación que necesitas</h2>
          <ul>
            <li>Comunicación del siniestro (copia o acuse de recibo)</li>
            <li>Documentación acreditativa de los daños (fotografías, informes, facturas)</li>
            <li>Copia de la póliza y sus condiciones particulares</li>
            <li>Respuesta de la aseguradora (si la hay)</li>
            <li>Presupuesto de reparación o valoración independiente</li>
          </ul>

          <h2>Los plazos legales</h2>
          <p>El <strong>artículo 23 LCS</strong> establece los plazos de prescripción: 2 años para seguros de daños, 5 años para seguros de vida. No esperes: presenta la reclamación cuanto antes para no perder derechos.</p>

          <h2>Cómo generar tu carta de reclamación</h2>
          <p>La carta de reclamación debe incluir: identificación del asegurado, número de póliza, descripción del siniestro, base legal aplicable (artículos LCS relevantes), importe reclamado con justificación y plazo para responder.</p>
          <p>Reclama genera esta carta automáticamente en menos de 90 segundos, con los artículos de la LCS aplicables a tu caso específico y la jurisprudencia relevante del TS y AP.</p>

        </div>

        <div style="margin-top:var(--sp-12)">
          @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Genera tu reclamación ahora', 'sub' => 'Carta con base legal en 90 segundos. Personalizada para tu aseguradora.'])
        </div>
      </div>

      <div class="col-lg-4">
        <div style="position:sticky;top:calc(var(--nav-h) + var(--sp-6))">
          <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-6);margin-bottom:var(--sp-5)">
            <h3 style="font-size:var(--t-base);margin-bottom:var(--sp-4)">Artículos relacionados</h3>
            <div style="display:flex;flex-direction:column;gap:var(--sp-3)">
              @foreach($related as $rel)
              <a href="{{ route('blog.show', $rel['slug']) }}" class="rel-card">
                <div style="font-size:var(--t-xs);color:var(--lime);margin-bottom:var(--sp-1)">{{ $rel['category'] }}</div>
                <div style="font-size:var(--t-sm);font-weight:var(--fw-medium);color:var(--text);line-height:1.3">{{ $rel['title'] }}</div>
              </a>
              @endforeach
            </div>
          </div>
          <div style="background:var(--bg-card);border:1px solid var(--lime-border);border-radius:var(--r-xl);padding:var(--sp-6)">
            <h3 style="font-size:var(--t-base);margin-bottom:var(--sp-3);color:var(--text)">¿Tienes un siniestro pendiente?</h3>
            <p style="font-size:var(--t-sm);color:var(--text-3);margin-bottom:var(--sp-4);line-height:1.65">Genera tu carta de reclamación en 90 segundos con base legal española.</p>
            <a href="{{ route('claim.create') }}" class="btn btn-primary w-100">Reclamar ahora →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
