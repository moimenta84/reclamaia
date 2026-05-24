@extends('layouts.app')

@section('title', 'Reclamar Seguro de Salud: Tratamiento Denegado y Autorización Retrasada')
@section('meta-description', 'Cómo reclamar al seguro de salud privado en España: tratamiento denegado, autorización retrasada, alta prematura, facturación incorrecta. Base legal LCS y plazos.')
@section('canonical', route('seo.salud'))

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
      <span aria-current="page">Seguro de salud</span>
    </nav>
    <div class="row g-5 align-items-center">
      <div class="col-lg-7">
        <div style="display:inline-flex;align-items:center;gap:.4rem;background:var(--lime-dim);border:1px solid var(--lime-border);color:var(--lime);font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.06em;text-transform:uppercase;padding:.25rem .8rem;border-radius:var(--r-full);margin-bottom:var(--sp-5)">🏥 Seguro de salud</div>
        <h1 style="font-size:clamp(1.875rem,4.5vw,3rem);font-weight:var(--fw-bold);letter-spacing:-.03em;line-height:1.1;margin-bottom:var(--sp-5)">
          Cómo reclamar al seguro de salud privado: autorización denegada, alta prematura y más
        </h1>
        <p style="font-size:var(--t-lg);color:var(--text-3);line-height:1.7;margin-bottom:var(--sp-8);max-width:600px">
          El seguro de salud privado está sujeto a la Ley de Contrato de Seguro. Guía para reclamar cuando te deniegan un tratamiento, retrasan la autorización o te cobran de más.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-3)">
          <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">Reclamar ahora →</a>
          <a href="{{ route('seo.reclamaciones') }}" class="btn btn-secondary btn-lg">Ver todos los tipos</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding: var(--sp-10) 0;">
  <div class="container">
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Reclamar al seguro de salud', 'sub' => 'Carta con base legal LCS en 90 segundos. Personalizada para tu aseguradora.'])
  </div>
</section>

<section class="lp-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Causas más frecuentes</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Cuándo y por qué reclamar al seguro de salud</h2>
        <p style="color:var(--text-3);line-height:1.75;margin-bottom:var(--sp-5)">Los seguros de salud privados tienen los mismos derechos y el mismo marco legal que cualquier otro seguro. Los casos más frecuentes de reclamación son la <strong style="color:var(--text-2)">denegación de autorización</strong> de tratamientos o pruebas diagnósticas, la <strong style="color:var(--text-2)">demora injustificada</strong> en dar respuesta a solicitudes urgentes, el <strong style="color:var(--text-2)">alta prematura</strong> sin criterio médico y la <strong style="color:var(--text-2)">facturación incorrecta</strong> de servicios incluidos en la póliza.</p>
        <p style="color:var(--text-3);line-height:1.75">Las exclusiones por <strong style="color:var(--text-2)">enfermedades preexistentes</strong> son el motivo de rechazo más habitual. Sin embargo, para ser válidas, deben estar claramente informadas al asegurado en el momento de la contratación y aceptadas expresamente. Si no lo fueron, son nulas (art. 3 LCS).</p>
      </div>
      <div class="col-lg-6">
        <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">Proceso</span>
        <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-5)">Cómo reclamar al seguro de salud</h2>
        @foreach([
          ['1','Documentar la denegación','Solicita por escrito la resolución de la aseguradora denegando o retrasando la autorización. Guarda toda la comunicación.'],
          ['2','Obtener informe médico que justifique la necesidad','El médico que te trata debe emitir un informe justificando la necesidad médica del tratamiento o prueba. Es tu argumento principal.'],
          ['3','Enviar reclamación formal por escrito','Carta formal a la aseguradora con el informe médico, la base legal y plazo de respuesta. Burofax o email con acuse de recibo.'],
          ['4','Escalar al Defensor del Asegurado','Si no hay respuesta satisfactoria, la reclamación al Defensor del Asegurado es obligatoria antes de acudir a la DGSFP.'],
          ['5','Acudir a la DGSFP o juzgado','Con la resolución del Defensor del Asegurado, puedes presentar reclamación ante la DGSFP o iniciar un procedimiento judicial.'],
        ] as [$num, $title, $body])
        <div class="step-item">
          <div class="step-num-lg">{{ $num }}</div>
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
    @include('partials.cta-box', ['size' => 'sm', 'headline' => 'Generar carta de reclamación al seguro de salud', 'sub' => 'Con base legal LCS y adaptada a tu aseguradora. Lista en 90 segundos.'])
  </div>
</section>

<section class="lp-section lp-section--alt">
  <div class="container">
    <div style="max-width:760px;margin:0 auto">
      <span style="font-size:var(--t-xs);font-weight:var(--fw-bold);letter-spacing:.08em;text-transform:uppercase;color:var(--lime)">FAQ</span>
      <h2 style="margin-top:var(--sp-3);margin-bottom:var(--sp-8)">Preguntas frecuentes sobre seguros de salud</h2>
      @foreach([
        ['¿Puede el seguro negarme un tratamiento por enfermedad preexistente?','Solo si la exclusión fue comunicada y aceptada expresamente al contratar (art. 3 LCS). Si el seguro no te informó de las exclusiones en el momento de la contratación, esas cláusulas son nulas y el tratamiento debe cubrirse.'],
        ['¿Cuál es el plazo para que el seguro autorice una intervención?','La normativa no fija un plazo universal. Sin embargo, la demora injustificada en atender una solicitud urgente constituye un incumplimiento reclamable. La DGSFP puede sancionar a la aseguradora por demoras sistemáticas.'],
        ['¿Qué pasa si me dan el alta antes de tiempo y tengo que reingresar?','Si el reingreso es consecuencia directa del alta prematura, la aseguradora debe cubrir los costes del reingreso. Documenta que el reinternamiento fue necesario por la misma causa.'],
        ['¿Puedo reclamar al seguro de salud por facturación incorrecta?','Sí. Si la aseguradora te cobra copagos que no están estipulados en la póliza, o servicios que deberían estar cubiertos, tienes derecho a reclamar la devolución del importe indebidamente cobrado.'],
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
    <h2 style="margin-bottom:var(--sp-3)">Reclamaciones relacionadas</h2>
    <div class="interlinking">
      <a href="{{ route('seo.vida') }}" class="ilink-card"><span class="il-icon">❤️</span><div class="il-title">Seguro de vida</div><div class="il-sub">Invalidez, fallecimiento →</div></a>
      <a href="{{ route('seo.hogar') }}" class="ilink-card"><span class="il-icon">🏠</span><div class="il-title">Seguro de hogar</div><div class="il-sub">Daños, siniestros →</div></a>
      <a href="{{ route('blog.show', 'defensor-asegurado-vs-dgsfp') }}" class="ilink-card"><span class="il-icon">⚖️</span><div class="il-title">Defensor vs DGSFP</div><div class="il-sub">Cuándo usar cada vía →</div></a>
      <a href="{{ route('seo.reclamaciones') }}" class="ilink-card"><span class="il-icon">📋</span><div class="il-title">Todas las reclamaciones</div><div class="il-sub">Hub completo →</div></a>
    </div>
  </div>
</section>

<section style="padding: var(--sp-16) 0; background: var(--bg-section); border-top: 1px solid var(--border);">
  <div class="container">
    @include('partials.cta-box', ['headline' => 'Reclamar al seguro de salud ahora', 'sub' => 'Carta con base legal LCS, adaptada a tu aseguradora. Lista en menos de 90 segundos.'])
  </div>
</section>

@endsection
