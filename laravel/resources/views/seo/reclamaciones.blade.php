@extends('layouts.app')

@section('title', 'Reclamaciones contra Aseguradoras sin Complicaciones — Reclama')
@section('meta-description', 'Reclamaciones contra aseguradoras de forma rápida y con base legal. Seguro de hogar, coche, vida, salud, desastres naturales y seguros de fallecidos. Guías y herramientas gratuitas.')
@section('canonical', route('seo.reclamaciones'))
@section('og-title', 'Reclamaciones contra Aseguradoras — Hub Completo 2024')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LegalService",
  "name": "Reclama",
  "description": "Plataforma para generar reclamaciones formales contra aseguradoras con base legal española.",
  "url": "{{ route('home') }}",
  "areaServed": { "@type": "Country", "name": "España" },
  "serviceType": "Reclamaciones a aseguradoras"
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
.ramo-card { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--r-xl); padding: var(--sp-8); text-decoration: none; display: block; transition: border-color var(--t-base-d), transform var(--t-base-d); }
.ramo-card:hover { border-color: var(--lime-border); transform: translateY(-3px); }
.ramo-card .rc-icon { font-size: 2rem; margin-bottom: var(--sp-4); display: block; }
.ramo-card .rc-title { font-size: var(--t-xl); font-weight: var(--fw-bold); color: var(--text); margin-bottom: var(--sp-2); }
.ramo-card .rc-desc { font-size: var(--t-sm); color: var(--text-3); line-height: 1.65; margin-bottom: var(--sp-4); }
.ramo-card .rc-tags { display: flex; flex-wrap: wrap; gap: var(--sp-2); }
.ramo-card .rc-tag { font-size: var(--t-xs); background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-full); padding: .15rem .6rem; color: var(--text-3); }
.kw-section p { color: var(--text-3); line-height: 1.85; }
.kw-section strong { color: var(--text-2); }
</style>
@endpush

@section('content')

<section class="lp-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-6)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">Reclamaciones</span>
    </nav>
    <div style="max-width:760px">
      <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:var(--fw-bold);letter-spacing:-.035em;line-height:1.08;margin-bottom:var(--sp-5)">
        Reclamaciones contra aseguradoras sin complicaciones
      </h1>
      <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-8);max-width:620px">
        Guías completas con base legal española para reclamar a tu seguro de <strong style="color:var(--text-2)">hogar, coche, vida, salud</strong>, por <strong style="color:var(--text-2)">desastres naturales</strong> y para encontrar <strong style="color:var(--text-2)">seguros de fallecidos no reclamados</strong>. Herramientas gratuitas y carta de reclamación en 90 segundos.
      </p>
      <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3)">
        <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Generar reclamación →</a>
        <a href="{{ route('viability.show') }}" class="btn btn-secondary btn-lg">Analizar mi caso</a>
      </div>
    </div>
  </div>
</section>

{{-- RAMOS --}}
<section class="lp-section">
  <div class="container">
    <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Por tipo de seguro</span>
    <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Selecciona tu tipo de reclamación</h2>
    <div class="row g-4">
      @foreach([
        ['🏠','Seguro de hogar',route('seo.hogar'),'Goteras, daños por agua, incendio, robo, fenómenos atmosféricos y coberturas denegadas.',['Daños por agua','Incendio','Robo','Goteras']],
        ['🚗','Seguro de coche',route('seo.coche'),'Accidentes, robo, peritación insuficiente, pérdida total y daños no cubiertos por la póliza.',['Accidente','Robo','Peritación','Pérdida total']],
        ['❤️','Seguro de vida',route('seo.vida'),'Fallecimiento, invalidez permanente, enfermedad grave y beneficiarios que no cobran.',['Fallecimiento','Invalidez','Beneficiarios']],
        ['🏥','Seguro de salud',route('seo.salud'),'Rechazo de tratamiento, demora en autorización, facturación incorrecta y altas prematuras.',['Tratamiento denegado','Facturación','Autorización']],
        ['🔍','Seguros de fallecidos',route('seo.fallecidos'),'Cómo encontrar seguros de vida no reclamados de un familiar fallecido mediante el RCSCF.',['RCSCF','Herencia','Vida no reclamado']],
        ['🌪️','Desastres naturales',route('seo.desastres'),'DANA, inundaciones, granizo y tormentas. Seguro privado y Consorcio de Compensación de Seguros.',['DANA','Inundación','Granizo','CCS']],
      ] as [$icon, $title, $route, $desc, $tags])
      <div class="col-md-6 col-lg-4">
        <a href="{{ $route }}" class="ramo-card">
          <span class="rc-icon">{{ $icon }}</span>
          <div class="rc-title">{{ $title }}</div>
          <p class="rc-desc">{{ $desc }}</p>
          <div class="rc-tags">
            @foreach($tags as $tag)<span class="rc-tag">{{ $tag }}</span>@endforeach
            <span class="rc-tag" style="color:var(--lime);border-color:var(--lime-border)">Ver guía →</span>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0; background: var(--bg-section); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Generar mi reclamación ahora', 'sub' => 'Carta formal con base legal española. Sin abogado. Sin esperas.'])
  </div>
</section>

{{-- PROCESO GENERAL --}}
<section class="lp-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso general</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Cómo funciona una reclamación a una aseguradora</h2>
        <p style="color:var(--text-3);line-height:1.75">Independientemente del tipo de seguro, el proceso de reclamación sigue siempre la misma estructura legal establecida en la <strong style="color:var(--text-2)">Ley 50/1980 de Contrato de Seguro (LCS)</strong>.</p>
      </div>
      <div class="col-lg-7">
        @foreach([
          ['Comunicación del siniestro','Notificar a la aseguradora en el plazo establecido en la póliza (generalmente 7 días). Por escrito, con acuse de recibo.'],
          ['Documentar los daños','Fotografías, facturas, informes técnicos, partes policiales. Cuanta más documentación, más difícil es rechazar o infravalorar.'],
          ['Esperar la respuesta de la aseguradora','Tiene 3 meses para pagar o rechazar motivadamente (art. 18 LCS). Si no lo hace, incurre en mora.'],
          ['Revisar la oferta y negociar','Si la oferta es insuficiente, solicita revisión. Compara con el coste real documentado y con la jurisprudencia.'],
          ['Carta formal de reclamación','Si no hay acuerdo extrajudicial, carta formal con base legal, plazos y consecuencias. Es el paso previo a la DGSFP.'],
          ['Escalada: DGSFP o juzgado','Si la aseguradora no responde, la DGSFP tiene poder sancionador. El juzgado es el último recurso, pero con los intereses del art. 20 LCS puede merecer la pena.'],
        ] as [$title, $body])
        <div style="display:flex;gap:var(--sp-5);padding:var(--sp-5) 0;border-bottom:1px solid var(--border);align-items:flex-start">
          <div style="flex-shrink:0;width:.5rem;height:.5rem;border-radius:50%;background:var(--lime);margin-top:.5rem"></div>
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

{{-- SEO KEYWORD SECTION --}}
<section class="lp-section lp-section--alt kw-section">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Guía</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-6)">Todo lo que necesitas saber sobre reclamaciones a aseguradoras en España</h2>
      <p>Una <strong>reclamación a la aseguradora</strong> es el proceso formal por el cual el <strong>asegurado o beneficiario</strong> exige a la compañía de seguros el cumplimiento de sus obligaciones contractuales: el pago de la <strong>indemnización</strong> pactada en la <strong>póliza</strong> cuando se produce el <strong>siniestro</strong> cubierto.</p>
      <p style="margin-top:var(--sp-4)">La <strong>Ley 50/1980 de Contrato de Seguro (LCS)</strong> es la norma principal que regula la relación entre asegurado y aseguradora en España. Sus artículos más relevantes para las reclamaciones son el art. 18 (plazos de pago), el art. 20 (intereses de mora del 20%), el art. 38 (peritaje contradictorio) y el art. 3 (cláusulas limitativas nulas si no están aceptadas).</p>
      <p style="margin-top:var(--sp-4)">Los principales motivos de reclamación son: <strong>cobertura denegada</strong> (la aseguradora no reconoce el siniestro como cubierto), <strong>indemnización insuficiente</strong> (el importe ofrecido es inferior al coste real), <strong>demora en el pago</strong> (no se liquida en el plazo legal) y <strong>exclusiones abusivas</strong> (cláusulas que no fueron comunicadas ni aceptadas expresamente).</p>
      <p style="margin-top:var(--sp-4)">La <strong>Dirección General de Seguros y Fondos de Pensiones (DGSFP)</strong> es el organismo supervisor que puede sancionar a las compañías aseguradoras que incumplen la normativa. Presentar una reclamación ante la DGSFP es gratuito y tiene un plazo de resolución de 4 meses.</p>
    </div>
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-6">
        <h2 style="margin-bottom:var(--sp-6)">Artículos del blog más leídos</h2>
        @foreach(array_slice(\App\Http\Controllers\BlogController::$articles, 0, 4) as $a)
        <a href="{{ route('blog.show', $a['slug']) }}" style="display:flex;gap:var(--sp-4);padding:var(--sp-4) 0;border-bottom:1px solid var(--border);text-decoration:none;align-items:flex-start">
          <div style="flex-shrink:0;background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--r-sm);padding:.2rem .5rem;font-size:var(--t-xs);color:var(--text-3);white-space:nowrap">{{ $a['read_min'] }} min</div>
          <div>
            <div style="font-size:var(--t-sm);font-weight:var(--fw-medium);color:var(--text);margin-bottom:var(--sp-1)">{{ $a['title'] }}</div>
            <div style="font-size:var(--t-xs);color:var(--text-3)">{{ $a['category'] }}</div>
          </div>
        </a>
        @endforeach
        <a href="{{ route('blog.index') }}" class="btn btn-secondary btn-sm mt-4">Ver todos los artículos →</a>
      </div>
      <div class="col-lg-6">
        <h2 style="margin-bottom:var(--sp-6)">Guías especializadas</h2>
        @foreach(\App\Http\Controllers\BlogController::$guias as $g)
        <a href="{{ route('guias.show', $g['slug']) }}" style="display:flex;gap:var(--sp-4);padding:var(--sp-4) 0;border-bottom:1px solid var(--border);text-decoration:none;align-items:flex-start">
          <span style="font-size:1.25rem;flex-shrink:0">{{ $g['icon'] }}</span>
          <div>
            <div style="font-size:var(--t-sm);font-weight:var(--fw-medium);color:var(--text);margin-bottom:var(--sp-1)">{{ $g['title'] }}</div>
            <div style="font-size:var(--t-xs);color:var(--text-3)">{{ Str::limit($g['description'], 80) }}</div>
          </div>
        </a>
        @endforeach
        <a href="{{ route('guias.index') }}" class="btn btn-secondary btn-sm mt-4">Ver todas las guías →</a>
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-16) 0; background: var(--bg-section); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Genera tu reclamación ahora', 'sub' => 'Carta formal con base legal española, jurisprudencia CENDOJ y datos de tu aseguradora. En menos de 90 segundos.'])
  </div>
</section>

@endsection
