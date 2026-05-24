@extends('layouts.app')

@section('title', 'Error al generar el documento')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center py-5">
        <div class="card p-5">
            <div class="fs-1 mb-3">⚠️</div>
            <h3>Error al generar el documento</h3>
            <p class="text-muted mb-3">Ha ocurrido un problema técnico al generar tu reclamación.</p>
            <div class="alert alert-info text-start">
                <strong>Tu pago será reembolsado automáticamente en las próximas 24 horas.</strong><br>
                Recibirás un email de confirmación en la dirección que proporcionaste.
            </div>
            <p class="text-muted small">Si tienes alguna duda, escríbenos a <a href="mailto:soporte@Reclama.es">soporte@Reclama.es</a></p>
            <a href="{{ route('claim.create') }}" class="btn btn-primary mt-2">Volver a intentarlo</a>
        </div>
    </div>
</div>
@endsection
