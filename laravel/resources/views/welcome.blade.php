@extends('layouts.app')

@section('title', 'ReclamaIA — Reclama a tu aseguradora en minutos')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-lg-8 text-center">
        <h1 class="display-5 fw-bold mb-3">Tu aseguradora no te cubre.<br>Reclama ahora.</h1>
        <p class="lead text-muted mb-4">
            Describe tu problema en lenguaje normal. ReclamaIA redacta la carta formal de reclamación
            con base legal, lista para enviar al Defensor del Asegurado o a la DGSFP.
        </p>
        <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg px-5 py-3">
            Generar mi reclamación — 9,99 €
        </a>
        <p class="text-muted small mt-2">Pago único por documento · Sin suscripción obligatoria</p>
    </div>
</div>

<div class="row g-4 py-4">
    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <div class="fs-1 mb-3">📝</div>
            <h5>1. Describe tu caso</h5>
            <p class="text-muted small">Cuéntanos el problema con tu aseguradora en tus propias palabras. Sin tecnicismos.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <div class="fs-1 mb-3">💳</div>
            <h5>2. Paga de forma segura</h5>
            <p class="text-muted small">9,99 € por documento. Pago con tarjeta via Stripe. Reembolso automático si hay algún error.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <div class="fs-1 mb-3">📄</div>
            <h5>3. Descarga y envía</h5>
            <p class="text-muted small">Documento Word y PDF con referencia a la Ley 50/1980, listo para presentar en menos de 30 segundos.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center py-4">
    <div class="col-lg-6">
        <div class="card p-4 bg-light">
            <h5 class="mb-3">Reclamaciones que puedes generar:</h5>
            <ul class="list-unstyled">
                <li class="mb-2">✅ Denegación de cobertura injustificada</li>
                <li class="mb-2">✅ Retraso en el pago de indemnización</li>
                <li class="mb-2">✅ Tasación incorrecta de daños</li>
                <li class="mb-2">✅ Exclusión de cláusula abusiva</li>
                <li class="mb-2">✅ Siniestro de hogar, coche, vida, salud...</li>
            </ul>
        </div>
    </div>
</div>
@endsection
