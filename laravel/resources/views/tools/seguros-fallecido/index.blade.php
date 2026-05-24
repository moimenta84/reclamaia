@extends('layouts.dashboard')
@section('title', 'Seguros del Fallecido — Reclama')
@section('page-title', 'Seguros del Fallecido (RCSCF)')

@push('styles')
<style>
.rcscf-header {
  background: linear-gradient(135deg, var(--s-900) 0%, #1e3a5f 100%);
  border-radius: var(--r-xl); padding: var(--sp-8) var(--sp-8);
  color: #fff; margin-bottom: var(--sp-8); position: relative; overflow: hidden;
}
.rcscf-header::before {
  content: '⚖️'; position: absolute; right: var(--sp-8); top: 50%;
  transform: translateY(-50%); font-size: 5rem; opacity: .08;
}
.rcscf-header h1 { font-size: var(--t-2xl); font-weight: var(--fw-extrabold); margin: 0 0 var(--sp-2); }
.rcscf-header p  { color: var(--s-300); margin: 0; font-size: var(--t-sm); max-width: 520px; }
.rcscf-stats { display: flex; gap: var(--sp-6); margin-top: var(--sp-6); flex-wrap: wrap; }
.rcscf-stat  { text-align: center; }
.rcscf-stat-val  { font-size: var(--t-2xl); font-weight: var(--fw-extrabold); color: #fff; }
.rcscf-stat-lbl  { font-size: var(--t-xs); color: var(--s-400); text-transform: uppercase; letter-spacing: .06em; }

.search-table { width: 100%; border-collapse: collapse; }
.search-table th {
  font-size: var(--t-xs); font-weight: var(--fw-semibold); text-transform: uppercase;
  letter-spacing: .07em; color: var(--text-3); padding: var(--sp-3) var(--sp-4);
  border-bottom: 1px solid var(--border); text-align: left;
}
.search-table td {
  padding: var(--sp-4); border-bottom: 1px solid var(--border);
  font-size: var(--t-sm); color: var(--text);
  vertical-align: middle;
}
.search-table tbody tr:hover { background: var(--bg-hover); }
.search-table tbody tr:last-child td { border-bottom: none; }
.deceased-name { font-weight: var(--fw-semibold); color: var(--text); }
.deceased-meta { font-size: var(--t-xs); color: var(--text-3); margin-top: 2px; }
</style>
@endpush

@section('content')

<div class="rcscf-header fade-up">
  <h1>Búsqueda de Seguros del Fallecido</h1>
  <p>
    Localiza seguros de vida y decesos no reclamados a través del Registro de Contratos
    de Seguros de Cobertura de Fallecimiento (RCSCF) del Ministerio de Justicia.
  </p>
  <div class="rcscf-stats">
    <div class="rcscf-stat">
      <div class="rcscf-stat-val">{{ $searches->total() }}</div>
      <div class="rcscf-stat-lbl">Expedientes</div>
    </div>
    <div class="rcscf-stat">
      <div class="rcscf-stat-val">{{ $searches->getCollection()->where('status','seguro_encontrado')->count() + $searches->getCollection()->where('status','cobrado')->count() }}</div>
      <div class="rcscf-stat-lbl">Seguros encontrados</div>
    </div>
    <div class="rcscf-stat">
      <div class="rcscf-stat-val">{{ $searches->getCollection()->where('status','cobrado')->count() }}</div>
      <div class="rcscf-stat-lbl">Cobrados</div>
    </div>
  </div>
</div>

<div class="section-card fade-up delay-1">
  <div class="section-card-header">
    <h2>Expedientes RCSCF</h2>
    <a href="{{ route('tools.fallecido.create') }}" class="btn btn-primary btn-sm">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
      Nuevo expediente
    </a>
  </div>

  @if($searches->isEmpty())
  <div class="empty-state">
    <div class="empty-icon" aria-hidden="true">🔍</div>
    <h3>Sin expedientes todavía</h3>
    <p>Crea el primer expediente para localizar seguros de vida o decesos de un fallecido.</p>
    <a href="{{ route('tools.fallecido.create') }}" class="btn btn-primary btn-lg">
      Crear primer expediente →
    </a>
  </div>
  @else
  <div class="table-responsive">
    <table class="search-table" aria-label="Expedientes RCSCF">
      <thead>
        <tr>
          <th>Fallecido</th>
          <th>Solicitante</th>
          <th>Fallecimiento</th>
          <th>Estado</th>
          <th>Importe</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($searches as $s)
        <tr>
          <td>
            <div class="deceased-name">{{ $s->deceased_name }}</div>
            <div class="deceased-meta">DNI: {{ $s->deceased_dni }}</div>
          </td>
          <td>
            <div>{{ $s->applicant_name }}</div>
            <div class="deceased-meta">{{ ucfirst($s->applicant_relationship) }}</div>
          </td>
          <td>
            <div>{{ $s->deceased_death_date->format('d/m/Y') }}</div>
            @if($s->deceased_province)
              <div class="deceased-meta">{{ $s->deceased_province }}</div>
            @endif
          </td>
          <td>
            <span class="badge bg-{{ $s->statusColor() }}">{{ $s->statusLabel() }}</span>
          </td>
          <td>
            @if($s->insured_amount)
              <strong>{{ number_format($s->insured_amount, 0, ',', '.') }} €</strong>
            @else
              <span style="color:var(--text-4)">—</span>
            @endif
          </td>
          <td>
            <a href="{{ route('tools.fallecido.show', $s) }}" class="btn btn-sm btn-secondary">
              Ver →
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if($searches->hasPages())
  <div class="pagination-wrap">{{ $searches->links() }}</div>
  @endif
  @endif
</div>

@endsection
