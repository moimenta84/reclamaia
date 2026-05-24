@extends('layouts.app')
@section('title', 'Jurisprudencia CENDOJ — Reclama')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <header class="mb-4">
            <a href="{{ route('tools.index') }}" class="small text-muted text-decoration-none">← Herramientas</a>
            <h1 class="fw-bold mt-2 mb-1">Jurisprudencia CENDOJ</h1>
            <p class="text-muted mb-0">Busca sentencias del Tribunal Supremo y Audiencias Provinciales aplicables a tu reclamación.</p>
        </header>

        <div class="card p-4 p-md-5">
            <form id="juri-form" novalidate aria-label="Búsqueda de jurisprudencia">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label" for="descripcion">Descripción del caso <span class="required" aria-hidden="true">*</span></label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-control"
                            required aria-required="true" aria-describedby="desc_hint"
                            placeholder="Describe tu caso: tipo de seguro, incidencia, respuesta de la aseguradora, importe reclamado..."></textarea>
                        <div id="desc_hint" class="form-text">El sistema analizará el caso y buscará sentencias relevantes en CENDOJ.</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="aseguradora">Aseguradora</label>
                        <input type="text" id="aseguradora" name="aseguradora" class="form-control"
                            placeholder="p.ej. Mapfre, Allianz, AXA...">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="tipo_reclamacion">Tipo de reclamación</label>
                        <select id="tipo_reclamacion" name="tipo_reclamacion" class="form-select">
                            <option value="">— Seleccionar —</option>
                            <option value="hogar">Hogar</option>
                            <option value="auto">Auto</option>
                            <option value="salud">Salud / Vida</option>
                            <option value="viaje">Viaje</option>
                            <option value="comercio">Comercio / Empresa</option>
                            <option value="responsabilidad_civil">Responsabilidad civil</option>
                            <option value="decesos">Decesos</option>
                        </select>
                    </div>
                </div>

                <button type="submit" id="juri-submit" class="btn btn-primary btn-lg w-100">
                    <span id="juri-submit-text">Buscar jurisprudencia</span>
                    <span id="juri-spinner" class="d-none spinner-border spinner-border-sm ms-2" role="status" aria-label="Buscando..."></span>
                </button>
            </form>
        </div>

        <div id="juri-result" class="mt-4 d-none" role="region" aria-live="polite" aria-label="Resultados de jurisprudencia">
            <div class="card p-4 mb-3" id="juri-summary-card">
                <h2 class="h5 fw-bold mb-2">Análisis jurídico</h2>
                <p id="juri-argumentacion" class="mb-0"></p>
            </div>

            <h2 class="h6 fw-semibold mb-3 text-muted">Sentencias encontradas</h2>
            <div id="juri-sentencias"></div>

            <div class="card p-3 mt-3" id="juri-articulos-card">
                <h3 class="h6 fw-bold mb-2">Artículos aplicables</h3>
                <ul id="juri-articulos" class="mb-0 small"></ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('juri-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = Object.fromEntries(new FormData(form).entries());

    document.getElementById('juri-submit-text').textContent = 'Buscando…';
    document.getElementById('juri-spinner').classList.remove('d-none');
    document.getElementById('juri-submit').disabled = true;
    document.getElementById('juri-result').classList.add('d-none');

    const res = await fetch('{{ route('tools.jurisprudencia.search') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify(data),
    });
    const r = await res.json();

    document.getElementById('juri-submit-text').textContent = 'Buscar jurisprudencia';
    document.getElementById('juri-spinner').classList.add('d-none');
    document.getElementById('juri-submit').disabled = false;

    if (!res.ok) { alert(r.error || 'Error al buscar jurisprudencia.'); return; }

    document.getElementById('juri-argumentacion').textContent = r.argumentacion_clave || '—';

    const sentenciasEl = document.getElementById('juri-sentencias');
    const sentencias = r.sentencias || [];
    if (sentencias.length === 0) {
        sentenciasEl.innerHTML = '<p class="text-muted small">No se encontraron sentencias relevantes.</p>';
    } else {
        sentenciasEl.innerHTML = sentencias.map(s => `
            <div class="card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <h3 class="h6 fw-bold mb-0">${escHtml(s.tribunal || '—')}</h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary text-nowrap">${escHtml(s.fecha || '')}</span>
                </div>
                ${s.numero_sentencia ? `<div class="text-muted small mb-2">Sentencia ${escHtml(s.numero_sentencia)}</div>` : ''}
                <p class="small mb-2">${escHtml(s.resumen || s.extracto || '—')}</p>
                ${s.fallo ? `<div class="small p-2 rounded" style="background:#f0fdf4"><strong>Fallo:</strong> ${escHtml(s.fallo)}</div>` : ''}
                ${s.url ? `<a href="${escHtml(s.url)}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm mt-2">Ver en CENDOJ →</a>` : ''}
            </div>
        `).join('');
    }

    const arts = r.articulos_aplicables || [];
    const artsEl = document.getElementById('juri-articulos');
    artsEl.innerHTML = arts.length
        ? arts.map(a => `<li>${escHtml(a)}</li>`).join('')
        : '<li class="text-muted">—</li>';

    document.getElementById('juri-result').classList.remove('d-none');
    document.getElementById('juri-result').scrollIntoView({behavior:'smooth', block:'start'});
});

function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush
