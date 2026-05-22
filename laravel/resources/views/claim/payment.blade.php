@extends('layouts.app')

@section('title', 'Pago seguro')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card p-4 mb-3">
            <h5 class="mb-3">Resumen de tu reclamación</h5>
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted">Aseguradora</td><td class="fw-semibold">{{ $claim->insurer_name }}</td></tr>
                <tr><td class="text-muted">Tu nombre</td><td>{{ $claim->claimant_name }}</td></tr>
                <tr><td class="text-muted">Email</td><td>{{ $claim->claimant_email }}</td></tr>
                <tr><td class="text-muted">Descripción</td><td>{{ Str::limit($claim->description, 120) }}</td></tr>
            </table>
        </div>

        {{-- Policy PDF upload — Pro subscribers only --}}
        @auth
            @if(auth()->user()->hasActiveSubscription())
            <div class="card p-3 mb-3 border-info">
                <h6 class="mb-1">📎 Sube tu póliza para una carta más sólida <span class="badge bg-primary">Pro</span></h6>
                <p class="text-muted small mb-2">La IA extraerá las cláusulas relevantes y las citará en tu carta con número de artículo exacto.</p>
                <div id="policy-upload-area">
                    <input type="file" id="policy-pdf" accept=".pdf" class="form-control form-control-sm mb-2">
                    <button type="button" id="upload-policy-btn" class="btn btn-sm btn-outline-info">Analizar póliza</button>
                    <div id="policy-status" class="mt-2 small"></div>
                </div>
            </div>
            @else
            <div class="card p-3 mb-3 bg-light">
                <p class="small text-muted mb-0">
                    💡 Con el <a href="{{ route('subscription.plans') }}">Plan Pro</a> puedes subir tu póliza PDF
                    para que la carta cite las cláusulas exactas de tu contrato.
                </p>
            </div>
            @endif
        @endauth

        <div class="card p-4">
            <h5 class="mb-1">Pago seguro</h5>
            <p class="text-muted small mb-3">El documento se genera inmediatamente tras confirmar el pago.</p>

            <div id="payment-error" class="alert alert-danger d-none"></div>

            <form id="payment-form">
                @csrf
                <div id="card-element" class="form-control py-3 mb-3"></div>
                <button id="submit-btn" class="btn btn-primary btn-lg w-100">
                    Pagar 9,99 € y generar reclamación
                </button>
                <p class="text-center text-muted small mt-2">
                    🔒 Pago procesado por Stripe. Tus datos de tarjeta no se almacenan en nuestros servidores.
                </p>
            </form>

            <div id="payment-processing" class="text-center d-none py-3">
                <div class="spinner-border text-primary mb-2"></div>
                <p class="text-muted">Procesando pago y generando tu documento...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ $stripeKey }}');
const elements = stripe.elements();
const card = elements.create('card', {
    style: { base: { fontSize: '16px', color: '#1a1a2e' } },
    hidePostalCode: true,
});
card.mount('#card-element');

// Policy PDF upload
document.getElementById('upload-policy-btn')?.addEventListener('click', async () => {
    const fileInput = document.getElementById('policy-pdf');
    const status = document.getElementById('policy-status');
    const btn = document.getElementById('upload-policy-btn');

    if (!fileInput.files.length) {
        status.innerHTML = '<span class="text-warning">Selecciona un PDF primero.</span>';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Analizando póliza...';
    status.innerHTML = '<span class="text-muted">Extrayendo cláusulas relevantes...</span>';

    const formData = new FormData();
    formData.append('policy_pdf', fileInput.files[0]);
    formData.append('_token', '{{ csrf_token() }}');

    const res = await fetch('{{ route('policy.upload', $claim) }}', {
        method: 'POST',
        body: formData,
    });
    const data = await res.json();

    if (data.status === 'success' && data.clauses?.clausulas_clave?.length) {
        const count = data.clauses.clausulas_clave.length;
        status.innerHTML = `<span class="text-success">✅ ${count} cláusula(s) extraída(s). Se incluirán en tu carta.</span>`;
    } else {
        status.innerHTML = `<span class="text-info">ℹ️ ${data.message || 'Póliza guardada.'}</span>`;
    }
    btn.disabled = false;
    btn.textContent = 'Analizar otra póliza';
});

document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submit-btn');
    const errorDiv = document.getElementById('payment-error');
    btn.disabled = true;
    btn.textContent = 'Procesando...';
    errorDiv.classList.add('d-none');

    // Create payment intent
    const res = await fetch('{{ route('payment.intent', $claim) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    });
    const { client_secret, error: serverError } = await res.json();

    if (serverError) {
        errorDiv.textContent = serverError;
        errorDiv.classList.remove('d-none');
        btn.disabled = false;
        btn.textContent = 'Pagar 9,99 € y generar reclamación';
        return;
    }

    const { error, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
        payment_method: { card }
    });

    if (error) {
        errorDiv.textContent = error.message;
        errorDiv.classList.remove('d-none');
        btn.disabled = false;
        btn.textContent = 'Pagar 9,99 € y generar reclamación';
    } else {
        document.getElementById('payment-form').classList.add('d-none');
        document.getElementById('payment-processing').classList.remove('d-none');
        // Webhook processes payment — redirect after a moment
        setTimeout(() => {
            window.location.href = '{{ route('payment.success', $claim) }}';
        }, 3000);
    }
});
</script>
@endpush
