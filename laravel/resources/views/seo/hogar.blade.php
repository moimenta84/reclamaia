@extends('layouts.app')

@section('title', 'Reclamar Seguro de Hogar: Goteras, Daños y Cobertura Denegada')
@section('meta-description', 'Cómo reclamar al seguro de hogar cuando rechaza el siniestro. Daños por agua, incendio, robo, goteras y coberturas denegadas. Guía legal actualizada 2024 con la Ley de Contrato de Seguro.')
@section('canonical', route('seo.hogar'))
@section('og-title', 'Reclamar Seguro de Hogar — Guía Completa 2024')
@section('og-description', 'Guía paso a paso para reclamar al seguro de hogar. Goteras, daños por agua, incendio, robo. Qué cubre la póliza, cuánto puedes reclamar y cómo hacerlo.')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Cuánto tiempo tiene la aseguradora para responder a una reclamación de hogar?",
      "acceptedAnswer": { "@type": "Answer", "text": "El artículo 18 de la Ley 50/1980 de Contrato de Seguro establece que la aseguradora debe satisfacer la indemnización o rechazarla motivadamente en un plazo de 3 meses desde que el asegurado le comunicó el siniestro. Pasado ese plazo sin respuesta, la asegurada incurre en mora." }
    },
    {
      "@type": "Question",
      "name": "¿Qué cubre el seguro de hogar obligatoriamente?",
      "acceptedAnswer": { "@type": "Answer", "text": "No existe cobertura mínima legal obligatoria para el seguro de hogar libre (a diferencia de comunidades). Cada póliza define sus coberturas. Las más habituales son daños por agua, incendio, robo, responsabilidad civil y fenómenos atmosféricos. Lee siempre las condiciones particulares." }
    },
    {
      "@type": "Question",
      "name": "¿Qué hago si el perito de la aseguradora valora menos de lo que me corresponde?",
      "acceptedAnswer": { "@type": "Answer", "text": "Tienes derecho a nombrar tu propio perito conforme al artículo 38 LCS. Si ambos peritos no se ponen de acuerdo, se nombra un tercer perito dirimente. Los costes del perito propio corren a tu cargo salvo que la póliza lo cubra." }
    },
    {
      "@type": "Question",
      "name": "¿Puedo reclamar al seguro de hogar por daños causados por el vecino de arriba?",
      "acceptedAnswer": { "@type": "Answer", "text": "Sí. Puedes reclamar a tu propio seguro (si cubre responsabilidad civil pasiva de vecinos) o directamente al seguro de responsabilidad civil del vecino causante. Si el vecino no tiene seguro, la reclamación es directamente a él." }
    },
    {
      "@type": "Question",
      "name": "¿Cuál es el plazo de prescripción para reclamar al seguro de hogar?",
      "acceptedAnswer": { "@type": "Answer", "text": "Para seguros de daños (incluido el hogar), el plazo de prescripción es de 2 años desde que el asegurado conoció o pudo conocer el siniestro (art. 23 LCS). Tras ese plazo, perderías el derecho a reclamar." }
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
    { "@type": "ListItem", "position": 3, "name": "Seguro de hogar", "item": "{{ route('seo.hogar') }}" }
  ]
}
</script>
@endpush

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.lp-hero {
  background: var(--bg-section);
  border-bottom: 1px solid var(--border);
  padding: var(--sp-20) 0 var(--sp-16);
}
.lp-hero .breadcrumb { font-size: var(--t-xs); margin-bottom: var(--sp-6); }
.lp-hero .breadcrumb a { color: var(--text-3); text-decoration: none; }
.lp-hero .breadcrumb a:hover { color: var(--lime); }
.lp-hero .breadcrumb span { color: var(--text-4); margin: 0 .4rem; }
.lp-section { padding: var(--sp-20) 0; }
.lp-section--alt { background: var(--bg-section); }
.lp-section--dark { background: var(--bg-card); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
.step-item { display: flex; gap: var(--sp-5); align-items: flex-start; padding: var(--sp-6) 0; border-bottom: 1px solid var(--border); }
.step-item:last-child { border-bottom: none; }
.step-num-lg { flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: var(--r-full); background: var(--lime-dim); border: 1px solid var(--lime-border); color: var(--lime); font-weight: var(--fw-bold); font-size: var(--t-sm); display: flex; align-items: center; justify-content: center; }
.case-card { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--r-lg); padding: var(--sp-6); }
.case-card h4 { font-size: var(--t-base); color: var(--text); margin-bottom: var(--sp-2); }
.amount-pill { display: inline-block; background: var(--lime-dim); color: var(--lime); border: 1px solid var(--lime-border); border-radius: var(--r-full); font-size: var(--t-xs); font-weight: var(--fw-bold); padding: .2rem .7rem; margin-bottom: var(--sp-3); }
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

{{-- HERO --}}
<section class="lp-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" class="breadcrumb">
      <a href="{{ route('home') }}">Inicio</a>
      <span aria-hidden="true">/</span>
      <a href="{{ route('seo.reclamaciones') }}">Reclamaciones</a>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Seguro de hogar</span>
    </nav>

    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">
          🏠 Seguro de hogar
        </div>
        <h1 style="font-size:clamp(1.875rem,4.5vw,3rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.1;margin-bottom:var(--sp-5)">
          Cómo reclamar al seguro de hogar cuando te deniegan el siniestro
        </h1>
        <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-8);max-width:600px">
          Goteras, daños por agua, incendio, robo o cobertura denegada: guía completa con la base legal exacta de la <strong style="color:var(--text-2)">Ley 50/1980 de Contrato de Seguro</strong>. Lo que tienes que hacer y lo que no.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3);margin-bottom:var(--sp-8)">
          <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">
            Generar reclamación ahora →
          </a>
          <a href="#pasos" class="btn btn-secondary btn-lg">Ver los pasos</a>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-5);font-size:var(--t-xs);color:var(--text-3)">
          <span>✓ Carta lista en 90 segundos</span>
          <span>✓ LCS actualizada 2024</span>
          <span>✓ Sin abogado necesario</span>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-8)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:var(--sp-5)">Tipos de siniestro que reclamamos</div>
          @foreach(['💧 Daños por agua y goteras', '🔥 Incendio y explosión', '🔨 Robo y vandalismo', '🌪️ Fenómenos atmosféricos', '⚡ Daños eléctricos', '🔧 Responsabilidad civil', '❌ Cobertura denegada'] as $item)
          <div style="display:flex;align-items:center;gap:var(--sp-3);padding:var(--sp-3) 0;border-bottom:1px solid var(--border);font-size:var(--t-sm)">
            <span style="color:var(--lime)">✓</span>
            <span>{{ $item }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA TOP --}}
<section style="padding: var(--sp-10) 0; background: var(--bg);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Revisar mi caso gratis', 'sub' => 'Dinos qué pasó y en 90 segundos generamos la carta de reclamación con base legal para tu aseguradora.'])
  </div>
</section>

{{-- QUÉ CUBRE --}}
<section class="lp-section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Coberturas</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Qué cubre (y qué no) el seguro de hogar</h2>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">
          El seguro de hogar no es una póliza única. Cada compañía define sus coberturas en las <strong style="color:var(--text-2)">condiciones particulares</strong>. Sin embargo, la inmensa mayoría incluyen como coberturas estándar los daños por agua (tuberías, goteras, desbordamientos), incendio, explosión y rayo, robo con fuerza en las cosas o violencia en las personas, responsabilidad civil familiar y fenómenos atmosféricos ordinarios.
        </p>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">
          Las aseguradoras <strong style="color:var(--text-2)">deniegan siniestros</strong> alegando exclusiones de póliza, negligencia del asegurado, daños preexistentes o valor de la póliza inferior al valor real del inmueble (infraseguro, regulado en el art. 30 LCS). En muchos casos esa denegación es recurrible.
        </p>
        <p style="color:var(--text-3);line-height:1.75">
          Recuerda: las cláusulas limitativas de derechos deben estar <strong style="color:var(--text-2)">destacadas especialmente y aceptadas específicamente</strong> por el tomador (art. 3 LCS). Si no lo están, son nulas.
        </p>
      </div>
      <div class="col-lg-6">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);overflow:hidden">
          <div style="background:var(--bg-elevated);padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border)">
            <h3 style="font-size:var(--t-base);margin:0;color:var(--text)">Coberturas habituales</h3>
          </div>
          @foreach([
            ['✅','Daños por agua (tuberías, goteras, desbordamiento)'],
            ['✅','Incendio, rayo y explosión'],
            ['✅','Robo con fuerza o intimidación'],
            ['✅','Responsabilidad civil del hogar'],
            ['✅','Fenómenos atmosféricos (viento, granizo, nieve)'],
            ['✅','Daños eléctricos y cortocircuito'],
            ['⚠️','DANA / inundaciones (puede requerir CCS)'],
            ['❌','Desgaste natural o falta de mantenimiento'],
            ['❌','Infraseguro (valor asegurado < valor real)'],
          ] as [$icon, $text])
          <div style="display:flex;align-items:center;gap:var(--sp-3);padding:var(--sp-3) var(--sp-6);border-bottom:1px solid var(--border);font-size:var(--t-sm)">
            <span style="width:1.2rem;text-align:center;flex-shrink:0">{{ $icon }}</span>
            <span style="color:var(--text-2)">{{ $text }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CUÁNTO PUEDES RECLAMAR --}}
<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="text-align:center;max-width:620px;margin:0 auto var(--sp-12)">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Cuantías</span>
      <h2 style="margin-top:var(--sp-3)">Cuánto puedes reclamar: casos reales</h2>
      <p style="color:var(--text-3);line-height:1.75">Importes orientativos basados en resoluciones DGSFP y jurisprudencia del Tribunal Supremo y Audiencias Provinciales. Cada caso es distinto.</p>
    </div>
    <div class="row g-4">
      @foreach([
        ['💧','Daños por rotura de tubería','Tubería interior reventó e inundó dos habitaciones. La aseguradora valoró 800 €. Tras reclamar con perito propio, se obtuvo 3.200 €.','3.200 €'],
        ['🔥','Incendio en cocina','Cortocircuito en electrodoméstico. Primera oferta de 4.500 €. Reclamación con carta formal: indemnización final de 11.800 €.','11.800 €'],
        ['🌧️','Filtración de agua por fachada','La aseguradora alegó mal mantenimiento. Acreditada la causa fortuita y urgencia reparatoria. Indemnización: 5.600 €.','5.600 €'],
        ['🔨','Robo en vivienda','Robo con fuerza. La aseguradora redujo la tasación aplicando obsolescencia. Reclamación por infravaloración: diferencia cobrada 2.100 €.','2.100 €'],
        ['🌪️','Daños por granizo (tejado y fachada)','DANA 2024: granizo destrozó tejado y cerramientos. Coordinación con Consorcio de Compensación: total indemnizado 18.400 €.','18.400 €'],
        ['⚡','Sobretensión eléctrica','Quema de varios electrodomésticos y equipo de calefacción. Aseguradora rechazó. Reclamación formal: 4.300 € obtenidos.','4.300 €'],
      ] as [$icon, $title, $desc, $amount])
      <div class="col-md-6 col-lg-4">
        <div class="case-card">
          <span class="amount-pill">{{ $amount }}</span>
          <h4>{{ $icon }} {{ $title }}</h4>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">{{ $desc }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- PASOS --}}
<section class="lp-section" id="pasos">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Pasos para reclamar al seguro de hogar</h2>
        <p style="color:var(--text-3);line-height:1.75">Sigue este orden exacto para no cometer errores que comprometan tu reclamación. El orden y la documentación son clave.</p>
        <a href="{{ route('claim.create') }}" class="btn btn-primary mt-4">Generar mi carta ahora →</a>
      </div>
      <div class="col-lg-7">
        @foreach([
          ['1','Comunicar el siniestro de inmediato','Notifica el siniestro a la aseguradora por escrito (email o burofax) dentro del plazo estipulado en la póliza (generalmente 7 días). Conserva el acuse de recibo. Esto es crucial: el retraso puede darte por renunciado a la cobertura.'],
          ['2','Documentar exhaustivamente los daños','Fotografía y vídeo con fecha y hora. Informes de fontanero, electricista o perito independiente. Facturas de todo lo dañado. Cuanto más documentado, más difícil es para la aseguradora reducir la indemnización.'],
          ['3','No reparar sin autorización (o con urgencia justificada)','No repares antes de que el perito de la aseguradora inspeccione, salvo que sea una urgencia (cortar el agua, tapar el tejado). En ese caso, documenta todo antes de reparar y guarda los materiales dañados.'],
          ['4','Analizar la valoración del perito de la aseguradora','Si la tasación es inferior a tu coste real, tienes derecho a nombrar tu propio perito (art. 38 LCS). No aceptes la primera oferta sin comprobarla.'],
          ['5','Enviar carta formal de reclamación','Si la aseguradora rechaza o infravalora, envía una carta formal por burofax o correo certificado con la normativa aplicable, la jurisprudencia relevante y el importe exacto que reclamas.'],
          ['6','Escalar a DGSFP o juicio si no hay respuesta','Si en 30 días no hay respuesta satisfactoria, presenta reclamación ante la DGSFP o el Defensor del Asegurado. Con la DGSFP puedes también obtener información sobre sanciones a la compañía.'],
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

{{-- CTA MID --}}
<section style="padding: var(--sp-10) 0; background: var(--bg-section); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar mi siniestro de hogar', 'sub' => 'Genera la carta con todos los artículos de la LCS aplicables a tu caso. Personalizada por aseguradora.'])
  </div>
</section>

{{-- NORMATIVA --}}
<section class="lp-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Base legal</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Normativa que protege tu reclamación de hogar</h2>
        <div style="display:flex;flex-direction:column;gap:var(--sp-4)">
          @foreach([
            ['Art. 18 LCS','La aseguradora debe pagar en 3 meses o justificar el rechazo. Pasado ese plazo, incurre en mora y debe intereses del 20% anual (art. 20 LCS).'],
            ['Art. 3 LCS','Las cláusulas limitativas de derechos del asegurado deben estar destacadas y aceptadas expresamente. Si no lo están, son nulas.'],
            ['Art. 30 LCS','Regula el infraseguro. Si el valor asegurado es inferior al real, la aseguradora solo paga proporcionalmente. Pero puedes impugnar una valoración errónea.'],
            ['Art. 38 LCS','Peritaje contradictorio: si no hay acuerdo entre el perito de la aseguradora y el tuyo, se nombra un tercero. Los honorarios del perito propio van a cargo del asegurado.'],
            ['Art. 20 LCS','Intereses de mora del 20% anual sobre la indemnización debida si la aseguradora retrasa injustificadamente el pago. Muy relevante en litigios.'],
          ] as [$art, $desc])
          <div style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--r-lg);padding:var(--sp-5)">
            <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);color:var(--lime);margin-bottom:var(--sp-2)">{{ $art }}</div>
            <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">{{ $desc }}</p>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Plazos clave</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Plazos que no puedes perder</h2>
        @foreach([
          ['7 días','Plazo habitual para comunicar el siniestro a la aseguradora (varía por póliza). Comprueba el tuyo.'],
          ['3 meses','Plazo máximo para que la aseguradora resuelva la reclamación (art. 18 LCS).'],
          ['30 días','Plazo recomendado para esperar respuesta antes de escalar a la DGSFP.'],
          ['2 años','Prescripción del seguro de daños (art. 23 LCS). Pasado ese plazo, pierdes el derecho.'],
          ['5 años','Prescripción del seguro de vida (art. 23 LCS).'],
        ] as [$plazo, $desc])
        <div style="display:flex;gap:var(--sp-4);padding:var(--sp-4) 0;border-bottom:1px solid var(--border);align-items:flex-start">
          <div style="flex-shrink:0;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-weight:var(--fw-bold);font-size:var(--t-sm);padding:.3rem .8rem;border-radius:var(--r-full);white-space:nowrap">{{ $plazo }}</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.65">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Preguntas frecuentes sobre seguros de hogar</h2>
      @foreach([
        ['¿Cuánto tiempo tiene la aseguradora para responder?','El artículo 18 LCS establece un plazo máximo de 3 meses para pagar o rechazar motivadamente. Si no lo hace, incurre en mora y debe intereses del 20% anual.'],
        ['¿Qué hago si el perito de la aseguradora me valora menos de lo que me corresponde?','Tienes derecho a nombrar tu propio perito (art. 38 LCS). Si no hay acuerdo, se designa un tercer perito dirimente. No aceptes la primera tasación sin verificarla.'],
        ['¿Puedo reclamar al seguro del vecino que me causó daños?','Sí. Si el vecino tiene seguro de hogar con responsabilidad civil, puedes reclamar a su aseguradora directamente. Si no tiene seguro, la reclamación es contra él personalmente.'],
        ['¿Cuál es el plazo de prescripción para seguros de hogar?','2 años desde que el asegurado conoció o pudo conocer el siniestro (art. 23 LCS). Pasado ese plazo, pierdes el derecho a reclamar.'],
        ['¿Qué son las cláusulas de infraseguro y cómo me afectan?','Si el valor asegurado es inferior al valor real de tus bienes, la aseguradora aplica la regla proporcional: solo paga el porcentaje que representa el valor asegurado sobre el real. Puedes impugnar valoraciones incorrectas.'],
        ['¿Tengo que contratar un abogado para reclamar?','No es obligatorio. Una carta de reclamación bien fundamentada en la LCS suele ser suficiente para resolver extrajudicialmente. Si el caso llega a juicio, sí es recomendable.'],
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

{{-- INTERLINKING --}}
<section class="lp-section">
  <div class="container">
    <h2 style="margin-bottom:var(--sp-3)">Otros tipos de reclamación que también cubrimos</h2>
    <p style="color:var(--text-3);margin-bottom:var(--sp-8)">Si tu siniestro no es de hogar, aquí tienes las guías para otros ramos:</p>
    <div class="interlinking">
      <a href="{{ route('seo.coche') }}" class="ilink-card">
        <span class="il-icon">🚗</span>
        <div class="il-title">Reclamar seguro de coche</div>
        <div class="il-sub">Accidentes, robo y daños no cubiertos →</div>
      </a>
      <a href="{{ route('seo.vida') }}" class="ilink-card">
        <span class="il-icon">❤️</span>
        <div class="il-title">Reclamar seguro de vida</div>
        <div class="il-sub">Fallecimiento, invalidez, beneficiarios →</div>
      </a>
      <a href="{{ route('seo.fallecidos') }}" class="ilink-card">
        <span class="il-icon">🔍</span>
        <div class="il-title">Seguros de fallecidos</div>
        <div class="il-sub">Cómo saber si el fallecido tenía seguros →</div>
      </a>
      <a href="{{ route('seo.desastres') }}" class="ilink-card">
        <span class="il-icon">🌪️</span>
        <div class="il-title">Daños por desastres naturales</div>
        <div class="il-sub">DANA, inundaciones, granizo →</div>
      </a>
      <a href="{{ route('blog.show', 'que-hacer-si-seguro-rechaza-siniestro') }}" class="ilink-card">
        <span class="il-icon">📖</span>
        <div class="il-title">Qué hacer si te rechazan el siniestro</div>
        <div class="il-sub">Guía paso a paso →</div>
      </a>
      <a href="{{ route('seo.reclamaciones') }}" class="ilink-card">
        <span class="il-icon">⚖️</span>
        <div class="il-title">Ver todas las reclamaciones</div>
        <div class="il-sub">Hub completo de reclamaciones →</div>
      </a>
    </div>
  </div>
</section>

{{-- CTA FINAL --}}
<section style="padding: var(--sp-16) 0; background: var(--bg-section); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Genera tu reclamación de hogar ahora', 'sub' => 'Carta formal con base en la LCS, jurisprudencia CENDOJ y datos de tu aseguradora. En menos de 90 segundos.'])
  </div>
</section>

@endsection
