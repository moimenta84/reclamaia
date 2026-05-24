@extends('layouts.app')

@section('title', 'Nueva reclamación — Reclama')
@section('meta-description', 'Genera tu carta formal de reclamación a la aseguradora con base legal española.')

@push('styles')
<style>
/* ── Claim Form — /reclamar ──────────────────────────────────── */
.claim-form-wrap {
    max-width: 640px;
    margin: 0 auto;
}

/* Progress */
.form-progress {
    display: flex;
    align-items: center;
    gap: var(--sp-3);
    margin-bottom: var(--sp-8);
}
.form-progress__step {
    font-size: var(--t-xs);
    font-weight: var(--fw-semibold);
    letter-spacing: .09em;
    text-transform: uppercase;
    color: var(--text-4);
}
.form-progress__track {
    display: flex;
    gap: var(--sp-1);
}
.form-progress__pip {
    width: 20px;
    height: 2px;
    border-radius: var(--r-full);
    background: var(--border-strong);
    transition: background var(--t-base-d);
}
.form-progress__pip--on { background: var(--lime); }

/* Page head */
.form-head {
    margin-bottom: var(--sp-8);
}
.form-head h1 {
    font-size: var(--t-3xl);
    margin-bottom: var(--sp-2);
}
.form-head p {
    font-size: var(--t-sm);
    color: var(--text-3);
}

/* Viability badge */
.viability-badge {
    display: flex;
    align-items: flex-start;
    gap: var(--sp-3);
    padding: var(--sp-3) var(--sp-4);
    border-radius: var(--r-md);
    margin-bottom: var(--sp-6);
    font-size: var(--t-sm);
}
.viability-badge.alta  { background: rgba(34,197,94,.07);   border: 1px solid rgba(34,197,94,.25);   }
.viability-badge.media { background: rgba(251,191,36,.07);  border: 1px solid rgba(251,191,36,.25);  }
.viability-badge.baja  { background: rgba(248,113,113,.07); border: 1px solid rgba(248,113,113,.25); }
.viability-badge__dot  {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
}
.alta  .viability-badge__dot { background: var(--success); }
.media .viability-badge__dot { background: var(--warning); }
.baja  .viability-badge__dot { background: var(--danger);  }
.viability-badge__label { font-weight: var(--fw-semibold); color: var(--text-2); }
.viability-badge__text  { color: var(--text-3); }

/* Error summary */
.error-summary {
    background: var(--danger-bg);
    border: 1px solid rgba(248,113,113,.28);
    border-radius: var(--r-md);
    padding: var(--sp-4);
    margin-bottom: var(--sp-6);
}
.error-summary__title {
    font-size: var(--t-sm);
    font-weight: var(--fw-semibold);
    color: var(--danger);
    margin-bottom: var(--sp-2);
}
.error-summary__list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: var(--sp-1);
}
.error-summary__list li {
    font-size: var(--t-xs);
    color: var(--text-3);
    padding-left: var(--sp-3);
    position: relative;
}
.error-summary__list li::before {
    content: '—';
    position: absolute;
    left: 0;
    color: var(--danger);
}

/* Section label */
.section-label {
    display: block;
    font-size: var(--t-xs);
    font-weight: var(--fw-semibold);
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-4);
    margin-bottom: var(--sp-4);
}

/* Section separator */
.section-sep {
    border: none;
    border-top: 1px solid var(--border);
    margin: var(--sp-8) 0 var(--sp-6);
}

/* Autocomplete hint */
.autofill-hint {
    display: flex;
    align-items: center;
    gap: var(--sp-1);
    font-size: var(--t-xs);
    color: var(--text-4);
    margin-bottom: var(--sp-4);
}

/* Two-column personal data grid */
.fields-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--sp-4);
}
.fields-grid .field-full { grid-column: 1 / -1; }
@media (max-width: 480px) {
    .fields-grid { grid-template-columns: 1fr; }
    .fields-grid .field-full { grid-column: 1; }
}

/* Mono input (póliza) */
.input-mono {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: var(--t-sm) !important;
    letter-spacing: .02em;
}

/* Char counter */
.char-counter {
    font-family: 'JetBrains Mono', monospace;
    font-size: var(--t-xs);
    font-variant-numeric: tabular-nums;
    transition: color var(--t-base-d);
}
.char-counter.cc-idle   { color: var(--text-4); }
.char-counter.cc-under  { color: var(--danger);  }
.char-counter.cc-met    { color: var(--success); }

/* DGSFP panel */
.dgsfp-panel { margin-top: var(--sp-3); }
.dgsfp-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    padding: var(--sp-3) var(--sp-4);
}
.dgsfp-card.ok   { border-color: rgba(34,197,94,.35);  background: rgba(34,197,94,.04);  }
.dgsfp-card.warn { border-color: rgba(251,191,36,.3);  background: rgba(251,191,36,.04); }
.dgsfp-header {
    display: flex;
    align-items: center;
    gap: var(--sp-2);
    margin-bottom: var(--sp-2);
}
.dgsfp-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dgsfp-dot.ok   { background: var(--success); }
.dgsfp-dot.warn { background: var(--warning); }
.dgsfp-title {
    font-size: var(--t-xs);
    font-weight: var(--fw-semibold);
    color: var(--text-2);
    letter-spacing: .01em;
}
.dgsfp-rows {
    display: flex;
    flex-direction: column;
    gap: 3px;
    margin-bottom: var(--sp-2);
}
.dgsfp-row { display: flex; gap: var(--sp-3); font-size: var(--t-xs); }
.dgsfp-key {
    font-family: 'DM Sans', system-ui, sans-serif;
    color: var(--text-4);
    min-width: 80px;
    flex-shrink: 0;
}
.dgsfp-val {
    font-family: 'JetBrains Mono', monospace;
    font-size: .7rem;
    color: var(--text-3);
    word-break: break-all;
}
.dgsfp-links {
    display: flex;
    gap: var(--sp-3);
    flex-wrap: wrap;
    border-top: 1px solid var(--border);
    padding-top: var(--sp-2);
}
.dgsfp-link {
    font-size: .7rem;
    color: var(--lime);
    text-decoration: none;
    font-weight: var(--fw-medium);
}
.dgsfp-link:hover { color: var(--lime-dark); text-decoration: underline; }

/* Trust note */
.trust-note {
    display: flex;
    gap: var(--sp-3);
    align-items: flex-start;
    background: var(--bg-elevated);
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    padding: var(--sp-3) var(--sp-4);
    margin-bottom: var(--sp-6);
}
.trust-note__icon { color: var(--text-4); flex-shrink: 0; margin-top: 1px; }
.trust-note__text {
    font-size: var(--t-xs);
    color: var(--text-4);
    line-height: 1.55;
}
.trust-note__text strong { color: var(--text-3); font-weight: var(--fw-medium); }

/* CTA footnote */
.cta-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--sp-2);
    font-size: var(--t-xs);
    color: var(--text-4);
    margin-top: var(--sp-3);
}
</style>
@endpush

@section('content')
<div class="claim-form-wrap">

    {{-- Progress --}}
    <div class="form-progress" aria-label="Progreso: paso 1 de 2">
        <span class="form-progress__step">Paso 1 de 2</span>
        <div class="form-progress__track" aria-hidden="true">
            <span class="form-progress__pip form-progress__pip--on"></span>
            <span class="form-progress__pip"></span>
        </div>
    </div>

    {{-- Viability badge --}}
    @if(request('viability_score'))
    @php $vs = request('viability_score'); @endphp
    <div class="viability-badge {{ $vs }}" role="status" aria-label="Resultado del análisis de viabilidad">
        <span class="viability-badge__dot"></span>
        <span>
            <span class="viability-badge__label">Viabilidad {{ strtoupper($vs) }}</span>
            <span class="viability-badge__text"> — Tu caso ha sido analizado. Completa los datos para generar la carta.</span>
        </span>
    </div>
    @endif

    {{-- Page heading --}}
    <div class="form-head">
        <h1>Genera tu reclamación</h1>
        <p>Rellena el formulario en 2 minutos. El documento se genera al instante.</p>
    </div>

    {{-- Error summary --}}
    @if($errors->any())
    <div class="error-summary" role="alert" aria-live="assertive">
        <p class="error-summary__title">{{ $errors->count() }} campo{{ $errors->count() > 1 ? 's requieren' : ' requiere' }} corrección</p>
        <ul class="error-summary__list">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('claim.store') }}" novalidate aria-label="Formulario de reclamación">
        @csrf

        {{-- ── SECCIÓN 1: TIPO DE CASO ───────────────────────────── --}}
        <span class="section-label" id="section-tipo">Tipo de caso</span>

        <div class="mb-4" role="group" aria-labelledby="section-tipo">
            <label class="form-label" for="claim_type">
                Tipo de seguro <span class="required" aria-hidden="true">*</span>
            </label>
            <select id="claim_type" name="claim_type"
                    class="form-select @error('claim_type') is-invalid @enderror"
                    aria-required="true"
                    @error('claim_type') aria-invalid="true" aria-describedby="claim_type-error" @enderror
                    required>
                <option value="">— Selecciona el tipo —</option>
                <option value="hogar"     @selected(old('claim_type') === 'hogar')>Seguro de hogar</option>
                <option value="auto"      @selected(old('claim_type') === 'auto')>Seguro de coche / moto</option>
                <option value="salud"     @selected(old('claim_type') === 'salud')>Seguro de salud</option>
                <option value="vida"      @selected(old('claim_type') === 'vida')>Seguro de vida / invalidez</option>
                <option value="viaje"     @selected(old('claim_type') === 'viaje')>Seguro de viaje</option>
                <option value="insurance" @selected(old('claim_type') === 'insurance')>Otro seguro</option>
            </select>
            @error('claim_type')
            <div id="claim_type-error" class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label" for="insurer_name">
                Aseguradora <span class="required" aria-hidden="true">*</span>
            </label>
            <input type="text" id="insurer_name" name="insurer_name"
                   value="{{ old('insurer_name', request('insurer_name')) }}"
                   class="form-control @error('insurer_name') is-invalid @enderror"
                   autocomplete="organization"
                   placeholder="Mapfre, AXA, Allianz, Mutua Madrileña..."
                   aria-required="true"
                   @error('insurer_name') aria-invalid="true" aria-describedby="insurer-error" @enderror
                   required>
            @error('insurer_name')
            <div id="insurer-error" class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
            <div id="dgsfp-panel" class="dgsfp-panel" role="status" aria-live="polite" style="display:none;"></div>
        </div>

        <div class="mb-2">
            <label class="form-label" for="description">
                Describe el problema <span class="required" aria-hidden="true">*</span>
            </label>
            <textarea id="description" name="description" rows="5"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Qué pasó, cuándo ocurrió, qué respondió la aseguradora y qué quieres conseguir."
                      aria-required="true"
                      aria-describedby="description-hint description-counter @error('description') description-error @enderror"
                      @error('description') aria-invalid="true" @enderror
                      minlength="50"
                      required>{{ old('description', request('description')) }}</textarea>
            <div class="d-flex justify-content-between align-items-baseline mt-2">
                <span id="description-hint" class="form-text">Sin tecnicismos — como se lo contarías a un colega.</span>
                <span id="description-counter" class="char-counter cc-idle" aria-live="polite" aria-atomic="true"></span>
            </div>
            @error('description')
            <div id="description-error" class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="section-sep" role="separator" aria-hidden="true"></div>

        {{-- ── SECCIÓN 2: TUS DATOS ──────────────────────────────── --}}
        <span class="section-label" id="section-datos">Tus datos</span>

        @auth
        <div class="autofill-hint" aria-live="polite">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2"/>
                <path d="M6 5v3M6 3.5v.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Autocompletado desde tu cuenta
        </div>
        @endauth

        <div class="fields-grid" role="group" aria-labelledby="section-datos">
            <div>
                <label class="form-label" for="claimant_name">
                    Nombre completo <span class="required" aria-hidden="true">*</span>
                </label>
                <input type="text" id="claimant_name" name="claimant_name"
                       value="{{ old('claimant_name', auth()->user()?->name) }}"
                       class="form-control @error('claimant_name') is-invalid @enderror"
                       autocomplete="name"
                       aria-required="true"
                       @error('claimant_name') aria-invalid="true" aria-describedby="claimant-name-error" @enderror
                       required>
                @error('claimant_name')
                <div id="claimant-name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="claimant_dni">
                    DNI / NIE <span class="required" aria-hidden="true">*</span>
                </label>
                <input type="text" id="claimant_dni" name="claimant_dni"
                       value="{{ old('claimant_dni') }}"
                       class="form-control input-mono @error('claimant_dni') is-invalid @enderror"
                       autocomplete="off"
                       placeholder="12345678A"
                       inputmode="text"
                       aria-required="true"
                       pattern="[0-9]{8}[A-Za-z]|[XYZxyz][0-9]{7}[A-Za-z]"
                       @error('claimant_dni') aria-invalid="true" aria-describedby="claimant-dni-error" @enderror
                       required>
                @error('claimant_dni')
                <div id="claimant-dni-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="claimant_email">
                    Correo electrónico <span class="required" aria-hidden="true">*</span>
                </label>
                <input type="email" id="claimant_email" name="claimant_email"
                       value="{{ old('claimant_email', auth()->user()?->email) }}"
                       class="form-control @error('claimant_email') is-invalid @enderror"
                       autocomplete="email"
                       aria-required="true"
                       @error('claimant_email') aria-invalid="true" aria-describedby="claimant-email-error" @enderror
                       required>
                @error('claimant_email')
                <div id="claimant-email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="claimant_phone">
                    Teléfono
                    <span style="color:var(--text-4);font-weight:400;font-size:.75rem;"> (opcional)</span>
                </label>
                <input type="tel" id="claimant_phone" name="claimant_phone"
                       value="{{ old('claimant_phone') }}"
                       class="form-control"
                       autocomplete="tel"
                       placeholder="600 123 456"
                       inputmode="tel">
            </div>

            <div class="field-full">
                <label class="form-label" for="claimant_address">
                    Dirección postal <span class="required" aria-hidden="true">*</span>
                </label>
                <input type="text" id="claimant_address" name="claimant_address"
                       value="{{ old('claimant_address') }}"
                       class="form-control @error('claimant_address') is-invalid @enderror"
                       autocomplete="street-address"
                       placeholder="Calle Mayor 1, 28001 Madrid"
                       aria-required="true"
                       @error('claimant_address') aria-invalid="true" aria-describedby="claimant-address-error" @enderror
                       required>
                @error('claimant_address')
                <div id="claimant-address-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="field-full">
                <label class="form-label" for="policy_number">
                    Número de póliza
                    <span style="color:var(--text-4);font-weight:400;font-size:.75rem;"> (opcional — mejora la carta)</span>
                </label>
                <input type="text" id="policy_number" name="policy_number"
                       value="{{ old('policy_number') }}"
                       class="form-control input-mono"
                       placeholder="POL-2024-001234">
            </div>
        </div>

        <div class="section-sep" role="separator" aria-hidden="true"></div>

        {{-- Trust note --}}
        <div class="trust-note" role="note">
            <svg class="trust-note__icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M8 1.5L2 4v4c0 3.31 2.52 6.41 6 7.17C11.48 14.41 14 11.31 14 8V4L8 1.5z"
                      stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
                <path d="M5.5 8l1.5 1.5 3-3"
                      stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="trust-note__text">
                <strong>Tus datos están protegidos.</strong>
                Solo se utilizan para redactar el documento y no se comparten con terceros.
                Cumplimos la Ley Orgánica 3/2018 (LOPD-GDD).
            </p>
        </div>

        {{-- CTA --}}
        <button type="submit" class="btn btn-primary btn-lg w-100">
            Generar reclamación — 9,99 €
        </button>
        <p class="cta-note">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                <rect x="1.5" y="5" width="9" height="6.5" rx="1" stroke="currentColor" stroke-width="1.1"/>
                <path d="M4 5V3.5a2 2 0 0 1 4 0V5" stroke="currentColor" stroke-width="1.1"/>
            </svg>
            Pago único · Stripe cifrado · Reembolso automático si hay error
        </p>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ── Char counter ─────────────────────────────────────────────
    const textarea = document.getElementById('description');
    const counter  = document.getElementById('description-counter');
    if (textarea && counter) {
        function updateCounter() {
            const len = textarea.value.length;
            counter.className = 'char-counter';
            if (len === 0) {
                counter.textContent = '';
                counter.classList.add('cc-idle');
            } else if (len < 50) {
                counter.textContent = `Faltan ${50 - len}`;
                counter.classList.add('cc-under');
            } else {
                counter.textContent = `${len} ✓`;
                counter.classList.add('cc-met');
            }
        }
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }

    // ── DGSFP widget ──────────────────────────────────────────────
    const insurerInput = document.getElementById('insurer_name');
    const dgsfpPanel   = document.getElementById('dgsfp-panel');
    if (!insurerInput || !dgsfpPanel) return;

    let dgsfpTimer = null;
    insurerInput.addEventListener('input', function () {
        clearTimeout(dgsfpTimer);
        const nombre = this.value.trim();
        if (nombre.length < 3) { dgsfpPanel.style.display = 'none'; return; }
        dgsfpTimer = setTimeout(() => fetchDgsfp(nombre), 700);
    });
    if (insurerInput.value.trim().length >= 3) fetchDgsfp(insurerInput.value.trim());

    function fetchDgsfp(nombre) {
        const csrf    = document.querySelector('meta[name="csrf-token"]');
        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
        if (csrf) headers['X-CSRF-TOKEN'] = csrf.getAttribute('content');
        Promise.all([
            fetch('/api/dgsfp/aseguradora', { method:'POST', headers, body: JSON.stringify({ nombre }) }).then(r => r.json()),
            fetch('/api/dgsfp/sanciones',   { method:'POST', headers, body: JSON.stringify({ nombre }) }).then(r => r.json()),
            fetch('/api/dgsfp/defensor',    { method:'POST', headers, body: JSON.stringify({ nombre }) }).then(r => r.json()),
        ]).then(([aseg, sanc, def]) => renderDgsfp(nombre, aseg, sanc, def))
          .catch(() => { dgsfpPanel.style.display = 'none'; });
    }

    function renderDgsfp(nombre, aseg, sanc, def) {
        const ok  = aseg.registrada === true;
        const cls = ok ? 'ok' : 'warn';
        const statusText = ok ? 'Registrada en DGSFP' : 'No encontrada en registro DGSFP';
        const rows = [
            aseg.nif            ? `<div class="dgsfp-row"><span class="dgsfp-key">NIF</span><span class="dgsfp-val">${aseg.nif}</span></div>` : '',
            aseg.nota           ? `<div class="dgsfp-row"><span class="dgsfp-key">Estado</span><span class="dgsfp-val">${aseg.nota}</span></div>` : '',
            def.plazo_respuesta ? `<div class="dgsfp-row"><span class="dgsfp-key">Defensor</span><span class="dgsfp-val">${def.plazo_respuesta}</span></div>` : '',
            def.derecho         ? `<div class="dgsfp-row"><span class="dgsfp-key">Base legal</span><span class="dgsfp-val">${def.derecho}</span></div>` : '',
        ].filter(Boolean).join('');
        const links = [
            aseg.url_ficha              ? `<a href="${aseg.url_ficha}" target="_blank" rel="noopener" class="dgsfp-link">Ficha DGSFP →</a>` : '',
            sanc.url_sanciones_boe      ? `<a href="${sanc.url_sanciones_boe}" target="_blank" rel="noopener" class="dgsfp-link">Sanciones BOE →</a>` : '',
            def.url_dgsfp_reclamaciones ? `<a href="${def.url_dgsfp_reclamaciones}" target="_blank" rel="noopener" class="dgsfp-link">Reclamar a DGSFP →</a>` : '',
        ].filter(Boolean).join('');
        dgsfpPanel.style.display = 'block';
        dgsfpPanel.innerHTML = `
            <div class="dgsfp-card ${cls}">
                <div class="dgsfp-header">
                    <span class="dgsfp-dot ${cls}"></span>
                    <span class="dgsfp-title">${statusText}</span>
                </div>
                ${rows  ? `<div class="dgsfp-rows">${rows}</div>` : ''}
                ${links ? `<div class="dgsfp-links">${links}</div>` : ''}
            </div>`;
    }
})();
</script>
@endpush
