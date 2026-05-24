@extends('layouts.app')

@section('title', 'Confirmar y pagar — Reclama')
@section('meta-description', 'Confirma tu reclamación y paga de forma segura con Stripe.')

@push('styles')
<style>
/* ── Payment page — /pago/{claim} ───────────────────────────── */
.payment-wrap {
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
.form-progress__track { display: flex; gap: var(--sp-1); }
.form-progress__pip {
    width: 20px; height: 2px;
    border-radius: var(--r-full);
    background: var(--border-strong);
    transition: background var(--t-base-d);
}
.form-progress__pip--on { background: var(--lime); }

/* Summary block */
.summary-block {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: var(--sp-5) var(--sp-6);
    margin-bottom: var(--sp-4);
}
.summary-block__label {
    display: block;
    font-size: var(--t-xs);
    font-weight: var(--fw-semibold);
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-4);
    margin-bottom: var(--sp-4);
}
.summary-rows {
    display: flex;
    flex-direction: column;
    gap: var(--sp-3);
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: var(--sp-4);
}
.summary-row__key {
    font-size: var(--t-sm);
    color: var(--text-4);
    flex-shrink: 0;
    min-width: 90px;
}
.summary-row__val {
    font-size: var(--t-sm);
    color: var(--text-2);
    font-weight: var(--fw-medium);
    text-align: right;
    word-break: break-word;
}
.summary-row__val--mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: var(--t-xs);
    font-weight: 400;
    color: var(--text-3);
}
.summary-desc {
    font-size: var(--t-xs);
    color: var(--text-4);
    line-height: 1.55;
    padding-top: var(--sp-3);
    border-top: 1px solid var(--border);
    margin-top: var(--sp-3);
}

/* Policy upload — Pro feature callout (lime accent, allowed exception) */
.policy-card {
    background: rgba(200,240,49,.04);
    border: 1px solid var(--lime-border);
    border-radius: var(--r-lg);
    padding: var(--sp-5) var(--sp-6);
    margin-bottom: var(--sp-4);
}
.policy-card__header {
    display: flex;
    align-items: center;
    gap: var(--sp-2);
    margin-bottom: var(--sp-1);
}
.policy-card__title {
    font-size: var(--t-sm);
    font-weight: var(--fw-semibold);
    color: var(--text-2);
}
.pro-badge {
    font-size: .65rem;
    font-weight: var(--fw-bold);
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--lime);
    background: var(--lime-dim);
    border: 1px solid var(--lime-border);
    padding: .15rem .45rem;
    border-radius: var(--r-full);
    line-height: 1.4;
}
.policy-card__desc {
    font-size: var(--t-xs);
    color: var(--text-4);
    line-height: 1.55;
    margin-bottom: var(--sp-4);
}

/* Drop zone */
.drop-zone {
    position: relative;
    border: 1px dashed var(--border-strong);
    border-radius: var(--r-md);
    padding: var(--sp-8) var(--sp-4);
    text-align: center;
    cursor: pointer;
    transition: border-color var(--t-base-d), background var(--t-base-d);
}
.drop-zone:hover, .drop-zone.drag-over {
    border-color: var(--lime-border);
    background: rgba(200,240,49,.04);
}
.drop-zone input[type="file"] {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    opacity: 0;
    cursor: pointer;
}
.drop-zone__icon { color: var(--text-4); margin-bottom: var(--sp-2); }
.drop-zone__label {
    font-size: var(--t-sm);
    font-weight: var(--fw-medium);
    color: var(--text-3);
    display: block;
}
.drop-zone__sub {
    font-size: var(--t-xs);
    color: var(--text-4);
    margin-top: 3px;
    display: block;
}
.policy-status {
    min-height: 18px;
    margin-top: var(--sp-2);
    font-size: var(--t-xs);
    color: var(--text-3);
    font-family: 'DM Sans', system-ui, sans-serif;
}
.policy-status.ps-success { color: var(--success); }
.policy-status.ps-warning { color: var(--warning); }
.policy-status.ps-error   { color: var(--danger);  }

/* Policy upsell (non-Pro) */
.policy-upsell {
    background: var(--bg-elevated);
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    padding: var(--sp-3) var(--sp-4);
    font-size: var(--t-xs);
    color: var(--text-4);
    line-height: 1.55;
    margin-bottom: var(--sp-4);
}
.policy-upsell a { color: var(--lime); }

/* Payment card */
.payment-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: var(--sp-6);
}
.payment-card__title {
    font-size: var(--t-2xl);
    margin-bottom: var(--sp-1);
}
.payment-card__sub {
    font-size: var(--t-xs);
    color: var(--text-4);
    margin-bottom: var(--sp-6);
}

/* Stripe card element */
#card-element {
    background: var(--bg-elevated);
    border: 1px solid var(--border-md);
    border-radius: var(--r-md);
    padding: .75rem .875rem;
    transition: border-color var(--t-base-d), box-shadow var(--t-base-d);
    margin-bottom: var(--sp-5);
}
#card-element.StripeElement--focus {
    border-color: var(--lime);
    box-shadow: var(--sh-ring);
}
#card-element.StripeElement--invalid { border-color: var(--danger); }
#card-errors {
    font-size: var(--t-xs);
    color: var(--danger);
    min-height: 16px;
    margin-top: calc(-1 * var(--sp-4));
    margin-bottom: var(--sp-4);
}

/* Desistimiento waiver */
.waiver-card {
    background: rgba(251,191,36,.06);
    border: 1px solid rgba(251,191,36,.28);
    border-radius: var(--r-md);
    padding: var(--sp-4) var(--sp-5);
    margin-bottom: var(--sp-5);
}
.waiver-card__title {
    display: flex;
    align-items: center;
    gap: var(--sp-2);
    font-size: var(--t-xs);
    font-weight: var(--fw-semibold);
    color: var(--warning);
    letter-spacing: .01em;
    margin-bottom: var(--sp-2);
}
.waiver-card__body {
    font-size: var(--t-xs);
    color: var(--text-3);
    line-height: 1.65;
    margin-bottom: var(--sp-3);
}
.waiver-checkbox {
    display: flex;
    gap: var(--sp-3);
    align-items: flex-start;
}
.waiver-checkbox__input {
    flex-shrink: 0;
    width: 15px; height: 15px;
    margin-top: 2px;
    appearance: none;
    -webkit-appearance: none;
    background: var(--bg-elevated);
    border: 1px solid var(--border-strong);
    border-radius: var(--r-xs);
    cursor: pointer;
    position: relative;
    transition: background var(--t-base-d), border-color var(--t-base-d);
}
.waiver-checkbox__input:checked {
    background: var(--lime);
    border-color: var(--lime);
}
.waiver-checkbox__input:checked::after {
    content: '';
    position: absolute;
    top: 2px; left: 4px;
    width: 5px; height: 8px;
    border: 1.5px solid #09090B;
    border-top: none;
    border-left: none;
    transform: rotate(45deg);
}
.waiver-checkbox__input:focus-visible {
    outline: none;
    box-shadow: var(--sh-ring);
}
.waiver-checkbox__label {
    font-size: var(--t-xs);
    color: var(--text-3);
    line-height: 1.6;
    cursor: pointer;
}
.waiver-checkbox__label strong {
    color: var(--text-2);
    font-weight: var(--fw-semibold);
}
.waiver-error {
    display: none;
    align-items: center;
    gap: var(--sp-1);
    font-size: var(--t-xs);
    color: var(--danger);
    margin-top: var(--sp-2);
}
.waiver-error.visible { display: flex; }

/* Payment error */
.payment-error-alert {
    display: none;
    background: var(--danger-bg);
    border: 1px solid rgba(248,113,113,.28);
    border-radius: var(--r-md);
    padding: var(--sp-3) var(--sp-4);
    font-size: var(--t-sm);
    color: var(--danger);
    margin-bottom: var(--sp-4);
}
.payment-error-alert.visible { display: block; }

/* Processing state */
.payment-processing {
    display: none;
    text-align: center;
    padding: var(--sp-16) var(--sp-4);
}
.payment-processing.visible { display: block; }
.payment-processing__spinner {
    width: 28px; height: 28px;
    border: 2px solid var(--border-strong);
    border-top-color: var(--lime);
    border-radius: 50%;
    animation: pay-spin 0.7s linear infinite;
    margin: 0 auto var(--sp-4);
}
@keyframes pay-spin { to { transform: rotate(360deg); } }
.payment-processing__text {
    font-size: var(--t-sm);
    color: var(--text-3);
}

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
<div class="payment-wrap">

    {{-- Progress --}}
    <div class="form-progress" aria-label="Progreso: paso 2 de 2">
        <span class="form-progress__step">Paso 2 de 2</span>
        <div class="form-progress__track" aria-hidden="true">
            <span class="form-progress__pip"></span>
            <span class="form-progress__pip form-progress__pip--on"></span>
        </div>
    </div>

    {{-- Summary block --}}
    <div class="summary-block" aria-label="Resumen de tu reclamación">
        <span class="summary-block__label">Tu reclamación</span>
        <div class="summary-rows">
            <div class="summary-row">
                <span class="summary-row__key">Aseguradora</span>
                <span class="summary-row__val">{{ $claim->insurer_name }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-row__key">Reclamante</span>
                <span class="summary-row__val">{{ $claim->claimant_name }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-row__key">Email</span>
                <span class="summary-row__val summary-row__val--mono">{{ $claim->claimant_email }}</span>
            </div>
            @if($claim->policy_number)
            <div class="summary-row">
                <span class="summary-row__key">Póliza</span>
                <span class="summary-row__val summary-row__val--mono">{{ $claim->policy_number }}</span>
            </div>
            @endif
        </div>
        @if($claim->description)
        <p class="summary-desc">{{ Str::limit($claim->description, 160) }}</p>
        @endif
    </div>

    {{-- Policy upload — Pro subscribers only --}}
    @auth
        @if(auth()->user()->hasActiveSubscription())
        <div class="policy-card" aria-label="Adjuntar póliza PDF (función Pro)">
            <div class="policy-card__header">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                    <path d="M4 2h5.5L12 4.5V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z"
                          stroke="#C8F031" stroke-width="1.1"/>
                    <path d="M9.5 2v2.5H12" stroke="#C8F031" stroke-width="1.1" stroke-linejoin="round"/>
                    <path d="M5.5 8.5h4M7.5 6.5v4" stroke="#C8F031" stroke-width="1.1" stroke-linecap="round"/>
                </svg>
                <span class="policy-card__title">Adjunta tu póliza</span>
                <span class="pro-badge">Pro</span>
            </div>
            <p class="policy-card__desc">
                El sistema extrae las cláusulas relevantes y las cita con número de artículo exacto en tu carta.
            </p>
            <div class="drop-zone" id="drop-zone" role="button" tabindex="0"
                 aria-label="Zona de carga de póliza PDF. Pulsa Enter o Espacio para seleccionar un archivo.">
                <input type="file" id="policy-pdf" accept=".pdf" aria-label="Seleccionar PDF de póliza">
                <div class="drop-zone__icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <path d="M11 14V6M11 6l-3 3M11 6l3 3"
                              stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 15v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2"
                              stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="drop-zone__label">Arrastra tu póliza aquí</span>
                <span class="drop-zone__sub">o haz clic para seleccionar · PDF · máx. 10 MB</span>
            </div>
            <div id="policy-status" class="policy-status" aria-live="polite" aria-atomic="true"></div>
        </div>
        @else
        <div class="policy-upsell" role="note">
            Con el <a href="{{ route('subscription.plans') }}">Plan Pro</a> puedes adjuntar tu póliza en PDF
            para que la carta cite las cláusulas exactas de tu contrato.
        </div>
        @endif
    @endauth

    {{-- Payment card --}}
    <div class="payment-card">
        <h1 class="payment-card__title">Pago seguro</h1>
        <p class="payment-card__sub">El documento se genera al instante tras confirmar el pago.</p>

        <div id="payment-error" class="payment-error-alert" role="alert" aria-live="assertive"></div>

        <form id="payment-form" novalidate>
            @csrf

            <label class="form-label" for="card-element">Datos de tarjeta</label>
            <div id="card-element" aria-label="Campo de tarjeta de crédito Stripe"></div>
            <div id="card-errors" role="alert" aria-live="polite"></div>

            {{-- Desistimiento waiver (LGDCU art. 103.a — legally required before payment) --}}
            <div class="waiver-card" role="group" aria-labelledby="waiver-title">
                <p class="waiver-card__title" id="waiver-title">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                        <path d="M6.5 1.5L1 4v3.5c0 2.76 2.35 5.34 5.5 5.97C9.65 12.84 12 10.26 12 7.5V4L6.5 1.5z"
                              stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                        <path d="M6.5 4.5v3M6.5 9h.01"
                              stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    Renuncia al derecho de desistimiento — art. 103.a LGDCU
                </p>
                <p class="waiver-card__body">
                    Este servicio consiste en la generación inmediata de contenido digital no suministrado en soporte material.
                    Al confirmar el pago solicitas expresamente el inicio inmediato del servicio y renuncias al derecho de
                    desistimiento de 14 días naturales, conforme al art. 103.a del RD Legislativo 1/2007 (LGDCU).
                </p>
                <div class="waiver-checkbox">
                    <input type="checkbox" id="desistimiento_waiver" name="desistimiento_waiver"
                           class="waiver-checkbox__input"
                           aria-required="true"
                           aria-describedby="desistimiento-err">
                    <label class="waiver-checkbox__label" for="desistimiento_waiver">
                        <strong>He leído y acepto la renuncia al derecho de desistimiento.</strong>
                        Entiendo que el documento se generará de forma inmediata tras el pago.
                    </label>
                </div>
                <div id="desistimiento-err" class="waiver-error" role="alert">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                        <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M6 4v3M6 8.5h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    Debes aceptar la renuncia al desistimiento para continuar.
                </div>
            </div>

            <button id="submit-btn" type="submit" class="btn btn-primary btn-lg w-100">
                Confirmar pago — 9,99 €
            </button>
            <p class="cta-note">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <rect x="1.5" y="5" width="9" height="6.5" rx="1" stroke="currentColor" stroke-width="1.1"/>
                    <path d="M4 5V3.5a2 2 0 0 1 4 0V5" stroke="currentColor" stroke-width="1.1"/>
                </svg>
                Procesado por Stripe · Datos de tarjeta no almacenados en nuestros servidores
            </p>
        </form>

        <div id="payment-processing" class="payment-processing" aria-live="polite" aria-label="Procesando pago">
            <div class="payment-processing__spinner" aria-hidden="true"></div>
            <p class="payment-processing__text">Procesando pago y generando tu documento...</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
// ── Stripe Elements with dark theme ──────────────────────────
const stripe = Stripe('{{ $stripeKey }}');
const elements = stripe.elements({
    appearance: {
        theme: 'night',
        variables: {
            colorPrimary:     '#C8F031',
            colorBackground:  '#18181B',
            colorText:        '#FAFAFA',
            colorTextPlaceholder: '#71717A',
            colorDanger:      '#F87171',
            fontFamily:       'DM Sans, system-ui, sans-serif',
            borderRadius:     '10px',
            spacingUnit:      '4px',
            focusBoxShadow:   '0 0 0 2px #09090B, 0 0 0 4px #C8F031',
        },
        rules: {
            '.Input': {
                border:     '1px solid rgba(255,255,255,0.12)',
                boxShadow:  'none',
                padding:    '10px 14px',
            },
            '.Input:focus': {
                border:     '1px solid rgba(255,255,255,0.22)',
                boxShadow:  '0 0 0 2px #09090B, 0 0 0 4px #C8F031',
            },
            '.Label': {
                fontWeight:    '500',
                fontSize:      '13px',
                letterSpacing: '0.01em',
                color:         '#E4E4E7',
            },
        }
    }
});
const card = elements.create('card', { hidePostalCode: true });
card.mount('#card-element');
card.on('change', ({ error }) => {
    const el = document.getElementById('card-errors');
    el.textContent = error ? error.message : '';
});

// ── Policy upload ─────────────────────────────────────────────
const dropZone    = document.getElementById('drop-zone');
const policyInput = document.getElementById('policy-pdf');
const policyStatus = document.getElementById('policy-status');

if (dropZone && policyInput) {
    ['dragenter', 'dragover'].forEach(ev =>
        dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('drag-over'); }));
    ['dragleave', 'drop'].forEach(ev =>
        dropZone.addEventListener(ev, () => dropZone.classList.remove('drag-over')));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        if (e.dataTransfer.files[0]) { policyInput.files = e.dataTransfer.files; uploadPolicy(); }
    });
    policyInput.addEventListener('change', () => { if (policyInput.files[0]) uploadPolicy(); });
    dropZone.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); policyInput.click(); }
    });
}

async function uploadPolicy() {
    if (!policyInput?.files?.length) return;
    policyStatus.textContent = 'Extrayendo cláusulas relevantes...';
    policyStatus.className = 'policy-status';

    const formData = new FormData();
    formData.append('policy_pdf', policyInput.files[0]);
    formData.append('_token', '{{ csrf_token() }}');

    try {
        const res  = await fetch('{{ route('policy.upload', $claim) }}', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.status === 'success' && data.clauses?.clausulas_clave?.length) {
            policyStatus.textContent = `${data.clauses.clausulas_clave.length} cláusula(s) extraída(s). Se incluirán en tu carta.`;
            policyStatus.className = 'policy-status ps-success';
        } else {
            policyStatus.textContent = data.message || 'Póliza guardada.';
            policyStatus.className = 'policy-status ps-warning';
        }
    } catch {
        policyStatus.textContent = 'Error al procesar la póliza. Puedes continuar sin ella.';
        policyStatus.className = 'policy-status ps-error';
    }
}

// ── Payment form ──────────────────────────────────────────────
document.getElementById('payment-form').addEventListener('submit', async e => {
    e.preventDefault();

    const btn       = document.getElementById('submit-btn');
    const errorDiv  = document.getElementById('payment-error');
    const waiver    = document.getElementById('desistimiento_waiver');
    const waiverErr = document.getElementById('desistimiento-err');

    if (!waiver.checked) {
        waiverErr.classList.add('visible');
        waiver.focus();
        return;
    }
    waiverErr.classList.remove('visible');

    btn.disabled    = true;
    btn.textContent = 'Procesando...';
    errorDiv.classList.remove('visible');

    try {
        const res = await fetch('{{ route('payment.intent', $claim) }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        });
        const { client_secret, error: serverError } = await res.json();

        if (serverError) {
            errorDiv.textContent = serverError;
            errorDiv.classList.add('visible');
            btn.disabled    = false;
            btn.textContent = 'Confirmar pago — 9,99 €';
            return;
        }

        const { error } = await stripe.confirmCardPayment(client_secret, {
            payment_method: { card }
        });

        if (error) {
            errorDiv.textContent = error.message;
            errorDiv.classList.add('visible');
            btn.disabled    = false;
            btn.textContent = 'Confirmar pago — 9,99 €';
        } else {
            document.getElementById('payment-form').style.display = 'none';
            document.getElementById('payment-processing').classList.add('visible');
            setTimeout(() => { window.location.href = '{{ route('payment.success', $claim) }}'; }, 2500);
        }
    } catch {
        errorDiv.textContent = 'Error de conexión. Comprueba tu red e inténtalo de nuevo.';
        errorDiv.classList.add('visible');
        btn.disabled    = false;
        btn.textContent = 'Confirmar pago — 9,99 €';
    }
});
</script>
@endpush
