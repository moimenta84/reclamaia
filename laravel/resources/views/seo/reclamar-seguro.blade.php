@extends('layouts.app')

@section('title', 'Cómo Reclamar a tu Seguro: Guía Completa para Todos los Ramos')
@section('meta-description', 'Guía completa para reclamar a cualquier seguro en España. Hogar, coche, vida, salud, accidentes. Proceso paso a paso, base legal LCS, normativa DGSFP y carta de reclamación.')
@section('canonical', route('seo.reclamar-seguro'))

@push('styles')
<style>
.page { padding: 0 !important; }
.page > .container { display: none; }
.lp-hero { background: var(--bg-section); border-bottom: 1px solid var(--border); padding: var(--sp-20) 0 var(--sp-16); }
.lp-section { padding: var(--sp-20) 0; }
.lp-section--alt { background: var(--bg-section); }
.tipo-card { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--r-lg); padding: var(--sp-5); text-decoration: none; display: block; transition: border-color var(--t-base-d), transform var(--t-base-d); }
.tipo-card:hover { border-color: var(--lime-border); transform: translateY(-2px); }
.faq-item { border-bottom: 1px solid var(--border); }
.faq-trigger { width: 100%; background: none; border: none; text-align: left; padding: var(--sp-5) 0; display: flex; justify-content: space-between; align-items: center; gap: var(--sp-4); cursor: pointer; color: var(--text); font-size: var(--t-base); font-weight: var(--fw-medium); }
.faq-trigger:hover { color: var(--lime); }
.faq-body { display: none; padding-bottom: var(--sp-5); color: var(--text-3); font-size: var(--t-base); line-height: 1.75; }
</style>
@endpush

@section('content')

<section class="lp-hero">
  <div class="container">
    <nav aria-label="Ruta de navegación" style="font-size:var(--t-xs);margin-bottom:var(--sp-6)">
      <a href="{{ route('home') }}" style="color:var(--text-3);text-decoration:none">Inicio</a>
      <span style="color:var(--text-4);margin:0 .4rem">/</span>
      <span aria-current="page">Reclamar seguro</span>
    </nav>
    <div style="max-width:760px">
      <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:var(--fw-bold);letter-spacing:-.035em;line-height:1.08;margin-bottom:var(--sp-5)">
        Cómo reclamar a tu seguro: guía completa para todos los ramos
      </h1>
      <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-8);max-width:640px">
        Ya sea un <strong style="color:var(--text-2)">seguro de hogar, coche, vida o salud</strong>, el proceso de reclamación sigue siempre la misma base legal. Aquí tienes la guía completa con todos los tipos, plazos y la normativa exacta que te protege.
      </p>
      <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3)">
        <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Generar reclamación →</a>
        <a href="{{ route('seo.reclamaciones') }}" class="btn btn-secondary btn-lg">Ver por tipo de seguro</a>
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0;">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar ahora', 'sub' => 'Indica tu aseguradora, el tipo de siniestro y la carta se genera en 90 segundos con base legal exacta.'])
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Elige tu seguro</span>
    <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Guías específicas por tipo de seguro</h2>
    <div class="row g-4">
      @foreach([
        ['🏠','Reclamar seguro de hogar',route('seo.hogar'),'Daños por agua, incendio, robo, goteras y cobertura denegada. La guía más completa.'],
        ['🚗','Reclamar seguro de coche',route('seo.coche'),'Accidentes, peritaciones bajas, robo, pérdida total y daños no cubiertos.'],
        ['❤️','Reclamar seguro de vida',route('seo.vida'),'Fallecimiento, invalidez permanente, enfermedad grave y beneficiarios que no cobran.'],
        ['🏥','Reclamar seguro de salud',route('seo.salud'),'Tratamiento denegado, demora en autorización, alta prematura y facturación incorrecta.'],
        ['🔍','Seguros de fallecidos',route('seo.fallecidos'),'Cómo encontrar seguros no reclamados de un familiar fallecido mediante el RCSCF.'],
        ['🌪️','Daños por desastres naturales',route('seo.desastres'),'DANA, inundaciones, granizo. Seguro privado y Consorcio de Compensación de Seguros.'],
      ] as [$icon, $title, $route, $desc])
      <div class="col-md-6 col-lg-4">
        <a href="{{ $route }}" class="tipo-card">
          <span style="font-size:1.5rem;display:block;margin-bottom:var(--sp-3)">{{ $icon }}</span>
          <div style="font-size:var(--t-base);font-weight:var(--fw-semibold);color:var(--text);margin-bottom:var(--sp-2)">{{ $title }}</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.6">{{ $desc }}</p>
          <div style="margin-top:var(--sp-4);font-size:var(--t-xs);color:var(--lime);font-weight:var(--fw-semibold)">Leer guía →</div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Los 6 pasos para reclamar a cualquier aseguradora</h2>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-6)">La Ley 50/1980 de Contrato de Seguro establece un proceso claro. Este es el orden que debes seguir para maximizar tus posibilidades de éxito.</p>
        @foreach(['Comunicar el siniestro por escrito dentro de plazo','Documentar exhaustivamente todos los daños','No reparar sin autorización (salvo urgencia)','Revisar la peritación de la aseguradora','Enviar carta formal de reclamación si no hay acuerdo','Escalar a DGSFP o juzgado si persiste el rechazo'] as $i => $step)
        <div style="display:flex;gap:var(--sp-4);padding:var(--sp-3) 0;border-bottom:1px solid var(--border)">
          <div style="flex-shrink:0;width:1.75rem;height:1.75rem;border-radius:50%;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);display:flex;align-items:center;justify-content:center">{{ $i+1 }}</div>
          <span style="font-size:var(--t-sm);color:var(--text-2);padding-top:.15rem">{{ $step }}</span>
        </div>
        @endforeach
      </div>
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Normativa</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Plazos legales que debes conocer</h2>
        @foreach([
          ['Comunicación del siniestro','7 días (varía por póliza). Comprueba el tuyo.'],
          ['Respuesta de la aseguradora','3 meses máximo (art. 18 LCS)'],
          ['Mora automática','Pasados 3 meses sin pago: 20% anual (art. 20 LCS)'],
          ['Prescripción seguros de daños','2 años desde el siniestro (art. 23 LCS)'],
          ['Prescripción seguro de vida','5 años desde el fallecimiento (art. 23 LCS)'],
          ['Reclamación DGSFP','4 meses de resolución. Gratuito.'],
        ] as [$plazo, $desc])
        <div style="display:flex;gap:var(--sp-4);padding:var(--sp-4) 0;border-bottom:1px solid var(--border);align-items:flex-start">
          <div style="flex-shrink:0;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-weight:var(--fw-semibold);font-size:var(--t-xs);padding:.2rem .6rem;border-radius:var(--r-full);white-space:nowrap">{{ $plazo }}</div>
          <p style="font-size:var(--t-sm);color:var(--text-3);margin:0;line-height:1.6">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Genera tu carta de reclamación', 'sub' => 'Carta formal con base legal española. Sin abogado. Sin esperas. Lista en 90 segundos.'])
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Preguntas generales sobre reclamaciones a aseguradoras</h2>
      @foreach([
        ['¿Tengo que contratar un abogado para reclamar al seguro?','No es obligatorio. Una carta formal bien fundamentada en la LCS resuelve la mayoría de los casos extrajudicialmente. Si el caso llega a juicio, es recomendable contar con asistencia letrada. Los seguros de defensa jurídica cubren estos honorarios.'],
        ['¿Qué pasa si la aseguradora no contesta en 3 meses?','Incurre en mora automática (art. 20 LCS) y debe intereses del 20% anual sobre la indemnización debida. Puedes también presentar reclamación ante la DGSFP por incumplimiento del plazo legal.'],
        ['¿Cuánto cuesta presentar una reclamación ante la DGSFP?','Es completamente gratuito. La DGSFP resuelve en un plazo máximo de 4 meses. No es un arbitraje vinculante, pero la resolución favorable tiene gran peso en un posterior litigio.'],
        ['¿Puedo reclamar aunque haya firmado la conformidad con la aseguradora?','Si firmaste un finiquito o conformidad, puede ser difícil reabrir la reclamación. Sin embargo, si la firma se obtuvo bajo presión, con información incompleta o sin que se explicaran tus derechos, existen vías para impugnarla.'],
        ['¿Qué es el Defensor del Asegurado y para qué sirve?','Es un órgano interno de cada aseguradora, obligatorio por ley, que resuelve reclamaciones de clientes. Es gratuito y su resolución es vinculante para la aseguradora si es favorable al cliente. Es el primer escalón antes de la DGSFP.'],
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

<section style="padding: var(--sp-16) 0; background: var(--bg-section); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box')
  </div>
</section>

@endsection
