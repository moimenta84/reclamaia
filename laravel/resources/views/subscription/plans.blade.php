@extends('layouts.app')

@section('title', 'Plan Pro — Reclamaciones ilimitadas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h3 class="text-center mb-4">Elige tu plan</h3>

        @if($hasSubscription)
            <div class="alert alert-success text-center">
                Ya tienes el <strong>Plan Pro activo</strong>. Puedes generar reclamaciones ilimitadas.
                <form method="POST" action="{{ route('subscription.cancel') }}" class="d-inline ms-3">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres cancelar?')">Cancelar suscripción</button>
                </form>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 p-4">
                    <h4>Free</h4>
                    <p class="display-6 fw-bold">9,99 <small class="fs-5 text-muted">€/doc</small></p>
                    <ul class="list-unstyled">
                        <li>✅ Un documento por pago</li>
                        <li>✅ Descarga Word y PDF</li>
                        <li>✅ Base legal Ley 50/1980</li>
                        <li class="text-muted">❌ Sin análisis de viabilidad</li>
                        <li class="text-muted">❌ Sin extracción de póliza</li>
                        <li class="text-muted">❌ Sin escalada automática</li>
                    </ul>
                    <a href="{{ route('claim.create') }}" class="btn btn-outline-primary mt-auto">Generar ahora</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 p-4 border-primary">
                    <span class="badge bg-primary mb-2">Recomendado</span>
                    <h4>Pro</h4>
                    <p class="display-6 fw-bold">29,99 <small class="fs-5 text-muted">€/mes</small></p>
                    <ul class="list-unstyled">
                        <li>✅ Reclamaciones ilimitadas</li>
                        <li>✅ Historial completo y re-descarga</li>
                        <li>✅ <strong>Análisis de viabilidad</strong> (probabilidad de éxito)</li>
                        <li>✅ <strong>Extracción de cláusulas de póliza PDF</strong></li>
                        <li>✅ <strong>Escalada automática a DGSFP a 30 días</strong></li>
                        <li>✅ Cartas con referencias exactas a tu póliza</li>
                    </ul>
                    @if(!$hasSubscription)
                        <button id="subscribe-btn" class="btn btn-primary mt-auto">Suscribirme — 29,99 €/mes</button>
                        <p class="text-muted small text-center mt-2">Cancela cuando quieras</p>
                    @else
                        <span class="btn btn-success mt-auto disabled">Plan activo ✓</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!$hasSubscription)
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ $stripeKey }}');
document.getElementById('subscribe-btn')?.addEventListener('click', async () => {
    const btn = document.getElementById('subscribe-btn');
    btn.disabled = true;
    btn.textContent = 'Procesando...';

    const res = await fetch('{{ route('subscription.subscribe') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    });
    const data = await res.json();

    if (data.client_secret) {
        const { error } = await stripe.confirmCardPayment(data.client_secret);
        if (error) {
            alert('Error: ' + error.message);
            btn.disabled = false;
            btn.textContent = 'Suscribirme — 29,99 €/mes';
        } else {
            window.location.href = '{{ route('dashboard') }}';
        }
    }
});
</script>
@endif
@endpush
