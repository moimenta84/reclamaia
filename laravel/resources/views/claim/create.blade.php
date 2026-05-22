@extends('layouts.app')

@section('title', 'Nueva reclamación')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card p-4">
            <h2 class="mb-1">Genera tu reclamación</h2>
            <p class="text-muted mb-4">Rellena el formulario con los datos de tu caso. Solo te llevará 2 minutos.</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('claim.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo de seguro</label>
                    <select name="claim_type" class="form-select @error('claim_type') is-invalid @enderror" required>
                        <option value="">— Selecciona —</option>
                        <option value="insurance" @selected(old('claim_type') === 'insurance')>Seguro (hogar, coche, vida, salud...)</option>
                    </select>
                    @error('claim_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre de la aseguradora</label>
                    <input type="text" name="insurer_name" value="{{ old('insurer_name') }}"
                           class="form-control @error('insurer_name') is-invalid @enderror"
                           placeholder="Ej. Mapfre, AXA, Allianz, Mutua Madrileña..." required>
                    @error('insurer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Describe tu problema</label>
                    <textarea name="description" rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Explica qué pasó, cuándo ocurrió, qué te dijo la aseguradora y qué quieres conseguir. Cuantos más detalles, mejor." required>{{ old('description') }}</textarea>
                    <div class="form-text">Mínimo 50 caracteres. Sin tecnicismos, escribe como lo contarías a un amigo.</div>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">
                <h6 class="text-muted mb-3">Tus datos (aparecerán en el documento)</h6>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="claimant_name" value="{{ old('claimant_name') }}"
                               class="form-control @error('claimant_name') is-invalid @enderror" required>
                        @error('claimant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">DNI / NIE</label>
                        <input type="text" name="claimant_dni" value="{{ old('claimant_dni') }}"
                               class="form-control @error('claimant_dni') is-invalid @enderror"
                               placeholder="12345678A" required>
                        @error('claimant_dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="claimant_email" value="{{ old('claimant_email', auth()->user()?->email) }}"
                               class="form-control @error('claimant_email') is-invalid @enderror" required>
                        @error('claimant_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono <span class="text-muted">(opcional)</span></label>
                        <input type="tel" name="claimant_phone" value="{{ old('claimant_phone') }}"
                               class="form-control" placeholder="600 123 456">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Dirección postal</label>
                        <input type="text" name="claimant_address" value="{{ old('claimant_address') }}"
                               class="form-control @error('claimant_address') is-invalid @enderror"
                               placeholder="Calle Mayor 1, 28001 Madrid" required>
                        @error('claimant_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Número de póliza <span class="text-muted">(si lo tienes)</span></label>
                        <input type="text" name="policy_number" value="{{ old('policy_number') }}"
                               class="form-control" placeholder="POL-2024-001234">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">
                    Ver resumen y pagar →
                </button>
                <p class="text-center text-muted small mt-2">Precio: 9,99 € · Pago seguro con Stripe · Reembolso automático si hay algún error</p>
            </form>
        </div>
    </div>
</div>
@endsection
