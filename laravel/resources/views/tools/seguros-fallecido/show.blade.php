@extends('layouts.dashboard')
@section('title', 'Expediente RCSCF — {{ $search->deceased_name }}')
@section('page-title', 'Expediente RCSCF')

@push('styles')
<style>
.rcscf-progress {
  display: flex; gap: 0; margin-bottom: var(--sp-8);
  border-radius: var(--r-lg); overflow: hidden;
  border: 1px solid var(--border);
}
.rcscf-step {
  flex: 1; padding: var(--sp-3) var(--sp-2); text-align: center;
  font-size: 10px; font-weight: var(--fw-semibold); letter-spacing: .05em;
  text-transform: uppercase; background: var(--bg-card); color: var(--text-3);
  border-right: 1px solid var(--border); position: relative;
}
.rcscf-step:last-child { border-right: none; }
.rcscf-step.active  { background: var(--b-600); color: #fff; }
.rcscf-step.done    { background: var(--b-50);  color: var(--b-700); }
.rcscf-step.success { background: #f0fdf4; color: #15803d; }
.rcscf-step.danger  { background: #fef2f2; color: #b91c1c; }
.rcscf-step-num { display: block; font-size: 1rem; margin-bottom: 2px; }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: var(--sp-6); }
@media (max-width: 640px) { .info-grid { grid-template-columns: 1fr; } }

.info-block h3 {
  font-size: var(--t-xs); font-weight: var(--fw-bold); text-transform: uppercase;
  letter-spacing: .07em; color: var(--text-3); margin: 0 0 var(--sp-3);
}
.info-row { display: flex; gap: var(--sp-3); padding: var(--sp-2) 0;
  border-bottom: 1px solid var(--border); font-size: var(--t-sm); }
.info-row:last-child { border-bottom: none; }
.info-key { color: var(--text-3); min-width: 130px; flex-shrink: 0; }
.info-val { color: var(--text); font-weight: var(--fw-medium); }

.next-step-box {
  background: var(--b-50); border: 1px solid var(--b-200);
  border-radius: var(--r-lg); padding: var(--sp-5);
  margin-bottom: var(--sp-6);
}
.next-step-box h3 { font-size: var(--t-sm); font-weight: var(--fw-bold); color: var(--b-800); margin: 0 0 var(--sp-2); }
.next-step-box p  { font-size: var(--t-sm); color: var(--b-700); margin: 0; }

.doc-checklist { list-style: none; padding: 0; margin: 0; }
.doc-checklist li {
  display: flex; align-items: flex-start; gap: var(--sp-3);
  padding: var(--sp-3) 0; border-bottom: 1px solid var(--border);
  font-size: var(--t-sm);
}
.doc-checklist li:last-child { border-bottom: none; }
.doc-check { width: 18px; height: 18px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 10px; margin-top: 1px; }
.doc-check.ok   { background: #dcfce7; color: #15803d; }
.doc-check.pend { background: var(--s-100); color: var(--s-500); border: 1px solid var(--s-200); }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success fade-up" role="alert">{{ session('success') }}</div>
@endif

{{-- Progress bar --}}
@php
$steps = [
  ['pendiente_documentacion', '📋', 'Documentación'],
  ['tramite_iniciado',        '📤', 'Trámite RCSCF'],
  ['certificado_recibido',    '📄', 'Certificado'],
  ['seguro_encontrado',       '✓',  'Seguro hallado'],
  ['reclamacion_enviada',     '✉️',  'Reclamación'],
  ['cobrado',                 '💶', 'Cobrado'],
];
$statusOrder = ['pendiente_documentacion'=>0,'tramite_iniciado'=>1,'certificado_recibido'=>2,'seguro_encontrado'=>3,'seguro_no_encontrado'=>3,'reclamacion_enviada'=>4,'cobrado'=>5];
$currentIdx  = $statusOrder[$search->status] ?? 0;
@endphp
<div class="rcscf-progress fade-up" role="progressbar" aria-label="Estado del expediente">
  @foreach($steps as $i => [$key, $icon, $label])
    @php
      $cls = $i < $currentIdx ? 'done' : ($i === $currentIdx ? ($search->status === 'seguro_no_encontrado' ? 'danger' : ($search->status === 'cobrado' ? 'success' : 'active')) : '');
    @endphp
    <div class="rcscf-step {{ $cls }}">
      <span class="rcscf-step-num">{{ $icon }}</span>
      {{ $label }}
    </div>
  @endforeach
</div>

<div class="row g-6 fade-up delay-1">
  <div class="col-lg-8">

    {{-- Siguiente paso --}}
    <div class="next-step-box">
      <h3>👉 Siguiente paso</h3>
      <p>{{ $search->nextStep() }}</p>
      @if($search->status === 'pendiente_documentacion')
        <a href="https://sede.mjusticia.gob.es/es/tramites/certificado-registro-de-seguros" target="_blank" rel="noopener"
           class="btn btn-primary btn-sm" style="margin-top:var(--sp-3)">
          Ir al trámite oficial del Ministerio de Justicia →
        </a>
      @endif
    </div>

    {{-- Info del expediente --}}
    <div class="section-card" style="margin-bottom:var(--sp-6)">
      <div class="section-card-header"><h2>Datos del expediente</h2></div>
      <div style="padding:var(--sp-6)">
        <div class="info-grid">
          <div class="info-block">
            <h3>Fallecido</h3>
            <div class="info-row"><span class="info-key">Nombre</span><span class="info-val">{{ $search->deceased_name }}</span></div>
            <div class="info-row"><span class="info-key">DNI/NIE</span><span class="info-val">{{ $search->deceased_dni }}</span></div>
            @if($search->deceased_birth_date)
            <div class="info-row"><span class="info-key">Nacimiento</span><span class="info-val">{{ $search->deceased_birth_date->format('d/m/Y') }}</span></div>
            @endif
            <div class="info-row"><span class="info-key">Fallecimiento</span><span class="info-val">{{ $search->deceased_death_date->format('d/m/Y') }}</span></div>
            @if($search->deceased_province)
            <div class="info-row"><span class="info-key">Provincia</span><span class="info-val">{{ $search->deceased_province }}</span></div>
            @endif
          </div>
          <div class="info-block">
            <h3>Solicitante</h3>
            <div class="info-row"><span class="info-key">Nombre</span><span class="info-val">{{ $search->applicant_name }}</span></div>
            <div class="info-row"><span class="info-key">DNI/NIE</span><span class="info-val">{{ $search->applicant_dni }}</span></div>
            <div class="info-row"><span class="info-key">Relación</span><span class="info-val">{{ $search->applicant_relationship }}</span></div>
            @if($search->applicant_email)
            <div class="info-row"><span class="info-key">Email</span><span class="info-val">{{ $search->applicant_email }}</span></div>
            @endif
            @if($search->applicant_phone)
            <div class="info-row"><span class="info-key">Teléfono</span><span class="info-val">{{ $search->applicant_phone }}</span></div>
            @endif
          </div>
        </div>

        @if($search->insurer_found)
        <hr style="border-color:var(--border);margin:var(--sp-4) 0">
        <div class="info-block">
          <h3>Resultado RCSCF</h3>
          <div class="info-row"><span class="info-key">Aseguradora</span><span class="info-val" style="color:var(--success);font-weight:var(--fw-bold)">{{ $search->insurer_found }}</span></div>
          @if($search->policy_type)
          <div class="info-row"><span class="info-key">Tipo de seguro</span><span class="info-val">{{ $search->policy_type }}</span></div>
          @endif
          @if($search->insured_amount)
          <div class="info-row"><span class="info-key">Capital asegurado</span><span class="info-val" style="font-size:var(--t-lg);font-weight:var(--fw-extrabold)">{{ number_format($search->insured_amount, 0, ',', '.') }} €</span></div>
          @endif
        </div>
        @endif

        @if($search->notes)
        <hr style="border-color:var(--border);margin:var(--sp-4) 0">
        <div class="info-block">
          <h3>Notas internas</h3>
          <p style="font-size:var(--t-sm);color:var(--text-2);margin:0">{{ $search->notes }}</p>
        </div>
        @endif
      </div>
    </div>

    {{-- Checklist de documentos --}}
    <div class="section-card">
      <div class="section-card-header"><h2>Documentación necesaria</h2></div>
      <div style="padding:var(--sp-5) var(--sp-6)">
        <ul class="doc-checklist" aria-label="Documentos requeridos para el trámite RCSCF">
          @php
          $hasTramite = in_array($search->status, ['tramite_iniciado','certificado_recibido','seguro_encontrado','seguro_no_encontrado','reclamacion_enviada','cobrado']);
          $hasCert    = in_array($search->status, ['certificado_recibido','seguro_encontrado','reclamacion_enviada','cobrado']);
          $hasSeguro  = in_array($search->status, ['seguro_encontrado','reclamacion_enviada','cobrado']);
          @endphp
          <li>
            <span class="doc-check ok" aria-hidden="true">✓</span>
            <div><strong>DNI/NIE del fallecido</strong><br><span style="color:var(--text-3);font-size:var(--t-xs)">Registrado en el expediente</span></div>
          </li>
          <li>
            <span class="doc-check {{ $hasTramite ? 'ok' : 'pend' }}" aria-hidden="true">{{ $hasTramite ? '✓' : '·' }}</span>
            <div><strong>Certificado literal de defunción</strong><br><span style="color:var(--text-3);font-size:var(--t-xs)">Registro Civil o Registro Civil Central. Plazo: inmediato.</span></div>
          </li>
          <li>
            <span class="doc-check {{ $hasTramite ? 'ok' : 'pend' }}" aria-hidden="true">{{ $hasTramite ? '✓' : '·' }}</span>
            <div><strong>DNI/NIE del solicitante</strong><br><span style="color:var(--text-3);font-size:var(--t-xs)">Heredero, beneficiario o representante legal</span></div>
          </li>
          <li>
            <span class="doc-check {{ $hasTramite ? 'ok' : 'pend' }}" aria-hidden="true">{{ $hasTramite ? '✓' : '·' }}</span>
            <div>
              <strong>Pago de tasas — 3,70 €</strong><br>
              <span style="color:var(--text-3);font-size:var(--t-xs)">Modelo 790 código 006 · Pago online en sede.mjusticia.gob.es</span>
            </div>
          </li>
          <li>
            <span class="doc-check {{ $hasCert ? 'ok' : 'pend' }}" aria-hidden="true">{{ $hasCert ? '✓' : '·' }}</span>
            <div><strong>Certificado RCSCF del Ministerio</strong><br><span style="color:var(--text-3);font-size:var(--t-xs)">Llega por correo postal en 10-15 días hábiles</span></div>
          </li>
          @if($hasSeguro)
          <li>
            <span class="doc-check ok" aria-hidden="true">✓</span>
            <div><strong>Seguro localizado — {{ $search->insurer_found }}</strong><br><span style="color:var(--text-3);font-size:var(--t-xs)">Listo para reclamar el capital asegurado</span></div>
          </li>
          @endif
        </ul>
      </div>
    </div>

  </div>

  {{-- Sidebar: actualizar estado --}}
  <div class="col-lg-4">
    <div class="section-card" style="position:sticky;top:80px">
      <div class="section-card-header"><h2>Actualizar estado</h2></div>
      <div style="padding:var(--sp-5)">
        <form method="POST" action="{{ route('tools.fallecido.update', $search) }}">
          @csrf @method('PATCH')

          <div class="mb-4">
            <label class="form-label" for="status">Estado del expediente</label>
            <select id="status" name="status" class="form-control">
              @foreach([
                'pendiente_documentacion' => 'Pendiente documentación',
                'tramite_iniciado'        => 'Trámite iniciado en Justicia',
                'certificado_recibido'    => 'Certificado RCSCF recibido',
                'seguro_encontrado'       => '✓ Seguro encontrado',
                'seguro_no_encontrado'    => '✗ Sin seguros registrados',
                'reclamacion_enviada'     => 'Reclamación enviada a aseguradora',
                'cobrado'                 => '💶 Cobrado',
              ] as $val => $label)
                <option value="{{ $val }}" {{ $search->status === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="insurer_found">Aseguradora encontrada</label>
            <input type="text" id="insurer_found" name="insurer_found"
                   value="{{ $search->insurer_found }}"
                   class="form-control" placeholder="Nombre de la compañía">
          </div>

          <div class="mb-3">
            <label class="form-label" for="policy_type">Tipo de seguro</label>
            <select id="policy_type" name="policy_type" class="form-control">
              <option value="">— Selecciona —</option>
              @foreach(['Seguro de vida','Seguro de decesos','Seguro de vida-ahorro','Seguro de vida con cobertura de accidentes','Otro'] as $t)
                <option value="{{ $t }}" {{ $search->policy_type === $t ? 'selected' : '' }}>{{ $t }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="insured_amount">Capital asegurado (€)</label>
            <input type="number" id="insured_amount" name="insured_amount"
                   value="{{ $search->insured_amount }}"
                   class="form-control" placeholder="0.00" step="0.01" min="0">
          </div>

          <div class="mb-3">
            <label class="form-label" for="tramite_sent_at">Fecha envío trámite</label>
            <input type="date" id="tramite_sent_at" name="tramite_sent_at"
                   value="{{ $search->tramite_sent_at?->format('Y-m-d') }}" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label" for="certificate_received_at">Fecha recepción certificado</label>
            <input type="date" id="certificate_received_at" name="certificate_received_at"
                   value="{{ $search->certificate_received_at?->format('Y-m-d') }}" class="form-control">
          </div>

          <div class="mb-4">
            <label class="form-label" for="notes">Notas internas</label>
            <textarea id="notes" name="notes" rows="3" class="form-control"
                      placeholder="Observaciones del gestor...">{{ $search->notes }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
        </form>

        <hr style="border-color:var(--border);margin:var(--sp-5) 0">

        {{-- Links útiles --}}
        <h3 style="font-size:var(--t-xs);font-weight:var(--fw-bold);text-transform:uppercase;letter-spacing:.07em;color:var(--text-3);margin:0 0 var(--sp-3)">
          Enlaces oficiales
        </h3>
        <div style="display:flex;flex-direction:column;gap:var(--sp-2)">
          <a href="https://sede.mjusticia.gob.es/es/tramites/certificado-registro-de-seguros"
             target="_blank" rel="noopener"
             class="btn btn-secondary btn-sm w-100">
            📋 Trámite RCSCF — Sede Justicia
          </a>
          <a href="https://www.mjusticia.gob.es/es/ciudadanos/tramites/registro-contratos-seguros-cobertura"
             target="_blank" rel="noopener"
             class="btn btn-secondary btn-sm w-100">
            ℹ️ Información RCSCF oficial
          </a>
          @if($search->status === 'seguro_encontrado' && $search->insurer_found)
          <a href="{{ route('claim.create') }}?insurer_name={{ urlencode($search->insurer_found) }}&claim_type=vida"
             class="btn btn-primary btn-sm w-100" style="margin-top:var(--sp-1)">
            ✉️ Generar carta de reclamación →
          </a>
          @endif
        </div>

        <hr style="border-color:var(--border);margin:var(--sp-5) 0">

        <form method="POST" action="{{ route('tools.fallecido.destroy', $search) }}"
              onsubmit="return confirm('¿Eliminar este expediente? Esta acción no se puede deshacer.')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm w-100"
                  style="color:var(--danger);border:1px solid var(--danger);background:none">
            Eliminar expediente
          </button>
        </form>
      </div>
    </div>
  </div>

</div>

@endsection
