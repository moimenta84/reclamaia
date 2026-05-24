@extends('layouts.app')
@section('title', 'Firma digital eIDAS — Reclama')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <header class="mb-4">
            <a href="{{ route('tools.index') }}" class="small text-muted text-decoration-none">← Herramientas</a>
            <h1 class="fw-bold mt-2 mb-1">Firma digital eIDAS</h1>
            <p class="text-muted mb-0">Firma tu reclamación con validez legal completa conforme al Reglamento (UE) 910/2014.</p>
        </header>

        @if(session('success'))
        <div class="alert alert-success" role="alert" aria-live="polite">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger" role="alert" aria-live="polite">{{ session('error') }}</div>
        @endif

        <div class="card p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0">
                <span style="font-size:1.5rem" aria-hidden="true">🔒</span>
                <div>
                    <div class="fw-semibold text-success">Firma electrónica avanzada</div>
                    <div class="small text-muted">Nivel eIDAS: Avanzada · Proveedor: Signaturit · Validez legal en toda la UE</div>
                </div>
            </div>

            <dl class="row mb-4">
                <dt class="col-sm-4 text-muted">Reclamación</dt>
                <dd class="col-sm-8 fw-semibold">#{{ $claim->id }} — {{ $claim->insurer_name }}</dd>
                <dt class="col-sm-4 text-muted">Tipo</dt>
                <dd class="col-sm-8">{{ ucfirst($claim->claim_type ?? '—') }}</dd>
                <dt class="col-sm-4 text-muted">Importe reclamado</dt>
                <dd class="col-sm-8">{{ $claim->amount_claimed ? number_format($claim->amount_claimed, 2, ',', '.') . ' €' : '—' }}</dd>
                <dt class="col-sm-4 text-muted">Estado firma</dt>
                <dd class="col-sm-8">
                    @if($claim->signed_at)
                        <span class="badge bg-success">Firmado el {{ $claim->signed_at->format('d/m/Y H:i') }}</span>
                    @elseif($claim->signaturit_id)
                        <span class="badge bg-warning text-dark">Pendiente de firma</span>
                    @else
                        <span class="badge bg-secondary">Sin enviar</span>
                    @endif
                </dd>
            </dl>

            @if($claim->signed_at)
            <div class="alert alert-success mb-0">
                <strong>Documento firmado correctamente.</strong>
                Puedes descargar el documento firmado desde tu <a href="{{ route('dashboard') }}">panel de control</a>.
            </div>
            @elseif($claim->signaturit_id)
            <div class="alert alert-info mb-0">
                <strong>Solicitud de firma enviada.</strong>
                Recibirás un email de Signaturit para completar la firma. Comprueba tu bandeja de entrada.
            </div>
            @else
            <form method="POST" action="{{ route('tools.firma.request', $claim) }}" novalidate aria-label="Solicitar firma digital">
                @csrf
                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Datos del firmante</legend>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="signer_name">Nombre completo <span class="required" aria-hidden="true">*</span></label>
                            <input type="text" id="signer_name" name="signer_name" class="form-control @error('signer_name') is-invalid @enderror"
                                value="{{ old('signer_name', auth()->user()->name) }}" required aria-required="true"
                                autocomplete="name" aria-describedby="signer_name_err">
                            @error('signer_name')
                            <div id="signer_name_err" class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="signer_email">Email <span class="required" aria-hidden="true">*</span></label>
                            <input type="email" id="signer_email" name="signer_email" class="form-control @error('signer_email') is-invalid @enderror"
                                value="{{ old('signer_email', auth()->user()->email) }}" required aria-required="true"
                                autocomplete="email" aria-describedby="signer_email_err">
                            @error('signer_email')
                            <div id="signer_email_err" class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="signer_phone">Teléfono (OTP SMS)</label>
                            <input type="tel" id="signer_phone" name="signer_phone" class="form-control @error('signer_phone') is-invalid @enderror"
                                value="{{ old('signer_phone') }}" autocomplete="tel"
                                placeholder="+34 6XX XXX XXX" aria-describedby="signer_phone_hint signer_phone_err">
                            <div id="signer_phone_hint" class="form-text">Opcional. Si lo indicas, recibirás el OTP de verificación por SMS.</div>
                            @error('signer_phone')
                            <div id="signer_phone_err" class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <div class="p-3 rounded mb-4" style="background:#fffbeb;border:1px solid #fde68a">
                    <p class="small mb-0">
                        <strong>¿Qué ocurre al solicitar la firma?</strong> Se enviará un email a la dirección indicada con el documento adjunto y un enlace para firmarlo con validez eIDAS avanzada. El proceso es 100% online, sin instalar software.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Enviar solicitud de firma
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
