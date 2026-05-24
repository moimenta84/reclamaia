@extends('layouts.app')

@section('title', 'Tu reclamación está lista')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 text-center">
        @if($claim->isCompleted() && $claim->document)
            <div class="card p-5">
                <div class="fs-1 mb-3">✅</div>
                <h2 class="mb-2">Tu reclamación está lista</h2>
                <p class="text-muted mb-4">
                    Hemos generado tu carta formal de reclamación contra <strong>{{ $claim->insurer_name }}</strong>.
                    Descárgala y envíala directamente a la aseguradora o al Defensor del Asegurado.
                </p>

                <div class="d-flex gap-3 justify-content-center mb-4">
                    <a href="{{ route('claim.download.word', $claim) }}" class="btn btn-outline-primary btn-lg">
                        📄 Descargar Word (.docx)
                    </a>
                    <a href="{{ route('claim.download.pdf', $claim) }}" class="btn btn-primary btn-lg">
                        📋 Descargar PDF
                    </a>
                </div>

                <div class="alert alert-warning text-start">
                    <strong>Aviso legal:</strong> Este documento es orientativo y generado automáticamente.
                    Consulte con un abogado especializado para casos complejos.
                    Se incluye la referencia a la Ley 50/1980 de Contrato de Seguro y las directrices de la DGSFP.
                </div>

                <hr>

                {{-- Escalation tracker --}}
                @if($claim->escalation_status === 'none' || !$claim->sent_to_insurer_at)
                    <div class="card p-3 bg-light text-start">
                        <h6>📬 Seguimiento automático</h6>
                        <p class="small text-muted mb-2">
                            Cuando envíes la carta, márcalo aquí. Si la aseguradora no responde en 30 días,
                            Reclama genera automáticamente la carta de escalada a la DGSFP y te avisa.
                        </p>
                        @auth
                        <form method="POST" action="{{ route('claim.mark-sent', $claim) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-success">He enviado la carta a la aseguradora</button>
                        </form>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary">Crear cuenta para activar seguimiento</a>
                        @endauth
                    </div>
                @elseif($claim->escalation_status === 'dgsfp_escalated')
                    <div class="alert alert-info text-start mt-3">
                        <strong>⚖️ Carta de escalada generada</strong> — La aseguradora no respondió en 30 días.
                        Se ha generado la carta de escalada a la DGSFP.
                        @if($claim->escalation_document_path)
                            <br><a href="{{ route('claim.download.escalation', $claim) }}" class="btn btn-sm btn-info mt-2">Descargar carta de escalada</a>
                        @endif
                    </div>
                @endif

                @guest
                    <hr>
                    <h5>¿Quieres guardar esta reclamación?</h5>
                    <p class="text-muted small">Crea una cuenta gratuita para acceder a tus documentos desde cualquier dispositivo y volver a descargarlos sin pagar.</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">Crear cuenta gratuita</a>
                @endguest
            </div>
        @elseif($claim->status === 'processing')
            <div class="card p-5">
                <div class="spinner-border text-primary mb-3 mx-auto" style="width:3rem;height:3rem"></div>
                <h3>Generando tu documento...</h3>
                <p class="text-muted">El sistema está redactando tu reclamación. Esto puede tardar hasta 30 segundos.</p>
                <script>setTimeout(() => location.reload(), 5000);</script>
            </div>
        @elseif($claim->status === 'failed')
            <div class="card p-5">
                <div class="fs-1 mb-3">❌</div>
                <h3>Error al generar el documento</h3>
                <p class="text-muted">Ha ocurrido un problema técnico. <strong>Tu pago será reembolsado automáticamente en 24 horas.</strong></p>
                <p class="text-muted small">Si tienes alguna duda, escríbenos a soporte@Reclama.es</p>
                <a href="{{ route('claim.create') }}" class="btn btn-primary mt-2">Volver a intentarlo</a>
            </div>
        @else
            <div class="card p-5">
                <h3>Esperando confirmación de pago</h3>
                <p class="text-muted">El pago está siendo procesado. La página se actualizará automáticamente.</p>
                <script>setTimeout(() => location.reload(), 5000);</script>
            </div>
        @endif
    </div>
</div>
@endsection
