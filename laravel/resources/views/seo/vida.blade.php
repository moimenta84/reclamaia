@extends('layouts.app')

@section('title', 'Reclamar Seguro de Vida: Fallecimiento, Invalidez y Beneficiarios')
@section('meta-description', 'Cómo reclamar el seguro de vida en España: fallecimiento, invalidez permanente, enfermedad grave. Quién cobra, qué documentos necesitas y plazos legales. Guía 2024.')
@section('canonical', route('seo.vida'))

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Quién puede cobrar el seguro de vida?",
      "acceptedAnswer": { "@type": "Answer", "text": "Los beneficiarios designados en la póliza tienen prioridad absoluta. Si no hay beneficiarios designados, corresponde a los herederos legales. El beneficiario puede reclamar directamente a la aseguradora sin necesidad de que intervengan los herederos (art. 83 LCS)." }
    },
    {
      "@type": "Question",
      "name": "¿Cuánto tarda el pago del seguro de vida?",
      "acceptedAnswer": { "@type": "Answer", "text": "La aseguradora tiene 3 meses desde que el beneficiario acredita su derecho para pagar (art. 18 LCS). Si no paga en ese plazo, incurre en mora y debe intereses del 20% anual (art. 20 LCS)." }
    }
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
      <span aria-current="page">Seguro de vida</span>
    </nav>
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">❤️ Seguro de vida</div>
        <h1 style="font-size:clamp(1.875rem,4.5vw,3rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.1;margin-bottom:var(--sp-5)">
          Cómo reclamar el seguro de vida: fallecimiento, invalidez y beneficiarios que no cobran
        </h1>
        <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-8);max-width:600px">
          Guía completa con los artículos exactos de la <strong style="color:var(--text-2)">Ley de Contrato de Seguro</strong> que protegen al beneficiario. Quién puede cobrar, qué documentos necesitas y qué pasa si la aseguradora demora el pago.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3);margin-bottom:var(--sp-8)">
          <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Reclamar seguro de vida →</a>
          <a href="{{ route('seo.fallecidos') }}" class="btn btn-secondary btn-lg">Buscar seguros del fallecido</a>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-8)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:var(--sp-5)">Coberturas que reclamamos</div>
          @foreach(['🪦 Fallecimiento por cualquier causa','♿ Invalidez permanente total o absoluta','🏥 Enfermedad grave o incapacidad','⏳ Beneficiarios no informados del seguro','❌ Capital denegado sin justificación','💸 Demora injustificada en el pago'] as $item)
          <div style="display:flex;align-items:center;gap:var(--sp-3);padding:var(--sp-3) 0;border-bottom:1px solid var(--border);font-size:var(--t-sm)">
            <span style="color:var(--lime)">✓</span><span>{{ $item }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0;">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar el seguro de vida ahora', 'sub' => 'Carta formal con art. 83, 18 y 20 LCS. Personalizada para tu aseguradora en 90 segundos.'])
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Pasos para cobrar el seguro de vida</h2>
        @foreach([
          ['1','Localizar la póliza','Busca entre los documentos del fallecido o consulta el RCSCF (Registro de Contratos de Seguros de Cobertura de Fallecimiento). El registro puede consultarse hasta 5 años después del fallecimiento.'],
          ['2','Obtener el certificado de defunción','En el Registro Civil. Debe ser certificado literal (no extracto). Es imprescindible para cualquier trámite posterior.'],
          ['3','Acreditar tu condición de beneficiario','Si estás designado en la póliza, con tu DNI es suficiente. Si no hay beneficiarios designados, necesitas certificado de últimas voluntades y testamento o declaración de herederos.'],
          ['4','Notificar a la aseguradora','Por escrito (burofax) con toda la documentación. La aseguradora tiene 3 meses para pagar (art. 18 LCS).'],
          ['5','Verificar que el importe sea correcto','La aseguradora debe pagar el capital asegurado más los intereses generados si el fallecimiento ocurrió hace tiempo. Comprueba la tabla de valores garantizados de la póliza.'],
          ['6','Reclamar si hay demora o rechazo','Si en 3 meses no hay pago, la aseguradora incurre en mora del 20% anual. Envía carta formal de reclamación.'],
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
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Base legal</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Normativa que te protege</h2>
        @foreach([
          ['Art. 83 LCS','El beneficiario puede reclamar directamente el capital asegurado a la aseguradora, con independencia de los herederos del tomador. Este derecho es directo e independiente de la herencia.'],
          ['Art. 18 LCS','La aseguradora tiene 3 meses desde que el beneficiario presenta la documentación acreditativa para pagar o rechazar con justificación. Pasado el plazo, mora automática.'],
          ['Art. 20 LCS','Intereses de mora del 20% anual sobre el capital asegurado si la aseguradora retrasa injustificadamente el pago. Estos intereses son adicionales al capital principal.'],
          ['Art. 23 LCS','Prescripción del seguro de vida: 5 años desde que el beneficiario tuvo conocimiento del fallecimiento y la existencia del seguro. No esperes.'],
          ['Art. 3 LCS','Las exclusiones de la póliza deben estar destacadas y aceptadas expresamente por el tomador. Las exclusiones no comunicadas son nulas.'],
        ] as [$art, $desc])
        <div style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--r-lg);padding:var(--sp-5);margin-bottom:var(--sp-4)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);color:var(--lime);margin-bottom:var(--sp-2)">{{ $art }}</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0; background: var(--bg-section); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar el seguro de vida', 'sub' => 'Carta con art. 83, 18 y 20 LCS. Personalizada por aseguradora. Lista en 90 segundos.'])
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Preguntas frecuentes sobre seguros de vida</h2>
      @foreach([
        ['¿Quién puede cobrar el seguro de vida?','Los beneficiarios designados en la póliza, con prioridad absoluta. Si no hay beneficiarios o han premuerto, corresponde a los herederos legales. El beneficiario tiene un derecho directo y autónomo (art. 83 LCS), independiente de la herencia.'],
        ['¿Cuánto tarda la aseguradora en pagar?','3 meses desde que el beneficiario presenta la documentación acreditativa (art. 18 LCS). Si no paga en ese plazo, incurre en mora del 20% anual (art. 20 LCS).'],
        ['¿Puede la aseguradora negar el pago si el fallecimiento es por accidente?','Solo si existe exclusión específica válidamente comunicada. Los accidentes suelen estar cubiertos. Si hay exclusión, debe estar destacada y aceptada expresamente (art. 3 LCS).'],
        ['¿El capital del seguro de vida forma parte de la herencia?','Si los beneficiarios son personas distintas a los herederos, no. El capital va directamente al beneficiario designado y no tributa por Impuesto de Sucesiones como parte de la herencia (aunque sí puede tributar por ISD en concepto de seguro contratado para caso de muerte).'],
        ['¿Cuánto tiempo tengo para reclamar?','5 años desde que el beneficiario tuvo conocimiento del fallecimiento y la existencia del seguro (art. 23 LCS). No esperes: los seguros más antiguos pueden ser difíciles de rastrear pasado ese plazo.'],
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

<section class="lp-section">
  <div class="container">
    <h2 style="margin-bottom:var(--sp-3)">Guías relacionadas</h2>
    <p style="color:var(--text-3);margin-bottom:var(--sp-8)">Recursos complementarios para seguros de vida:</p>
    <div class="interlinking">
      <a href="{{ route('seo.fallecidos') }}" class="ilink-card"><span class="il-icon">🔍</span><div class="il-title">Buscar seguros del fallecido</div><div class="il-sub">RCSCF y cómo localizar pólizas →</div></a>
      <a href="{{ route('blog.show', 'seguros-vida-no-reclamados-espana') }}" class="ilink-card"><span class="il-icon">📖</span><div class="il-title">Seguros no reclamados</div><div class="il-sub">Datos y cómo encontrarlos →</div></a>
      <a href="{{ route('blog.show', 'plazos-prescripcion-seguros-espana') }}" class="ilink-card"><span class="il-icon">⏱️</span><div class="il-title">Plazos de prescripción</div><div class="il-sub">Cuánto tiempo tienes →</div></a>
      <a href="{{ route('seo.reclamaciones') }}" class="ilink-card"><span class="il-icon">⚖️</span><div class="il-title">Todas las reclamaciones</div><div class="il-sub">Hub completo →</div></a>
    </div>
  </div>
</section>

<section style="padding: var(--sp-16) 0; background: var(--bg-section); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Reclamar el seguro de vida ahora', 'sub' => 'Carta formal con art. 83, 18 y 20 LCS, personalizada por aseguradora. En menos de 90 segundos.'])
  </div>
</section>

@endsection
