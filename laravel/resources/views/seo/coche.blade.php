@extends('layouts.app')

@section('title', 'Reclamar Seguro de Coche: Accidentes, Robo y Daños Negados')
@section('meta-description', 'Cómo reclamar al seguro de coche cuando rechaza el siniestro. Accidentes, robo, daños de terceros, cobertura denegada. Base legal LCS y baremo 2024 actualizado.')
@section('canonical', route('seo.coche'))
@section('og-title', 'Reclamar Seguro de Coche — Guía Legal 2024')
@section('og-description', 'Guía completa para reclamar al seguro del coche. Accidentes, robo, peritaciones bajas, daños no cubiertos. Ley de Contrato de Seguro y baremo de tráfico 2024.')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Qué hago si la aseguradora valora los daños del coche por menos de lo que cuestan?",
      "acceptedAnswer": { "@type": "Answer", "text": "Tienes derecho a nombrar un perito propio (art. 38 LCS). Si hay discrepancia entre los dos peritos, se designa un tercero dirimente. No tienes que aceptar la primera tasación. También puedes solicitar al taller que justifique el coste real de la reparación." }
    },
    {
      "@type": "Question",
      "name": "¿Cuánto tarda la aseguradora en pagar después de un accidente de tráfico?",
      "acceptedAnswer": { "@type": "Answer", "text": "El art. 18 LCS obliga a resolver en 3 meses desde la comunicación del siniestro. En accidentes con lesionados, la Ley 35/2015 obliga a una oferta motivada en 3 meses desde la comunicación del accidente. Si no lo hace, incurre en mora del 20% anual." }
    },
    {
      "@type": "Question",
      "name": "¿El seguro de coche cubre el coche aunque sea culpa mía?",
      "acceptedAnswer": { "@type": "Answer", "text": "Depende de la cobertura contratada. El seguro a terceros solo cubre los daños que causes a otros. Para cubrir tus propios daños necesitas un seguro a todo riesgo o un seguro con daños propios. Comprueba tu póliza antes de reclamar." }
    },
    {
      "@type": "Question",
      "name": "¿Qué pasa si el otro conductor no tiene seguro?",
      "acceptedAnswer": { "@type": "Answer", "text": "El Consorcio de Compensación de Seguros cubre los daños personales en accidentes con vehículos no asegurados o no identificados. Para daños materiales, la cobertura del CCS es más limitada. También puedes reclamar directamente al conductor causante." }
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
    { "@type": "ListItem", "position": 3, "name": "Seguro de coche", "item": "{{ route('seo.coche') }}" }
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

<section class="lp-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-6)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <a href="{{ route('seo.reclamaciones') }}" style="color:var(--text-3);text-decoration:none">Reclamaciones</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">Seguro de coche</span>
    </nav>
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">🚗 Seguro de coche</div>
        <h1 style="font-size:clamp(1.875rem,4.5vw,3rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.1;margin-bottom:var(--sp-5)">
          Cómo reclamar al seguro del coche: accidentes, peritaciones bajas y coberturas negadas
        </h1>
        <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-8);max-width:600px">
          Cuando la aseguradora valora menos, demora o rechaza: guía con la base legal exacta del <strong style="color:var(--text-2)">baremo 2024</strong> y la <strong style="color:var(--text-2)">Ley de Contrato de Seguro</strong>. Incluye casos reales con importes.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3);margin-bottom:var(--sp-8)">
          <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Reclamar ahora →</a>
          <a href="{{ route('tools.baremo.show') }}" class="btn btn-secondary btn-lg">Calcular baremo</a>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-5);font-size:var(--t-xs);color:var(--text-3)">
          <span>✓ Baremo 2024 integrado</span>
          <span>✓ LCS actualizada</span>
          <span>✓ Carta lista en 90 segundos</span>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:var(--sp-8)">
          <div style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:var(--sp-5)">Siniestros que reclamamos</div>
          @foreach(['💥 Accidente con culpa del otro','🚗 Choque sin identificar al culpable','🔥 Incendio o explosión del vehículo','💸 Peritación inferior al coste real','🔒 Robo del vehículo o de piezas','🌧️ Daños por fenómenos atmosféricos','❌ Rechazo injustificado de cobertura'] as $item)
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
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Revisar mi caso de seguro de coche', 'sub' => 'Genera la carta con el baremo 2024 y la LCS. Personalizada para tu aseguradora.'])
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Qué cubre</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Coberturas del seguro del coche</h2>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">El seguro obligatorio de responsabilidad civil (seguro a terceros) cubre los daños que causas a personas y bienes de terceros. <strong style="color:var(--text-2)">No cubre tus propios daños.</strong> Para eso necesitas cobertura adicional.</p>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">Los motivos de reclamación más frecuentes son: <strong style="color:var(--text-2)">peritación insuficiente</strong> (el perito valora menos de lo que cuesta la reparación), <strong style="color:var(--text-2)">demora en el pago</strong>, <strong style="color:var(--text-2)">rechazo por cláusulas oscuras</strong> que la aseguradora no comunicó correctamente al contratar, y <strong style="color:var(--text-2)">infravaloración del vehículo</strong> tras robo o pérdida total.</p>
        <p style="color:var(--text-3);line-height:1.75">En accidentes con lesionados, el baremo de la Ley 35/2015 y la Resolución DGS 2024 establece las cuantías mínimas de indemnización. La aseguradora <strong style="color:var(--text-2)">no puede pagar menos</strong> de lo que fija el baremo.</p>
      </div>
      <div class="col-lg-6">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);overflow:hidden">
          <div style="background:var(--bg-elevated);padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border)">
            <h3 style="font-size:var(--t-base);margin:0">Coberturas por tipo de póliza</h3>
          </div>
          @foreach([
            ['Seguro a terceros','RC obligatoria: daños a terceros','✅','❌','❌'],
            ['Terceros ampliado','+ incendio, robo, lunas','✅','Parcial','❌'],
            ['Todo riesgo con franquicia','+ daños propios (con franquicia)','✅','✅','Parcial'],
            ['Todo riesgo sin franquicia','Cobertura completa','✅','✅','✅'],
          ] as [$tipo, $desc, $rc, $propio, $total])
          <div style="padding:var(--sp-4) var(--sp-6);border-bottom:1px solid var(--border)">
            <div style="font-weight:var(--fw-semibold);font-size:var(--t-sm);margin-bottom:var(--sp-1)">{{ $tipo }}</div>
            <div style="font-size:var(--t-xs);color:var(--text-3)">{{ $desc }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="text-align:center;max-width:620px;margin:0 auto var(--sp-12)">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Casos reales</span>
      <h2 style="margin-top:var(--sp-3)">Cuánto puedes reclamar: ejemplos</h2>
    </div>
    <div class="row g-4">
      @foreach([
        ['💥','Accidente con fractura de muñeca','Lesión en accidente de tráfico. Aseguradora ofreció 3.200 €. Con baremo 2024 aplicado correctamente: 8.700 €.','8.700 €'],
        ['🚗','Pérdida total del vehículo','Valor venal ofrecido por aseguradora: 9.500 €. Valor real de mercado documentado: 14.200 €. Reclamación exitosa.','14.200 €'],
        ['💸','Peritación insuficiente','Reparación real: 4.800 €. Perito aseguradora: 2.100 €. Con perito propio y carta formal: 4.600 € obtenidos.','4.600 €'],
        ['🔒','Robo del catalizador','Robo de catalizador no cubierto alegando cláusula de exclusión. Cláusula considerada limitativa (art. 3 LCS): 1.800 € cobrados.','1.800 €'],
      ] as [$icon, $title, $desc, $amount])
      <div class="col-md-6">
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

<section class="lp-section" id="pasos">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Pasos para reclamar al seguro del coche</h2>
        <p style="color:var(--text-3);line-height:1.75">El orden importa. Saltarse pasos puede debilitar tu posición legal frente a la aseguradora.</p>
        <a href="{{ route('claim.create') }}" class="btn btn-primary mt-4">Generar mi carta →</a>
      </div>
      <div class="col-lg-7">
        @foreach([
          ['1','Declarar el siniestro inmediatamente','Comunica el accidente o siniestro a tu aseguradora por escrito (email o burofax) dentro de las 72 horas. Si hay lesionados, también al seguro del otro vehículo.'],
          ['2','Documentar el accidente y los daños','Fotografías del lugar, de los daños de ambos vehículos, del informe de la policía o guardia civil si acudió, y de las facturas del taller.'],
          ['3','No firmar nada sin leerlo detenidamente','La aseguradora puede intentar que firmes una renuncia a futuras reclamaciones. No firmes ningún documento sin entender exactamente lo que contiene.'],
          ['4','Revisar la peritación de la aseguradora','Compara el importe peritado con el presupuesto del taller. Si hay diferencia significativa, solicita revisión o nombra perito propio (art. 38 LCS).'],
          ['5','Enviar carta formal de reclamación','Con el importe real documentado, la normativa aplicable y un plazo de 15 días para responder antes de escalar.'],
          ['6','Acudir al Consorcio, DGSFP o juzgado','Si la aseguradora no cede, el Consorcio de Compensación de Seguros actúa como último recurso en algunos supuestos. La DGSFP puede sancionar a la compañía.'],
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

<section style="padding: var(--sp-10) 0; background: var(--bg-section); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar al seguro del coche', 'sub' => 'Carta con baremo 2024, LCS y jurisprudencia del TS. Lista en menos de 90 segundos.'])
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Preguntas frecuentes sobre seguros de coche</h2>
      @foreach([
        ['¿Qué hago si la aseguradora valora los daños por menos de lo que cuestan?','Nombra tu propio perito (art. 38 LCS). Si hay discrepancia entre los dos peritos, se designa un tercero dirimente. No tienes que aceptar la primera tasación. Documenta el presupuesto del taller.'],
        ['¿Cuánto tarda la aseguradora en pagar tras un accidente?','El art. 18 LCS obliga a resolver en 3 meses. En accidentes con lesionados, la Ley 35/2015 obliga a una oferta motivada en 3 meses. Si no lo hace, incurre en mora del 20% anual.'],
        ['¿El seguro a terceros cubre mis daños si el accidente es culpa mía?','No. El seguro obligatorio (RC) solo cubre los daños que causes a terceros. Para tus propios daños necesitas cobertura de daños propios o todo riesgo.'],
        ['¿Qué pasa si el otro conductor no tiene seguro?','El Consorcio de Compensación de Seguros (CCS) cubre los daños personales en accidentes con vehículos no asegurados. Para daños materiales hay condiciones específicas.'],
        ['¿Puedo reclamar si el accidente fue hace más de un año?','El plazo de prescripción del seguro de daños es de 2 años (art. 23 LCS). Para seguros de accidentes personales, 1 año. Comprueba cuándo ocurrió exactamente el siniestro.'],
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
    <h2 style="margin-bottom:var(--sp-3)">Otros tipos de reclamación relacionados</h2>
    <p style="color:var(--text-3);margin-bottom:var(--sp-8)">Casos similares o complementarios:</p>
    <div class="interlinking">
      <a href="{{ route('seo.hogar') }}" class="ilink-card"><span class="il-icon">🏠</span><div class="il-title">Reclamar seguro de hogar</div><div class="il-sub">Daños, goteras, cobertura denegada →</div></a>
      <a href="{{ route('seo.vida') }}" class="ilink-card"><span class="il-icon">❤️</span><div class="il-title">Reclamar seguro de vida</div><div class="il-sub">Fallecimiento, invalidez →</div></a>
      <a href="{{ route('seo.desastres') }}" class="ilink-card"><span class="il-icon">🌪️</span><div class="il-title">Daños por desastres</div><div class="il-sub">DANA, granizo, inundaciones →</div></a>
      <a href="{{ route('tools.baremo.show') }}" class="ilink-card"><span class="il-icon">⚖️</span><div class="il-title">Calculadora de baremo</div><div class="il-sub">Calcula tu indemnización →</div></a>
      <a href="{{ route('blog.show', 'cuanto-tarda-reclamacion-aseguradora') }}" class="ilink-card"><span class="il-icon">⏱️</span><div class="il-title">Cuánto tarda la aseguradora</div><div class="il-sub">Plazos y consecuencias →</div></a>
      <a href="{{ route('seo.reclamaciones') }}" class="ilink-card"><span class="il-icon">📋</span><div class="il-title">Todas las reclamaciones</div><div class="il-sub">Hub completo →</div></a>
    </div>
  </div>
</section>

<section style="padding: var(--sp-16) 0; background: var(--bg-section); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Genera tu reclamación de seguro de coche', 'sub' => 'Carta con el baremo 2024, LCS y argumentos jurídicos. Personalizada por aseguradora en menos de 90 segundos.'])
  </div>
</section>

@endsection
