@extends('layouts.app')

@section('title', 'Analiza tu caso gratis — ReclamaIA')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="text-center mb-4">
            <span class="badge bg-primary fs-6 px-3 py-2 mb-2">Plan Pro — Análisis IA</span>
            <h2 class="fw-bold">¿Tiene viabilidad tu reclamación?</h2>
            <p class="text-muted">Describe tu caso y nuestra IA te dirá si tienes posibilidades de éxito, qué base legal aplica y cómo redactarlo para maximizar el resultado.</p>
        </div>

        <div class="card p-4 mb-4">
            <form id="viability-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre de la aseguradora</label>
                    <input type="text" id="insurer_name" name="insurer_name"
                           class="form-control" placeholder="Ej. Mapfre, AXA, Allianz..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Describe tu problema</label>
                    <textarea id="description" name="description" rows="5" class="form-control"
                              placeholder="Cuéntanos qué pasó. Cuantos más detalles, más preciso será el análisis..." required minlength="30"></textarea>
                    <div class="form-text" id="char-count">Mínimo 30 caracteres</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Número de póliza <span class="text-muted">(opcional pero mejora el análisis)</span></label>
                    <input type="text" id="policy_number" name="policy_number" class="form-control" placeholder="POL-2024-001234">
                </div>
                <button type="submit" id="analyze-btn" class="btn btn-success btn-lg w-100">
                    Analizar mi caso gratis →
                </button>
            </form>
        </div>

        <!-- Results panel (hidden until analysis) -->
        <div id="results-panel" class="d-none">
            <div id="loading-state" class="card p-4 text-center d-none">
                <div class="spinner-border text-success mb-3 mx-auto"></div>
                <p class="text-muted">Analizando tu caso con IA legal española...</p>
            </div>

            <div id="analysis-result" class="d-none">
                <div class="card p-4 mb-3" id="score-card">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div id="score-badge" class="display-4 fw-bold text-center" style="min-width:80px"></div>
                        </div>
                        <div class="col">
                            <h4 id="score-label" class="mb-1"></h4>
                            <p id="analysis-summary" class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto text-end">
                            <div class="text-muted small">Probabilidad estimada</div>
                            <div id="probability" class="display-6 fw-bold"></div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="card p-3 h-100 border-success">
                            <h6 class="text-success mb-2">✅ Puntos a tu favor</h6>
                            <ul id="puntos-fuertes" class="mb-0 ps-3 small"></ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-3 h-100 border-warning">
                            <h6 class="text-warning mb-2">⚠️ Puntos a tener en cuenta</h6>
                            <ul id="puntos-debiles" class="mb-0 ps-3 small"></ul>
                        </div>
                    </div>
                </div>

                <div class="card p-3 mb-3 bg-light">
                    <h6 class="mb-2">⚖️ Base legal aplicable</h6>
                    <p id="base-legal" class="mb-0 small"></p>
                </div>

                <div class="card p-3 mb-4 border-primary">
                    <h6 class="text-primary mb-2">💡 Recomendación para tu carta</h6>
                    <p id="recomendacion" class="mb-0 small"></p>
                </div>

                <div id="cta-section" class="text-center">
                    <a id="cta-btn" href="{{ route('claim.create') }}" class="btn btn-primary btn-lg px-5">
                        Generar mi reclamación — 9,99 €
                    </a>
                    <p class="text-muted small mt-2">El análisis se incluirá en tu carta generada.</p>
                </div>
            </div>

            <div id="error-state" class="alert alert-warning d-none">
                No hemos podido analizar tu caso en este momento. Puedes proceder directamente a generar la reclamación.
                <a href="{{ route('claim.create') }}" class="btn btn-primary btn-sm ms-2">Generar reclamación →</a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const descriptionEl = document.getElementById('description');
const charCount = document.getElementById('char-count');

descriptionEl.addEventListener('input', () => {
    const len = descriptionEl.value.length;
    charCount.textContent = len < 30
        ? `Mínimo 30 caracteres (${30 - len} restantes)`
        : `${len} caracteres ✓`;
    charCount.className = len < 30 ? 'form-text text-danger' : 'form-text text-success';
});

document.getElementById('viability-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const panel = document.getElementById('results-panel');
    const loading = document.getElementById('loading-state');
    const result = document.getElementById('analysis-result');
    const error = document.getElementById('error-state');
    const btn = document.getElementById('analyze-btn');

    panel.classList.remove('d-none');
    loading.classList.remove('d-none');
    result.classList.add('d-none');
    error.classList.add('d-none');
    btn.disabled = true;
    btn.textContent = 'Analizando...';

    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });

    try {
        const res = await fetch('{{ route('viability.analyze') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                insurer_name: document.getElementById('insurer_name').value,
                description: descriptionEl.value,
                policy_number: document.getElementById('policy_number').value,
                claim_type: 'insurance',
            }),
        });

        const data = await res.json();

        loading.classList.add('d-none');

        if (data.status !== 'success' || !data.analysis) {
            error.classList.remove('d-none');
        } else {
            renderAnalysis(data.analysis);
            result.classList.remove('d-none');
        }
    } catch (err) {
        loading.classList.add('d-none');
        error.classList.remove('d-none');
    }

    btn.disabled = false;
    btn.textContent = 'Analizar de nuevo →';
});

function renderAnalysis(a) {
    const scoreColors = { alta: '#198754', media: '#fd7e14', baja: '#dc3545' };
    const scoreLabels = { alta: 'Viabilidad ALTA', media: 'Viabilidad MEDIA', baja: 'Viabilidad BAJA' };
    const scoreEmojis = { alta: '🟢', media: '🟡', baja: '🔴' };

    const score = a.score || 'baja';
    const color = scoreColors[score];

    document.getElementById('score-card').style.borderLeft = `6px solid ${color}`;
    document.getElementById('score-badge').innerHTML = scoreEmojis[score];
    document.getElementById('score-label').textContent = scoreLabels[score];
    document.getElementById('score-label').style.color = color;
    document.getElementById('analysis-summary').textContent = a.resumen || '';
    document.getElementById('probability').textContent = (a.probabilidad_estimada || 0) + '%';
    document.getElementById('probability').style.color = color;

    const fuertes = document.getElementById('puntos-fuertes');
    fuertes.innerHTML = (a.puntos_fuertes || []).map(p => `<li>${p}</li>`).join('');

    const debiles = document.getElementById('puntos-debiles');
    debiles.innerHTML = (a.puntos_debiles || []).map(p => `<li>${p}</li>`).join('');

    document.getElementById('base-legal').textContent = a.base_legal || '';
    document.getElementById('recomendacion').textContent = a.recomendacion || '';

    // Pre-fill the claim form URL with the insurer name
    const params = new URLSearchParams({
        insurer_name: document.getElementById('insurer_name').value,
        description: descriptionEl.value,
    });
    document.getElementById('cta-btn').href = '{{ route('claim.create') }}?' + params.toString();

    if (a.fuera_de_ambito) {
        document.getElementById('cta-section').innerHTML = '<div class="alert alert-info">Este tipo de reclamación estará disponible próximamente. <a href="{{ route('claim.create') }}">Generar la reclamación de seguros más similar →</a></div>';
    }
}
</script>
@endpush
