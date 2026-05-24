@extends('layouts.app')

@section('title', 'Reclamar Daños por Desastres Naturales: DANA, Inundaciones y Granizo')
@section('meta-description', 'Cómo reclamar indemnización por daños de DANA, inundaciones, granizo y tormentas. Seguro de hogar, Consorcio de Compensación de Seguros y plazos para reclamar. Guía legal 2024.')
@section('canonical', route('seo.desastres'))
@section('og-title', 'Reclamar por DANA, Inundaciones y Granizo — Guía Completa 2024')
@section('og-description', 'Guía paso a paso para reclamar daños por desastres naturales en España. DANA, inundaciones, granizo, tormentas. Seguro de hogar vs Consorcio de Compensación de Seguros.')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Qué cubre el Consorcio de Compensación de Seguros por inundaciones?",
      "acceptedAnswer": { "@type": "Answer", "text": "El Consorcio de Compensación de Seguros cubre los daños producidos por inundaciones, terremotos, erupciones volcánicas, tormentas ciclónicas atípicas y otros fenómenos de la naturaleza de carácter extraordinario. Solo actúa si el bien dañado tiene un seguro privado que cubra el mismo riesgo." }
    },
    {
      "@type": "Question",
      "name": "¿Tengo que reclamar al seguro o al Consorcio por daños de DANA?",
      "acceptedAnswer": { "@type": "Answer", "text": "Depende de la cobertura de tu póliza. Muchos seguros de hogar cubren inundaciones ordinarias pero no las extraordinarias (DANA). En ese caso, el Consorcio de Compensación de Seguros es quien paga. La reclamación al CCS se hace a través de tu propia aseguradora." }
    },
    {
      "@type": "Question",
      "name": "¿Cuánto tiempo tengo para reclamar daños por una tormenta o inundación?",
      "acceptedAnswer": { "@type": "Answer", "text": "Al Consorcio de Compensación de Seguros tienes 1 año desde el siniestro para presentar la reclamación. A tu seguro privado, el plazo de prescripción es de 2 años (art. 23 LCS). No esperes: presenta la comunicación de siniestro inmediatamente." }
    },
    {
      "@type": "Question",
      "name": "¿El seguro del coche cubre los daños de inundación?",
      "acceptedAnswer": { "@type": "Answer", "text": "Si tu póliza es a todo riesgo o incluye cobertura de fenómenos atmosféricos, tu aseguradora cubre los daños. Si la inundación es un fenómeno extraordinario (DANA), el Consorcio de Compensación de Seguros complementa o sustituye la cobertura del seguro privado." }
    }
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Inicio", "item": "{{ route('home') }}" },
    { "@type": "ListItem", "position": 2, "name": "Reclamaciones", "item": "{{ route('seo.reclamaciones') }}" },
    { "@type": "ListItem", "position": 3, "name": "Desastres naturales", "item": "{{ route('seo.desastres') }}" }
  ]
}
</script>
@endpush

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.lp-hero { background: var(--bg-section); border-bottom: 1px solid var(--border); padding: var(--sp-20) 0 var(--sp-16); }
.lp-section { padding: var(--sp-20) 0; }
.lp-section--alt { background: var(--bg-section); }
.step-item { display: flex; gap: var(--sp-5); align-items: flex-start; padding: var(--sp-6) 0; border-bottom: 1px solid var(--border); }
.step-item:last-child { border-bottom: none; }
.step-num-lg { flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: var(--r-full); background: var(--lime-dim); border: 1px solid var(--lime-border); color: var(--lime); font-weight: var(--fw-bold); font-size: var(--t-sm); display: flex; align-items: center; justify-content: center; }
.desastre-card { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--r-lg); padding: var(--sp-6); }
.faq-item { border-bottom: 1px solid var(--border); }
.faq-trigger { width: 100%; background: none; border: none; text-align: left; padding: var(--sp-5) 0; display: flex; justify-content: space-between; align-items: center; gap: var(--sp-4); cursor: pointer; color: var(--text); font-size: var(--t-base); font-weight: var(--fw-medium); }
.faq-trigger:hover { color: var(--lime); }
.faq-body { display: none; padding-bottom: var(--sp-5); color: var(--text-3); font-size: var(--t-base); line-height: 1.75; }
.interlinking { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: var(--sp-4); }
.ilink-card { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--r-lg); padding: var(--sp-5); text-decoration: none; display: block; transition: border-color var(--t-base-d), transform var(--t-base-d); }
.ilink-card:hover { border-color: var(--lime-border); transform: translateY(-2px); }
.ilink-card .il-icon { font-size: 1.5rem; margin-bottom: var(--sp-3); display: block; }
.ilink-card .il-title { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); margin-bottom: var(--sp-1); }
.ilink-card .il-sub { font-size: var(--t-xs); color: var(--text-3); }
</style>
@endpush

@section('content')

<section class="lp-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-6)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <a href="{{ route('seo.reclamaciones') }}" style="color:var(--text-3);text-decoration:none">Reclamaciones</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">Desastres naturales</span>
    </nav>
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">🌪️ Desastres naturales</div>
        <h1 style="font-size:clamp(1.875rem,4.5vw,3rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.1;margin-bottom:var(--sp-5)">
          Reclamar daños por DANA, inundaciones y granizo: seguro vs Consorcio
        </h1>
        <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-6);max-width:600px">
          Los desastres naturales en España tienen un sistema de cobertura dual: <strong style="color:var(--text-2)">seguro privado</strong> para fenómenos ordinarios y <strong style="color:var(--text-2)">Consorcio de Compensación de Seguros (CCS)</strong> para fenómenos extraordinarios. Te explicamos quién paga qué y cómo reclamar.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3);margin-bottom:var(--sp-8)">
          <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Reclamar ahora →</a>
          <a href="#quien-paga" class="btn btn-secondary btn-lg">¿Seguro o Consorcio?</a>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-5);font-size:var(--t-xs);color:var(--text-3)">
          <span>✓ DANA 2024 cubierta</span>
          <span>✓ CCS + seguro privado</span>
          <span>✓ Carta lista en 90 segundos</span>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-8)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:var(--sp-5)">Fenómenos cubiertos</div>
          @foreach([
            ['🌊','Inundaciones (ordinarias y DANA)'],
            ['🌨️','Granizo y nieve'],
            ['🌪️','Viento huracanado (>120 km/h)'],
            ['⚡','Rayo y tormentas eléctricas'],
            ['🌋','Terremotos y erupciones'],
            ['🧊','Heladas y daños por frío extremo'],
            ['🌧️','Lluvias torrenciales'],
          ] as [$icon, $item])
          <div style="display:flex;align-items:center;gap:var(--sp-3);padding:var(--sp-3) 0;border-bottom:1px solid var(--border);font-size:var(--t-sm)">
            <span>{{ $icon }}</span><span>{{ $item }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0;">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar daños por DANA o inundación', 'sub' => 'Carta con la normativa del CCS y el art. 18 LCS. Lista en 90 segundos.'])
  </div>
</section>

<section class="lp-section" id="quien-paga">
  <div class="container">
    <div style="text-align:center;max-width:620px;margin:0 auto var(--sp-12)">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Cobertura</span>
      <h2 style="margin-top:var(--sp-3)">¿Quién paga: tu seguro o el Consorcio de Compensación de Seguros?</h2>
      <p style="color:var(--text-3);line-height:1.75">Esta es la pregunta clave. La respuesta depende del tipo de fenómeno y de lo que cubra tu póliza.</p>
    </div>
    <div class="row g-4">
      @foreach([
        ['🏢','Tu seguro privado','Fenómenos atmosféricos ordinarios','Viento inferior a 120 km/h, granizo leve, lluvia ordinaria, pequeñas inundaciones locales. Cubiertos si tu póliza incluye la cobertura de fenómenos atmosféricos.','Plazo: 2 años (art. 23 LCS)'],
        ['🏛️','Consorcio de Compensación de Seguros','Fenómenos extraordinarios','DANA, inundaciones extraordinarias, terremotos, erupciones volcánicas, tsunamis, tormentas ciclónicas con viento >120 km/h. Solo actúa si tienes póliza del mismo riesgo.','Plazo: 1 año desde el siniestro'],
        ['🤝','Ambos en coordinación','Situaciones mixtas','Si tu póliza cubre el riesgo ordinario y el fenómeno es extraordinario, el seguro privado paga lo ordinario y el CCS lo extraordinario. En la práctica, el CCS suele pagar el total.','El CCS liquida desde tu aseguradora'],
      ] as [$icon, $title, $sub, $desc, $plazo])
      <div class="col-lg-4">
        <div class="desastre-card" style="height:100%">
          <div style="font-size:1.5rem;margin-bottom:var(--sp-3)">{{ $icon }}</div>
          <h3 style="font-size:var(--t-base);font-weight:var(--fw-bold);margin-bottom:var(--sp-1)">{{ $title }}</h3>
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);color:var(--lime);margin-bottom:var(--sp-3)">{{ $sub }}</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin-bottom:var(--sp-4);line-height:1.65">{{ $desc }}</p>
          <div style="font-size:var(--t-xs);background:var(--bg-card);padding:var(--sp-3);border-radius:var(--r-md);color:var(--text-3)">⏱️ {{ $plazo }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Cómo reclamar daños por desastre natural</h2>
        <p style="color:var(--text-3);line-height:1.75">Actúa rápido: los plazos son más cortos que en siniestros ordinarios y la documentación inicial es crítica.</p>
        <a href="{{ route('claim.create') }}" class="btn btn-primary mt-4">Generar mi reclamación →</a>
      </div>
      <div class="col-lg-7">
        @foreach([
          ['1','Documenta los daños inmediatamente','Fotografías y vídeo con geolocalización antes de limpiar o reparar nada. Conserva todos los objetos dañados que puedas. La documentación gráfica es la prueba más valiosa.'],
          ['2','Comunica el siniestro a tu aseguradora','Por escrito (email o burofax). La aseguradora inicia el trámite con el CCS si el fenómeno es extraordinario. No dejes pasar más de 7 días.'],
          ['3','Obtén la declaración de zona catastrófica (si aplica)','El Gobierno puede declarar zona catastrófica y activar ayudas adicionales. Consulta con las administraciones locales.'],
          ['4','Espera la tasación del perito','El perito (de la aseguradora o del CCS) evaluará los daños. Ten preparada tu propia lista de daños con presupuestos de reparación.'],
          ['5','Revisa la tasación y reclama si es baja','Compara la tasación con los presupuestos reales. Si hay diferencia, envía carta formal. Para el CCS también tienes derecho a perito propio.'],
          ['6','Reclama daños no materiales si los hay','Si hay daños personales o lucro cesante (negocio parado), son indemnizables por separado. Documenta todos los ingresos perdidos.'],
        ] as [$num, $title, $body])
        <div class="step-item">
          <div class="step-num-lg" aria-hidden="true">{{ $num }}</div>
          <div>
            <h3 style="font-size:var(--t-base);font-weight:var(--fw-semibold);margin-bottom:var(--sp-2)">{{ $title }}</h3>
            <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.7">{{ $body }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Generar carta de reclamación por desastre natural', 'sub' => 'Con la normativa del Consorcio de Compensación de Seguros y la LCS. Personalizada en 90 segundos.'])
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Preguntas frecuentes sobre daños por desastres naturales</h2>
      @foreach([
        ['¿Tengo que reclamar al seguro o al Consorcio?','Si el fenómeno es extraordinario (DANA, terremotos, erupciones), es el CCS quien paga. La reclamación se canaliza a través de tu propia aseguradora, que se coordina con el CCS. Si el fenómeno es ordinario (granizo leve, viento normal), es tu seguro privado quien cubre.'],
        ['¿Qué pasa si no tengo seguro de hogar contratado?','El Consorcio solo actúa si tienes una póliza privada que cubra el mismo riesgo. Sin seguro privado, no tienes acceso al CCS. En ese caso, podrías acceder a las ayudas públicas del Estado o comunidades autónomas.'],
        ['¿El seguro del coche cubre los daños de la DANA?','Si tu póliza es a todo riesgo o incluye cobertura de fenómenos atmosféricos, tu seguro cubre los daños. Si la DANA es considerada fenómeno extraordinario, el CCS puede complementar la cobertura.'],
        ['¿Cuánto tiempo tengo para reclamar al CCS?','1 año desde el siniestro. Es un plazo más corto que el del seguro privado (2 años). Presenta la comunicación del siniestro cuanto antes, aunque no tengas aún toda la documentación.'],
        ['¿Puedo reclamar el lucro cesante por tener el negocio cerrado?','Si tienes seguro de negocio o seguro multirriesgo con cobertura de lucro cesante, sí. Documenta todos los ingresos dejados de percibir durante el período de paralización.'],
      ] as [$q, $a])
      <div class="faq-item">
        <button class="faq-trigger" aria-expanded="false" onclick="(e=>{const x=e.getAttribute('aria-expanded')==='true';e.setAttribute('aria-expanded',String(!x));e.nextElementSibling.style.display=x?'none':'block'})(this)">
          <span>{{ $q }}</span>
          <span aria-hidden="true" style="flex-shrink:0;font-size:1.25rem;color:var(--lime)">+</span>
        </button>
        <div class="faq-body">{{ $a }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <h2 style="margin-bottom:var(--sp-3)">Guías y reclamaciones relacionadas</h2>
    <p style="color:var(--text-3);margin-bottom:var(--sp-8)">Recursos complementarios:</p>
    <div class="interlinking">
      <a href="{{ route('seo.hogar') }}" class="ilink-card"><span class="il-icon">🏠</span><div class="il-title">Reclamar seguro de hogar</div><div class="il-sub">Daños, goteras, inundaciones →</div></a>
      <a href="{{ route('seo.coche') }}" class="ilink-card"><span class="il-icon">🚗</span><div class="il-title">Reclamar seguro de coche</div><div class="il-sub">Daños por DANA en tu vehículo →</div></a>
      <a href="{{ route('blog.show', 'como-reclamar-indemnizacion-inundacion') }}" class="ilink-card"><span class="il-icon">📖</span><div class="il-title">Reclamar por inundación</div><div class="il-sub">Guía detallada con el CCS →</div></a>
      <a href="{{ route('blog.show', 'cuanto-tarda-reclamacion-aseguradora') }}" class="ilink-card"><span class="il-icon">⏱️</span><div class="il-title">Plazos de las aseguradoras</div><div class="il-sub">Cuándo y cómo presionar →</div></a>
      <a href="{{ route('seo.reclamaciones') }}" class="ilink-card"><span class="il-icon">⚖️</span><div class="il-title">Todas las reclamaciones</div><div class="il-sub">Hub completo →</div></a>
      <a href="{{ route('claim.create') }}" class="ilink-card" style="border-color:var(--lime-border)"><span class="il-icon">⚡</span><div class="il-title">Generar reclamación ahora</div><div class="il-sub">Carta lista en 90 segundos →</div></a>
    </div>
  </div>
</section>

<section style="padding: var(--sp-16) 0; background: var(--bg); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Reclamar daños por DANA o desastre natural', 'sub' => 'Carta con la normativa del CCS, LCS y jurisprudencia aplicable. Generada en menos de 90 segundos.'])
  </div>
</section>

@endsection
