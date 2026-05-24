@extends('layouts.app')
@section('title', 'Valoración de daños vehiculares — Reclama')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <header class="mb-4">
            <a href="{{ route('tools.index') }}" class="small text-muted text-decoration-none">← Herramientas</a>
            <h1 class="fw-bold mt-2 mb-1">Valoración de daños vehiculares</h1>
            <p class="text-muted mb-0">Tasación de daños vía DAT Ibérica, Audatex o GT Estimate según configuración.</p>
        </header>

        <div class="card p-4 p-md-5">
            <form id="valoracion-form" novalidate aria-label="Valoración de daños vehiculares">
                @csrf

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Datos del vehículo</legend>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="matricula">Matrícula <span class="required" aria-hidden="true">*</span></label>
                            <input type="text" id="matricula" name="matricula" class="form-control"
                                placeholder="0000 AAA" required aria-required="true"
                                aria-describedby="matricula_hint" autocomplete="off" style="text-transform:uppercase">
                            <div id="matricula_hint" class="form-text">Formato: 0000 AAA o AB-1234-CD (clásicos)</div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="marca">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control"
                                placeholder="p.ej. Seat" autocomplete="off">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="modelo">Modelo</label>
                            <input type="text" id="modelo" name="modelo" class="form-control"
                                placeholder="p.ej. Ibiza" autocomplete="off">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="anio">Año</label>
                            <input type="number" id="anio" name="anio" class="form-control"
                                min="1950" max="{{ date('Y') }}" placeholder="{{ date('Y') - 5 }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="kilometros">Kilómetros</label>
                            <input type="number" id="kilometros" name="kilometros" class="form-control"
                                min="0" step="1000" placeholder="50000">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="combustible">Combustible</label>
                            <select id="combustible" name="combustible" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diésel</option>
                                <option value="hibrido">Híbrido</option>
                                <option value="electrico">Eléctrico</option>
                                <option value="glp">GLP/GNC</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Descripción de daños</legend>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="descripcion_danos">Descripción de los daños <span class="required" aria-hidden="true">*</span></label>
                            <textarea id="descripcion_danos" name="descripcion_danos" rows="4" class="form-control"
                                required aria-required="true" aria-describedby="desc_hint"
                                placeholder="Describe los daños: zonas afectadas, golpes, abolladuras, daños mecánicos, cristales rotos..."></textarea>
                            <div id="desc_hint" class="form-text">Cuanto más detallado, más precisa será la valoración.</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="zona_danos">Zona principal afectada</label>
                            <select id="zona_danos" name="zona_danos" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="frontal">Frontal</option>
                                <option value="trasera">Trasera</option>
                                <option value="lateral_derecho">Lateral derecho</option>
                                <option value="lateral_izquierdo">Lateral izquierdo</option>
                                <option value="techo">Techo</option>
                                <option value="multiple">Múltiples zonas</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="gravedad">Gravedad estimada</label>
                            <select id="gravedad" name="gravedad" class="form-select">
                                <option value="leve">Leve (cosmético)</option>
                                <option value="moderado" selected>Moderado</option>
                                <option value="grave">Grave (estructural)</option>
                                <option value="total">Pérdida total posible</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <button type="submit" id="val-submit" class="btn btn-primary btn-lg w-100">
                    <span id="val-submit-text">Calcular valoración</span>
                    <span id="val-spinner" class="d-none spinner-border spinner-border-sm ms-2" role="status" aria-label="Calculando..."></span>
                </button>
            </form>
        </div>

        <div id="valoracion-result" class="card p-4 p-md-5 mt-4 d-none" role="region" aria-live="polite" aria-label="Resultado de la valoración">
            <h2 class="h5 fw-bold mb-3">Valoración estimada</h2>
            <div class="row g-3 mb-3" id="val-breakdown"></div>
            <div class="alert alert-primary mb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Coste total estimado de reparación:</strong>
                    <span class="fs-3 fw-bold" id="val-total"></span>
                </div>
            </div>
            <p class="form-text mt-2 mb-0">
                Valoración orientativa. Para peritación oficial, el proveedor configurado
                (DAT Ibérica, Audatex o GT Estimate) emitirá el informe certificado.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('valoracion-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = Object.fromEntries(new FormData(form).entries());

    document.getElementById('val-submit-text').textContent = 'Calculando…';
    document.getElementById('val-spinner').classList.remove('d-none');
    document.getElementById('val-submit').disabled = true;

    const res = await fetch('{{ route('tools.valoracion.calculate') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify(data),
    });
    const r = await res.json();

    document.getElementById('val-submit-text').textContent = 'Calcular valoración';
    document.getElementById('val-spinner').classList.add('d-none');
    document.getElementById('val-submit').disabled = false;

    if (!res.ok) { alert(r.error || 'Error al calcular la valoración.'); return; }

    const fmt = (v) => new Intl.NumberFormat('es-ES', {style:'currency',currency:'EUR'}).format(v);
    const bd = document.getElementById('val-breakdown');
    const items = r.breakdown || {};
    bd.innerHTML = Object.entries(items).map(([k,v]) =>
        `<div class="col-sm-4"><div class="card p-3 h-100"><div class="text-muted small">${k}</div><div class="fw-bold">${typeof v === 'number' ? fmt(v) : v}</div></div></div>`
    ).join('');
    document.getElementById('val-total').textContent = fmt(r.total || 0);
    document.getElementById('valoracion-result').classList.remove('d-none');
    document.getElementById('valoracion-result').scrollIntoView({behavior:'smooth', block:'start'});
});
</script>
@endpush
