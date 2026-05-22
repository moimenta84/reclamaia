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
