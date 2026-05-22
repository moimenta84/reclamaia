@extends('layouts.app')

@section('title', 'Mis reclamaciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Mis reclamaciones</h3>
    <div class="d-flex gap-2">
        @if(auth()->user()->hasActiveSubscription())
            <span class="badge bg-success fs-6 px-3 py-2">Plan Pro activo</span>
        @else
            <a href="{{ route('subscription.plans') }}" class="btn btn-outline-primary btn-sm">Hacerse Pro — 29,99 €/mes</a>
        @endif
        <a href="{{ route('claim.create') }}" class="btn btn-primary btn-sm">Nueva reclamación</a>
    </div>
</div>

@if($claims->isEmpty())
    <div class="card p-5 text-center">
        <p class="text-muted mb-3">Todavía no tienes ninguna reclamación.</p>
        <a href="{{ route('claim.create') }}" class="btn btn-primary">Generar mi primera reclamación</a>
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Aseguradora</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($claims as $claim)
                    <tr>
                        <td class="text-muted small">{{ $claim->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-semibold">{{ $claim->insurer_name }}</td>
                        <td><span class="badge bg-secondary">Seguro</span></td>
                        <td>
                            @if($claim->status === 'completed')
                                <span class="badge bg-success">Completada</span>
                            @elseif($claim->status === 'processing')
                                <span class="badge bg-warning text-dark">Generando...</span>
                            @elseif($claim->status === 'failed')
                                <span class="badge bg-danger">Error</span>
                            @else
                                <span class="badge bg-light text-dark">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            @if($claim->isCompleted() && $claim->document)
                                <a href="{{ route('claim.download.word', $claim) }}" class="btn btn-sm btn-outline-secondary me-1">Word</a>
                                <a href="{{ route('claim.download.pdf', $claim) }}" class="btn btn-sm btn-outline-primary">PDF</a>
                            @elseif($claim->status === 'processing')
                                <a href="{{ route('claim.download', $claim->id) }}" class="btn btn-sm btn-outline-secondary">Ver estado</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $claims->links() }}</div>
@endif
@endsection
