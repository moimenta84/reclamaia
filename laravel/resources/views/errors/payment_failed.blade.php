@extends('layouts.app')

@section('title', 'Error en el pago')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center py-5">
        <div class="card p-5">
            <div class="fs-1 mb-3">💳❌</div>
            <h3>El pago no se ha podido procesar</h3>
            <p class="text-muted mb-4">Tu tarjeta ha sido rechazada. No se ha realizado ningún cargo. Puedes reintentar con otra tarjeta.</p>
            <a href="javascript:history.back()" class="btn btn-primary btn-lg">Volver a intentarlo</a>
            <p class="text-muted small mt-3">Tus datos del formulario se han guardado.</p>
        </div>
    </div>
</div>
@endsection
