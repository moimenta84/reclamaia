@extends('layouts.dashboard')
@section('title', 'Expedientes — Reclama')
@section('page-title', 'Expedientes')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   DASHBOARD INDEX — Styles
   ══════════════════════════════════════════════════════════════ */

/* ─ Welcome strip ───────────────────────────────────────────── */
.welcome-strip {
  display: flex; align-items: flex-start; justify-content: space-between;
  flex-wrap: wrap; gap: var(--sp-4);
  margin-bottom: var(--sp-8);
}
.welcome-strip h1 {
  font-size: var(--t-2xl); font-weight: var(--fw-extrabold);
  letter-spacing: -.025em; color: var(--text); margin: 0;
}
.welcome-strip .welcome-date {
  font-size: var(--t-sm); color: var(--text-3); margin-top: var(--sp-1);
}
.welcome-strip .welcome-name { color: var(--accent); }

/* ─ KPI grid ────────────────────────────────────────────────── */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--sp-4);
  margin-bottom: var(--sp-8);
}
@media (max-width: 992px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .kpi-grid { grid-template-columns: 1fr 1fr; } }

.kpi-card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: var(--sp-5) var(--sp-6);
  box-shadow: var(--sh-card);
  display: flex; flex-direction: column; gap: var(--sp-3);
  transition: box-shadow var(--t-base-d) var(--ease);
}
.kpi-card:hover { box-shadow: var(--sh-md); }

.kpi-header  { display: flex; align-items: flex-start; justify-content: space-between; }
.kpi-label   {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  letter-spacing: .06em; text-transform: uppercase; color: var(--text-3);
}
.kpi-icon-wrap {
  width: 36px; height: 36px; border-radius: var(--r-md);
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; flex-shrink: 0;
}
.kpi-value {
  font-size: var(--t-3xl); font-weight: var(--fw-extrabold);
  letter-spacing: -.03em; color: var(--text); line-height: 1;
}
.kpi-value.kpi-value-md { font-size: var(--t-xl); }
.kpi-sub { font-size: var(--t-xs); color: var(--text-4); margin-top: 2px; }

/* ─ Pro tools strip ─────────────────────────────────────────── */
.tools-strip {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); box-shadow: var(--sh-card);
  padding: var(--sp-5) var(--sp-6); margin-bottom: var(--sp-8);
}
.tools-strip-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: var(--sp-5);
}
.tools-strip-header h2 {
  font-size: var(--t-sm); font-weight: var(--fw-semibold);
  color: var(--text); margin: 0;
}
.tools-strip-header a {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  color: var(--accent); text-decoration: none;
  transition: color var(--t-base-d);
}
.tools-strip-header a:hover { color: var(--accent-h); }

.tools-row {
  display: grid; grid-template-columns: repeat(5, 1fr); gap: var(--sp-3);
}
@media (max-width: 768px) { .tools-row { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 480px) { .tools-row { grid-template-columns: repeat(2, 1fr); } }

.tool-btn {
  background: var(--bg-subtle); border: 1px solid var(--border);
  border-radius: var(--r-md); padding: var(--sp-4) var(--sp-3);
  display: flex; flex-direction: column; align-items: center; gap: var(--sp-2);
  text-decoration: none; color: inherit;
  transition: background var(--t-base-d) var(--ease), box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease);
  text-align: center;
}
.tool-btn:hover {
  background: var(--bg-card); box-shadow: var(--sh-md);
  transform: translateY(-2px); color: inherit;
}
.tool-btn .tb-icon  { font-size: 1.35rem; line-height: 1; }
.tool-btn .tb-label {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  color: var(--text-2); line-height: 1.3;
}

/* ─ Pro upsell banner ───────────────────────────────────────── */
.pro-banner {
  background: linear-gradient(135deg, var(--s-900) 0%, var(--b-950) 100%);
  border: 1px solid rgba(29,78,216,.25); border-radius: var(--r-xl);
  padding: var(--sp-6) var(--sp-8); margin-bottom: var(--sp-8);
  display: flex; align-items: center; justify-content: space-between;
  gap: var(--sp-6); flex-wrap: wrap;
  position: relative; overflow: hidden;
}
.pro-banner::before {
  content: '';
  position: absolute; top: -40px; right: -40px;
  width: 200px; height: 200px; border-radius: 50%;
  background: rgba(29,78,216,.12); pointer-events: none;
}
.pro-banner-text h3 {
  font-size: var(--t-lg); font-weight: var(--fw-bold); color: #fff;
  margin-bottom: var(--sp-1);
}
.pro-banner-text p { font-size: var(--t-sm); color: var(--s-400); margin: 0; }
.pro-banner-features {
  display: flex; flex-wrap: wrap; gap: var(--sp-3); margin-top: var(--sp-3);
}
.pro-banner-feat {
  display: flex; align-items: center; gap: var(--sp-1);
  font-size: var(--t-xs); color: var(--s-400);
}
.pro-banner-feat::before { content: '✓'; color: var(--b-400); font-weight: 700; }

/* ─ Cases table section ─────────────────────────────────────── */
.section-card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-xl); box-shadow: var(--sh-card); overflow: hidden;
}
.section-card-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: var(--sp-5) var(--sp-6); border-bottom: 1px solid var(--border);
  flex-wrap: wrap; gap: var(--sp-3);
}
.section-card-header h2 {
  font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); margin: 0;
}
.section-card-meta { font-size: var(--t-xs); color: var(--text-3); }

/* Claims table */
.claims-table { width: 100%; border-collapse: collapse; }
.claims-table thead th {
  font-size: var(--t-xs); font-weight: var(--fw-semibold); letter-spacing: .06em;
  text-transform: uppercase; color: var(--text-3);
  padding: var(--sp-3) var(--sp-5); background: var(--s-25);
  border-bottom: 1px solid var(--border-md); white-space: nowrap;
}
.claims-table thead th:first-child { padding-left: var(--sp-6); }
.claims-table thead th:last-child  { padding-right: var(--sp-6); }
.claims-table tbody td {
  font-size: var(--t-sm); padding: var(--sp-4) var(--sp-5);
  border-bottom: 1px solid var(--border);
  color: var(--text-2); vertical-align: middle;
  transition: background var(--t-fast);
}
.claims-table tbody td:first-child  { padding-left: var(--sp-6); color: var(--text); font-weight: var(--fw-semibold); }
.claims-table tbody td:last-child   { padding-right: var(--sp-6); }
.claims-table tbody tr:last-child td { border-bottom: none; }
.claims-table tbody tr:hover td     { background: var(--s-25); }

.claim-insurer { font-weight: var(--fw-semibold); color: var(--text); }
.claim-date    { font-size: var(--t-xs); color: var(--text-3); white-space: nowrap; line-height: 1.6; }
.claim-actions { display: flex; align-items: center; gap: var(--sp-2); white-space: nowrap; }

/* Status badges */
.badge {
  font-family: inherit; font-size: .68rem; font-weight: var(--fw-semibold);
  padding: .2rem .6rem; border-radius: var(--r-full); letter-spacing: .01em;
  display: inline-flex; align-items: center; gap: .25rem;
}
.badge.bg-success   { background: var(--success-bg) !important; color: var(--success) !important; }
.badge.bg-warning   { background: var(--warning-bg) !important; color: var(--warning) !important; }
.badge.bg-danger    { background: var(--danger-bg)  !important; color: var(--danger)  !important; }
.badge.bg-secondary { background: var(--s-100)      !important; color: var(--s-600)   !important; }
.badge.bg-primary   { background: var(--b-50)       !important; color: var(--b-700)   !important; }
.badge.bg-info      { background: #ecfeff            !important; color: #0e7490        !important; }

/* Empty state */
.empty-state {
  padding: var(--sp-20) var(--sp-8); text-align: center;
}
.empty-icon   { font-size: 3rem; margin-bottom: var(--sp-4); opacity: .5; }
.empty-state h3 {
  font-size: var(--t-lg); font-weight: var(--fw-semibold); color: var(--text);
  margin-bottom: var(--sp-2);
}
.empty-state p {
  font-size: var(--t-sm); color: var(--text-3);
  margin-bottom: var(--sp-6); max-width: 380px; margin-inline: auto;
}

/* Pagination wrapper */
.pagination-wrap {
  padding: var(--sp-4) var(--sp-6); border-top: 1px solid var(--border);
  display: flex; justify-content: center;
}
.pagination-wrap .pagination { margin: 0; }

/* Responsive table */
@media (max-width: 768px) {
  .kpi-card { padding: var(--sp-4); }
  .kpi-value { font-size: var(--t-2xl); }
  .kpi-value.kpi-value-md { font-size: var(--t-lg); }
  .pro-banner { padding: var(--sp-5); }
  .section-card-header { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 640px) {
  /* Stack table on very small screens */
  .claims-table thead { display: none; }
  .claims-table tbody td {
    display: flex; justify-content: space-between; align-items: center;
    padding: var(--sp-2) var(--sp-4); border-bottom: none;
  }
  .claims-table tbody td:first-child { padding-left: var(--sp-4); }
  .claims-table tbody td:last-child  { padding-right: var(--sp-4); border-bottom: 1px solid var(--border); padding-bottom: var(--sp-4); }
  .claims-table tbody td[data-label]::before {
    content: attr(data-label);
    font-size: var(--t-xs); font-weight: var(--fw-semibold);
    letter-spacing: .04em; text-transform: uppercase; color: var(--text-3);
    flex-shrink: 0; margin-right: var(--sp-3);
  }
  .claims-table tbody tr { display: block; padding: var(--sp-3) 0; border-bottom: 1px solid var(--border-md); }
  .claims-table tbody tr:last-child { border-bottom: none; }
  .claims-table tbody tr:last-child td:last-child { border-bottom: none; }
}
</style>
@endpush

@section('content')

{{-- ─── Welcome strip ─────────────────────────────────────────── --}}
<div class="welcome-strip fade-up">
  <div>
    @php
      $hour = now()->hour;
      $greeting = $hour < 13 ? 'Buenos días' : ($hour < 20 ? 'Buenas tardes' : 'Buenas noches');
    @endphp
    <h1>{{ $greeting }}, <span class="welcome-name">{{ explode(' ', auth()->user()->name)[0] }}</span></h1>
    <p class="welcome-date">
      {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}
    </p>
  </div>
  <a href="{{ route('claim.create') }}" class="btn btn-primary">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
    Nuevo expediente
  </a>
</div>

{{-- ─── KPI grid ───────────────────────────────────────────────── --}}
<div class="kpi-grid fade-up delay-1" role="region" aria-label="Resumen de actividad">

  {{-- Total reclamaciones --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Total casos</span>
      <div class="kpi-icon-wrap" style="background:var(--b-50)" aria-hidden="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--b-700)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
    </div>
    <div class="kpi-value">{{ $claims->total() }}</div>
    <div class="kpi-sub">expedientes en cartera</div>
  </div>

  {{-- Completadas --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Completadas</span>
      <div class="kpi-icon-wrap" style="background:var(--success-bg)" aria-hidden="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
      </div>
    </div>
    <div class="kpi-value">{{ $claims->getCollection()->where('status', 'completed')->count() }}</div>
    <div class="kpi-sub">cartas enviadas a aseguradora</div>
  </div>

  {{-- Plan activo --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Plan activo</span>
      <div class="kpi-icon-wrap" style="background:{{ auth()->user()->hasActiveSubscription() ? 'var(--g-50)' : 'var(--s-100)' }}" aria-hidden="true">
        @if(auth()->user()->hasActiveSubscription())
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--g-600)" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        @else
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--s-500)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        @endif
      </div>
    </div>
    <div class="kpi-value kpi-value-md">
      @if(auth()->user()->hasActiveSubscription())
        <span style="color:var(--g-700)">Asesoría Pro</span>
      @else
        Por expediente
      @endif
    </div>
    <div class="kpi-sub">
      {{ auth()->user()->hasActiveSubscription() ? 'expedientes ilimitados' : 'pago por expediente' }}
    </div>
  </div>

  {{-- Estado del sistema --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Estado</span>
      <div class="kpi-icon-wrap" style="background:var(--success-bg)" aria-hidden="true">
        <div class="dot-live" aria-hidden="true"></div>
      </div>
    </div>
    <div class="kpi-value kpi-value-md" style="color:var(--success)">Activo</div>
    <div class="kpi-sub">servicio disponible 24/7</div>
  </div>

</div>

{{-- ─── Pro tools strip / Upsell ──────────────────────────────── --}}
@if(auth()->user()->hasActiveSubscription())
<div class="tools-strip fade-up delay-2" role="region" aria-label="Herramientas Pro para tu asesoría">
  <div class="tools-strip-header">
    <h2>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--g-600)" stroke-width="2" style="margin-right:var(--sp-2);vertical-align:text-bottom" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Herramientas Pro
    </h2>
    <a href="{{ route('tools.index') }}" aria-label="Ver todas las herramientas Pro">Ver todas →</a>
  </div>
  <div class="tools-row">
    <a href="{{ route('tools.baremo.show') }}" class="tool-btn" aria-label="Baremo de tráfico">
      <span class="tb-icon" aria-hidden="true">⚖️</span>
      <span class="tb-label">Baremo tráfico</span>
    </a>
    <a href="{{ route('tools.ocr.show') }}" class="tool-btn" aria-label="OCR documental">
      <span class="tb-icon" aria-hidden="true">📄</span>
      <span class="tb-label">OCR</span>
    </a>
    <a href="{{ route('tools.valoracion.show') }}" class="tool-btn" aria-label="Valoración de daños">
      <span class="tb-icon" aria-hidden="true">🚗</span>
      <span class="tb-label">Valoración</span>
    </a>
    <a href="{{ route('tools.jurisprudencia.show') }}" class="tool-btn" aria-label="Jurisprudencia CENDOJ">
      <span class="tb-icon" aria-hidden="true">📚</span>
      <span class="tb-label">Jurisprudencia</span>
    </a>
    <span class="tool-btn" style="cursor:default;opacity:.65" title="Disponible desde cualquier caso completado" aria-label="Firma digital — accede desde un caso completado">
      <span class="tb-icon" aria-hidden="true">🖋️</span>
      <span class="tb-label">Firma eIDAS</span>
    </span>
    <a href="{{ route('tools.fallecido.index') }}" class="tool-btn" aria-label="Seguros del fallecido RCSCF">
      <span class="tb-icon" aria-hidden="true">⚖️</span>
      <span class="tb-label">RCSCF</span>
    </a>
  </div>
</div>

@else
{{-- Pro upsell --}}
<div class="pro-banner fade-up delay-2" role="region" aria-label="Actualizar a Plan Pro">
  <div class="pro-banner-text">
    <h3>Activa el Plan Asesoría Pro</h3>
    <p>Herramientas profesionales para tramitar más expedientes en menos tiempo. ROI desde el primer mes.</p>
    <div class="pro-banner-features">
      <span class="pro-banner-feat">Expedientes ilimitados</span>
      <span class="pro-banner-feat">Baremo 2024</span>
      <span class="pro-banner-feat">OCR de pólizas</span>
      <span class="pro-banner-feat">Jurisprudencia CENDOJ</span>
      <span class="pro-banner-feat">Firma eIDAS clientes</span>
    </div>
  </div>
  <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-lg" style="flex-shrink:0">
    Probar 14 días gratis →
  </a>
</div>
@endif

{{-- ─── AEMET Alertas meteorológicas ───────────────────────────── --}}
<div id="aemet-widget" class="fade-up delay-3" style="margin-bottom:var(--sp-6)" role="region" aria-label="Alertas meteorológicas AEMET" aria-live="polite"></div>

{{-- ─── Cases table ────────────────────────────────────────────── --}}
<div class="section-card fade-up delay-3" role="region" aria-labelledby="historial-h">
  <div class="section-card-header">
    <h2 id="historial-h">Expedientes de la asesoría</h2>
    @if($claims->total() > 0)
      <span class="section-card-meta">
        {{ $claims->total() }} {{ $claims->total() === 1 ? 'expediente' : 'expedientes' }}
      </span>
    @endif
  </div>

  @if($claims->isEmpty())
  {{-- Empty state --}}
  <div class="empty-state">
    <div class="empty-icon" aria-hidden="true">📭</div>
    <h3>Sin expedientes todavía</h3>
    <p>
      Genera el primer expediente de tu asesoría en menos de 2 minutos.
      El sistema aplica la normativa correcta y redacta la carta por ti.
    </p>
    <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">
      Crear primer expediente →
    </a>
  </div>

  @else
  {{-- Claims table --}}
  <div class="table-responsive">
    <table class="claims-table" aria-label="Expedientes de la asesoría">
      <thead>
        <tr>
          <th scope="col">Aseguradora</th>
          <th scope="col">Ramo</th>
          <th scope="col">Fecha</th>
          <th scope="col">Estado</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($claims as $claim)
        <tr>
          <td data-label="Aseguradora" class="claim-insurer">
            {{ $claim->insurer_name ?? '—' }}
          </td>
          <td data-label="Tipo">
            <span class="badge bg-secondary">
              {{ ucfirst($claim->claim_type ?? 'Seguro') }}
            </span>
          </td>
          <td data-label="Fecha" class="claim-date">
            {{ $claim->created_at->format('d/m/Y') }}<br>
            <span style="color:var(--text-4)">{{ $claim->created_at->format('H:i') }}</span>
          </td>
          <td data-label="Estado">
            @if($claim->status === 'completed')
              <span class="badge bg-success">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m9 12 2 2 4-4"/></svg>
                Completada
              </span>
            @elseif($claim->status === 'processing')
              <span class="badge bg-warning">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Generando…
              </span>
            @elseif($claim->status === 'failed')
              <span class="badge bg-danger">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Error
              </span>
            @elseif($claim->status === 'pending_payment')
              <span class="badge bg-primary">Pago pendiente</span>
            @else
              <span class="badge bg-secondary">Pendiente</span>
            @endif
          </td>
          <td data-label="Acciones">
            <div class="claim-actions">
              @if($claim->isCompleted() && $claim->document)
                <a href="{{ route('claim.download.word', $claim) }}"
                   class="btn btn-sm btn-secondary"
                   aria-label="Descargar Word: {{ $claim->insurer_name }}">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Word
                </a>
                <a href="{{ route('claim.download.pdf', $claim) }}"
                   class="btn btn-sm btn-primary"
                   aria-label="Descargar PDF: {{ $claim->insurer_name }}">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  PDF
                </a>
                @if(auth()->user()->hasActiveSubscription())
                <a href="{{ route('tools.firma.show', $claim) }}"
                   class="btn btn-sm btn-secondary"
                   title="Firmar digitalmente"
                   aria-label="Firmar digitalmente: {{ $claim->insurer_name }}">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                  Firmar
                </a>
                @endif

              @elseif($claim->status === 'processing')
                <a href="{{ route('claim.download', $claim->id) }}" class="btn btn-sm btn-secondary">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Ver estado
                </a>

              @elseif($claim->status === 'failed')
                <a href="{{ route('claim.create') }}" class="btn btn-sm btn-danger">
                  Reintentar
                </a>

              @else
                <a href="{{ route('payment.show', $claim) }}" class="btn btn-sm btn-primary">
                  Pagar →
                </a>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if($claims->hasPages())
  <div class="pagination-wrap">
    {{ $claims->links() }}
  </div>
  @endif

  @endif
</div>

@endsection

@push('styles')
<style>
.aemet-banner {
  border-radius: var(--r-lg); padding: var(--sp-4) var(--sp-5);
  display: flex; align-items: flex-start; gap: var(--sp-4);
  margin-bottom: var(--sp-3); border: 1px solid transparent;
  animation: fadeUp .3s ease both;
}
.aemet-banner.nivel-amarillo { background:#fefce8; border-color:#fde047; }
.aemet-banner.nivel-naranja  { background:#fff7ed; border-color:#fb923c; }
.aemet-banner.nivel-rojo     { background:#fef2f2; border-color:#f87171; }
.aemet-icon { font-size:1.5rem; flex-shrink:0; line-height:1; }
.aemet-body { flex:1; min-width:0; }
.aemet-header {
  display:flex; align-items:center; gap:var(--sp-2); flex-wrap:wrap;
  margin-bottom:var(--sp-1);
}
.aemet-title { font-size:var(--t-sm); font-weight:var(--fw-bold); color:var(--text); }
.aemet-badge {
  font-size:10px; font-weight:var(--fw-bold); letter-spacing:.07em;
  text-transform:uppercase; padding:2px 8px; border-radius:var(--r-full);
}
.nivel-amarillo .aemet-badge { background:#fde047; color:#713f12; }
.nivel-naranja  .aemet-badge { background:#fb923c; color:#fff; }
.nivel-rojo     .aemet-badge { background:#ef4444; color:#fff; }
.aemet-area  { font-size:var(--t-xs); color:var(--text-3); }
.aemet-msg   { font-size:var(--t-sm); color:var(--text-2); line-height:1.55; margin-top:var(--sp-1); }
.aemet-seguros {
  display:flex; flex-wrap:wrap; gap:var(--sp-1); margin-top:var(--sp-2);
}
.aemet-seg-pill {
  font-size:10px; font-weight:var(--fw-semibold); letter-spacing:.04em;
  text-transform:uppercase; padding:2px 8px; border-radius:var(--r-full);
  background:rgba(59,130,246,.1); color:var(--b-700); border:1px solid rgba(59,130,246,.2);
}
.aemet-dismiss {
  flex-shrink:0; background:none; border:none; cursor:pointer;
  color:var(--text-4); font-size:1rem; padding:2px; line-height:1;
  transition:color var(--t-base-d);
}
.aemet-dismiss:hover { color:var(--text); }
</style>
@endpush

@push('scripts')
<script>
(function() {
  var widget = document.getElementById('aemet-widget');
  if (!widget) return;

  // Dismissed alerts stored in sessionStorage
  var dismissed = JSON.parse(sessionStorage.getItem('aemet_dismissed') || '[]');

  fetch('/api/aemet/alertas', {
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
  })
  .then(function(r) { return r.ok ? r.json() : null; })
  .then(function(data) {
    if (!data || !data.alertas || !data.alertas.length) return;
    var alerts = data.alertas.filter(function(a) {
      return dismissed.indexOf(a.tipo + '|' + a.area) === -1;
    });
    if (!alerts.length) return;

    var html = '';
    alerts.forEach(function(a) {
      var nivel = (a.nivel || 'amarillo').toLowerCase();
      var key   = a.tipo + '|' + a.area;
      var seguros = (a.seguros_afectados || []).map(function(s) {
        return '<span class="aemet-seg-pill">'+s+'</span>';
      }).join('');

      html += '<div class="aemet-banner nivel-'+nivel+'" data-key="'+key+'" role="alert">';
      html += '<div class="aemet-icon" aria-hidden="true">'+a.icon+'</div>';
      html += '<div class="aemet-body">';
      html += '<div class="aemet-header">';
      html += '<span class="aemet-title">'+a.tipo+'</span>';
      html += '<span class="aemet-badge">'+a.nivel+'</span>';
      if (a.area) html += '<span class="aemet-area">· '+a.area+'</span>';
      html += '</div>';
      html += '<p class="aemet-msg">'+a.consejo+'</p>';
      if (seguros) html += '<div class="aemet-seguros">'+seguros+'</div>';
      html += '</div>';
      html += '<button class="aemet-dismiss" aria-label="Cerrar alerta '+a.tipo+'" onclick="aemetDismiss(\''+key+'\',this.closest(\'.aemet-banner\'))">✕</button>';
      html += '</div>';
    });
    widget.innerHTML = html;
  })
  .catch(function() {});

  window.aemetDismiss = function(key, el) {
    var d = JSON.parse(sessionStorage.getItem('aemet_dismissed') || '[]');
    d.push(key);
    sessionStorage.setItem('aemet_dismissed', JSON.stringify(d));
    el.style.opacity = '0';
    el.style.transition = 'opacity .2s';
    setTimeout(function() { el.remove(); }, 200);
  };
})();
</script>
@endpush
