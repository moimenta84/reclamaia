@extends('layouts.app')
@section('title', 'Baremo de tráfico — Reclama')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <header class="mb-4">
            <a href="{{ route('tools.index') }}" class="small text-muted text-decoration-none">← Herramientas</a>
            <h1 class="fw-bold mt-2 mb-1">Baremo de tráfico</h1>
            <p class="text-muted mb-0">Cálculo de indemnización por accidente de tráfico según la Ley 35/2015 (actualización 2024).</p>
        </header>

        <div class="card p-4 p-md-5">
            <form id="baremo-form" novalidate aria-label="Calculadora de baremo de tráfico">
                @csrf

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Datos personales</legend>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="edad">Edad <span class="required" aria-hidden="true">*</span></label>
                            <input type="number" id="edad" name="edad" min="0" max="120" value="40" class="form-control" required aria-required="true">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="ingresos_anuales">Ingresos anuales (€)</label>
                            <input type="number" id="ingresos_anuales" name="ingresos_anuales" min="0" step="100" value="0" class="form-control" inputmode="numeric">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Días de perjuicio personal</legend>
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <label class="form-label" for="dias_muy_grave">Muy grave (UCI)</label>
                            <input type="number" id="dias_muy_grave" name="dias_muy_grave" min="0" value="0" class="form-control">
                            <div class="form-text">115,80 €/día</div>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="dias_grave">Grave (hospital)</label>
                            <input type="number" id="dias_grave" name="dias_grave" min="0" value="0" class="form-control">
                            <div class="form-text">78,32 €/día</div>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="dias_moderado">Moderado (baja)</label>
                            <input type="number" id="dias_moderado" name="dias_moderado" min="0" value="0" class="form-control">
                            <div class="form-text">56,08 €/día</div>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="dias_basico">Básico</label>
                            <input type="number" id="dias_basico" name="dias_basico" min="0" value="0" class="form-control">
                            <div class="form-text">33,28 €/día</div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Secuelas y estética</legend>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="puntos_secuelas">Puntos de secuelas (0-100)</label>
                            <input type="number" id="puntos_secuelas" name="puntos_secuelas" min="0" max="100" step="0.5" value="0" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="puntos_esteticos">Puntos perjuicio estético (0-50)</label>
                            <input type="number" id="puntos_esteticos" name="puntos_esteticos" min="0" max="50" step="0.5" value="0" class="form-control">
                            <div class="form-text">1.320 €/punto</div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Gastos</legend>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="gastos_medicos">Gastos médicos (€)</label>
                            <input type="number" id="gastos_medicos" name="gastos_medicos" min="0" step="0.01" value="0" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="gastos_otros">Otros gastos (€)</label>
                            <input type="number" id="gastos_otros" name="gastos_otros" min="0" step="0.01" value="0" class="form-control">
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-primary btn-lg w-100">Calcular indemnización</button>
            </form>
        </div>

        <div id="baremo-result" class="card p-4 p-md-5 mt-4 d-none" role="region" aria-live="polite" aria-label="Resultado del cálculo">
            <h2 class="h5 fw-bold mb-3">Resultado</h2>
            <div class="row g-3" id="result-breakdown"></div>
            <div class="alert alert-info mt-4 mb-0">
                <strong>Total estimado:</strong>
                <span id="result-total" class="fs-4 fw-bold ms-2"></span>
            </div>
            <p class="form-text mt-3 mb-0">
                Resultado orientativo basado en la Resolución DGS 2024 (RD 1148/2015).
                Para casos formales, contraste con la resolución vigente publicada en el BOE.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('baremo-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = Object.fromEntries(new FormData(form).entries());

    const res = await fetch('{{ route('tools.baremo.calculate') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify(data),
    });
    const r = await res.json();

    const breakdown = document.getElementById('result-breakdown');
    const fmt = (v) => new Intl.NumberFormat('es-ES', {style:'currency', currency:'EUR'}).format(v);

    breakdown.innerHTML = `
        <div class="col-sm-6"><div class="card p-3 h-100"><div class="text-muted small">Días de perjuicio</div><div class="fw-bold">${fmt(r.dias_perjuicio.total)}</div></div></div>
        <div class="col-sm-6"><div class="card p-3 h-100"><div class="text-muted small">Secuelas (${r.secuelas.puntos} pts × ${fmt(r.secuelas.tarifa_punto)})</div><div class="fw-bold">${fmt(r.secuelas.total)}</div></div></div>
        <div class="col-sm-6"><div class="card p-3 h-100"><div class="text-muted small">Perjuicio estético</div><div class="fw-bold">${fmt(r.perjuicio_estetico.total)}</div></div></div>
        <div class="col-sm-6"><div class="card p-3 h-100"><div class="text-muted small">Lucro cesante + gastos</div><div class="fw-bold">${fmt(r.perjuicio_patrimonial.total)}</div></div></div>
    `;
    document.getElementById('result-total').textContent = fmt(r.total_indemnizacion);
    document.getElementById('baremo-result').classList.remove('d-none');
    document.getElementById('baremo-result').scrollIntoView({behavior:'smooth', block:'start'});
});
</script>
@endpush
