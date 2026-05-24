@extends('layouts.app')

@section('title', 'Cómo Saber si un Fallecido Tenía Seguros de Vida No Reclamados')
@section('meta-description', 'Cómo buscar seguros de vida no reclamados de un fallecido. Consulta el Registro de Contratos de Seguros de Cobertura de Fallecimiento (RCSCF). Herencia, seguros ocultos y plazos para reclamar.')
@section('canonical', route('seo.fallecidos'))
@section('og-title', 'Seguros de Fallecidos: Cómo Encontrar Seguros de Vida No Reclamados')
@section('og-description', 'Guía completa para buscar seguros de vida no reclamados de un familiar fallecido. RCSCF, Registro de Seguros, herencia y plazos legales 2024.')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Cómo saber si un fallecido tenía seguro de vida?",
      "acceptedAnswer": { "@type": "Answer", "text": "Puedes consultar el Registro de Contratos de Seguros de Cobertura de Fallecimiento (RCSCF) del Ministerio de Justicia. El plazo para solicitarlo es de 5 años desde el fallecimiento. Necesitas el certificado de defunción y, si no eres el titular, acreditar tu condición de beneficiario o heredero." }
    },
    {
      "@type": "Question",
      "name": "¿Cuánto tiempo tengo para reclamar un seguro de vida de un fallecido?",
      "acceptedAnswer": { "@type": "Answer", "text": "El plazo de prescripción del seguro de vida en España es de 5 años desde que el beneficiario tuvo conocimiento del fallecimiento y del seguro (art. 23 LCS). El RCSCF puede consultarse hasta 5 años después del fallecimiento." }
    },
    {
      "@type": "Question",
      "name": "¿Quién puede reclamar el seguro de vida de un fallecido?",
      "acceptedAnswer": { "@type": "Answer", "text": "Los beneficiarios designados en la póliza tienen prioridad absoluta. Si no hay beneficiarios designados o han fallecido previamente, corresponde a los herederos legales según el orden de sucesión del Código Civil." }
    },
    {
      "@type": "Question",
      "name": "¿Qué documentos necesito para reclamar el seguro de vida de un fallecido?",
      "acceptedAnswer": { "@type": "Answer", "text": "Certificado literal de defunción, certificado de últimas voluntades, certificado del RCSCF (o copia de la póliza), DNI del beneficiario/heredero, y en su caso: declaración de herederos o testamento. La aseguradora puede solicitar documentación médica adicional." }
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
    { "@type": "ListItem", "position": 3, "name": "Seguros de fallecidos", "item": "{{ route('seo.fallecidos') }}" }
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
.info-box { background: var(--bg-elevated); border: 1px solid var(--lime-border); border-radius: var(--r-lg); padding: var(--sp-6); }
.doc-item { display: flex; gap: var(--sp-4); padding: var(--sp-4) 0; border-bottom: 1px solid var(--border); align-items: flex-start; }
.doc-item:last-child { border-bottom: none; }
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
      <span aria-current="page">Seguros de fallecidos</span>
    </nav>
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">🔍 Seguros de fallecidos</div>
        <h1 style="font-size:clamp(1.875rem,4.5vw,3rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.1;margin-bottom:var(--sp-5)">
          Cómo saber si un fallecido tenía seguros de vida no reclamados
        </h1>
        <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-5);max-width:600px">
          Miles de millones en <strong style="color:var(--text-2)">seguros de vida sin cobrar</strong> en España. Cómo consultar el <strong style="color:var(--text-2)">Registro de Contratos de Seguros de Cobertura de Fallecimiento (RCSCF)</strong>, qué documentos necesitas y cómo reclamar la indemnización.
        </p>
        <div class="info-box" style="margin-bottom:var(--sp-6)">
          <div style="font-weight:var(--fw-semibold);color:var(--lime);margin-bottom:var(--sp-2)">⚠️ Plazo importante</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">El RCSCF puede consultarse hasta <strong style="color:var(--text-2)">5 años después del fallecimiento</strong>. El plazo de prescripción para reclamar el seguro de vida también es de 5 años (art. 23 LCS).</p>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3)">
          <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Reclamar seguro de vida →</a>
          <a href="#como-consultar" class="btn btn-secondary btn-lg">Cómo consultar el registro</a>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-8)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:var(--sp-5)">Datos clave</div>
          @foreach([
            ['📊','~3 millones de seguros de vida activos en España','Muchos sin beneficiarios que lo sepan'],
            ['💰','Capital medio por póliza','Entre 30.000 € y 150.000 €'],
            ['⏱️','Plazo de consulta RCSCF','5 años desde fallecimiento'],
            ['📋','Documentos necesarios','Certificado defunción + DNI + últimas voluntades'],
          ] as [$icon, $title, $sub])
          <div style="display:flex;gap:var(--sp-3);padding:var(--sp-4) 0;border-bottom:1px solid var(--border)">
            <span style="flex-shrink:0">{{ $icon }}</span>
            <div>
              <div style="font-size:var(--t-sm);font-weight:var(--fw-semibold);color:var(--text)">{{ $title }}</div>
              <div style="font-size:var(--t-xs);color:var(--text-3)">{{ $sub }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0;">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar un seguro de vida de fallecido', 'sub' => 'Genera la carta de reclamación con la base legal completa. En menos de 90 segundos.'])
  </div>
</section>

{{-- RCSCF --}}
<section class="lp-section" id="como-consultar">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">El registro</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Qué es el RCSCF y para qué sirve</h2>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">El <strong style="color:var(--text-2)">Registro de Contratos de Seguros de Cobertura de Fallecimiento (RCSCF)</strong>, gestionado por el Ministerio de Justicia, centraliza todos los contratos de seguro de vida y accidentes que incluyen coberturas de fallecimiento suscritos en España.</p>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">Cualquier persona con <strong style="color:var(--text-2)">interés legítimo</strong> (beneficiario designado, heredero, representante legal) puede solicitar un certificado que indica si el fallecido tenía algún seguro de este tipo y qué compañía aseguradora lo gestiona.</p>
        <p style="color:var(--text-3);line-height:1.75">El certificado <strong style="color:var(--text-2)">no indica el importe</strong> de la indemnización, solo la existencia del seguro y la aseguradora. Para conocer el capital asegurado hay que contactar directamente con la compañía.</p>
      </div>
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Cómo solicitarlo</span>
        <h3 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Cómo consultar el RCSCF paso a paso</h3>
        @foreach([
          ['1','Obtener el certificado de defunción literal','En el Registro Civil donde se inscribió el fallecimiento. Imprescindible para cualquier trámite posterior.'],
          ['2','Solicitar el certificado de últimas voluntades','En el Registro de Actos de Última Voluntad del Ministerio de Justicia. Indica si el fallecido otorgó testamento y ante qué notario.'],
          ['3','Solicitar el certificado RCSCF','Online en la sede electrónica del Ministerio de Justicia, por correo postal (Gerencia Territorial del Ministerio de Justicia) o presencialmente. Tasa actual: 3,78 €. Plazo de respuesta: 7 días hábiles.'],
          ['4','Contactar con la aseguradora','Con el certificado RCSCF en mano, contacta con la compañía indicada y solicita el inicio del proceso de reclamación. Ellos pedirán documentación adicional.'],
          ['5','Generar la carta de reclamación','Si la aseguradora pone obstáculos o retrasa el pago, genera una carta formal con la base legal del art. 23 LCS y los intereses de mora del art. 20 LCS.'],
        ] as [$num, $title, $body])
        <div class="step-item">
          <div class="step-num-lg" aria-hidden="true">{{ $num }}</div>
          <div>
            <h4 style="font-size:var(--t-sm);font-weight:var(--fw-semibold);margin-bottom:var(--sp-2)">{{ $title }}</h4>
            <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.7">{{ $body }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- DOCUMENTOS --}}
<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Documentación</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Documentos necesarios para reclamar el seguro de vida</h2>
      @foreach([
        ['📋','Certificado literal de defunción','Imprescindible. Se obtiene en el Registro Civil donde se inscribió el fallecimiento. Debe ser literal (no extracto).'],
        ['📜','Certificado de últimas voluntades','Indica si existe testamento y ante qué notario. Si no hay testamento, se necesita declaración notarial de herederos.'],
        ['🔍','Certificado RCSCF','Confirma la existencia del seguro y la aseguradora gestora. Tarda 7 días hábiles y cuesta 3,78 €.'],
        ['💳','DNI/NIE del solicitante','Acredita tu identidad como beneficiario o heredero. Si actúas por representación, también el poder notarial.'],
        ['📄','Testamento o declaración de herederos','Si no eres el beneficiario designado, necesitas acreditar que eres heredero legal. El testamento se obtiene ante el notario indicado en el certificado de últimas voluntades.'],
        ['🏥','Documentación médica (en su caso)','Si la aseguradora lo solicita, para confirmar que la causa del fallecimiento no es una exclusión de la póliza.'],
      ] as [$icon, $title, $desc])
      <div class="doc-item">
        <span style="font-size:1.5rem;flex-shrink:0">{{ $icon }}</span>
        <div>
          <h3 style="font-size:var(--t-sm);font-weight:var(--fw-semibold);margin-bottom:var(--sp-1)">{{ $title }}</h3>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">{{ $desc }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar el seguro de vida del fallecido', 'sub' => 'Carta formal con todos los documentos, plazos y base legal. Generada en 90 segundos.'])
  </div>
</section>

{{-- NORMATIVA --}}
<section class="lp-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Normativa</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Base legal que respalda tu reclamación</h2>
        @foreach([
          ['Art. 23 LCS','Prescripción del seguro de vida: 5 años desde que el beneficiario tuvo conocimiento del fallecimiento y la existencia del seguro.'],
          ['Art. 83 LCS','El beneficiario del seguro de vida puede reclamar directamente a la aseguradora con independencia de los herederos del tomador.'],
          ['Art. 20 LCS','Si la aseguradora retrasa injustificadamente el pago, debe intereses del 20% anual sobre el capital asegurado desde el momento en que debió pagar.'],
          ['Art. 18 LCS','La aseguradora debe pagar o rechazar motivadamente en 3 meses desde que el beneficiario acredite su derecho.'],
          ['RD 398/2007','Regula el RCSCF: quién puede consultar, plazos, documentación requerida y funcionamiento del registro.'],
        ] as [$art, $desc])
        <div style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--r-lg);padding:var(--sp-5);margin-bottom:var(--sp-4)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);color:var(--lime);margin-bottom:var(--sp-2)">{{ $art }}</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-6)">Preguntas frecuentes</h2>
        @foreach([
          ['¿Cuánto tiempo tengo para reclamar?','5 años desde que el beneficiario tuvo conocimiento del fallecimiento y la existencia del seguro (art. 23 LCS). Consulta el RCSCF cuanto antes: solo puede consultarse 5 años después del fallecimiento.'],
          ['¿Quién puede ser beneficiario si no se designó ninguno?','Si no hay beneficiarios designados o estos han premuerto, el capital corresponde a los herederos legales del tomador según el Código Civil.'],
          ['¿Puede la aseguradora negarme el pago?','Solo si concurre una exclusión válida de la póliza (p. ej. suicidio en el primer año, enfermedad preexistente no declarada). Las exclusiones deben estar destacadas y aceptadas específicamente (art. 3 LCS).'],
          ['¿Tributa el seguro de vida?','Los beneficiarios que no sean herederos tributan por el Impuesto sobre Sucesiones y Donaciones. Los herederos que sean a su vez beneficiarios también. Las bonificaciones varían por comunidad autónoma. Consúltalo con un gestor.'],
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
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <h2 style="margin-bottom:var(--sp-3)">Guías relacionadas</h2>
    <p style="color:var(--text-3);margin-bottom:var(--sp-8)">Otros recursos que te pueden ayudar:</p>
    <div class="interlinking">
      <a href="{{ route('seo.vida') }}" class="ilink-card"><span class="il-icon">❤️</span><div class="il-title">Reclamar seguro de vida</div><div class="il-sub">Invalidez, fallecimiento, beneficiarios →</div></a>
      <a href="{{ route('seo.hogar') }}" class="ilink-card"><span class="il-icon">🏠</span><div class="il-title">Reclamar seguro de hogar</div><div class="il-sub">Herencia, pólizas del inmueble →</div></a>
      <a href="{{ route('blog.show', 'seguros-vida-no-reclamados-espana') }}" class="ilink-card"><span class="il-icon">📖</span><div class="il-title">Seguros no reclamados en España</div><div class="il-sub">Datos, estadísticas, cómo buscar →</div></a>
      <a href="{{ route('blog.show', 'plazos-prescripcion-seguros-espana') }}" class="ilink-card"><span class="il-icon">⏱️</span><div class="il-title">Plazos de prescripción</div><div class="il-sub">Cuánto tiempo tienes para reclamar →</div></a>
      <a href="{{ route('guias.show', 'guia-completa-reclamar-seguro-espana') }}" class="ilink-card"><span class="il-icon">📘</span><div class="il-title">Guía completa de reclamación</div><div class="il-sub">Manual paso a paso →</div></a>
      <a href="{{ route('seo.reclamaciones') }}" class="ilink-card"><span class="il-icon">⚖️</span><div class="il-title">Todas las reclamaciones</div><div class="il-sub">Hub completo →</div></a>
    </div>
  </div>
</section>

<section style="padding: var(--sp-16) 0; background: var(--bg); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Reclamar el seguro de vida del fallecido ahora', 'sub' => 'Carta formal para la aseguradora con la base legal completa. Personalizada, lista en 90 segundos.'])
  </div>
</section>

@endsection
