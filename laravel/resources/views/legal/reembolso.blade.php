@extends('layouts.app')
@section('title', 'Política de reembolso — Reclama')
@section('meta-description', 'Política de reembolso y garantía de satisfacción de Reclama.')

@include('legal._partials.layout-legal')

@section('content')
<article class="legal-doc">
    <h1>Política de reembolso</h1>
    <p class="meta">Última actualización: {{ date('d/m/Y') }}</p>

    <div class="alert alert-info">
        <strong>Garantía de satisfacción:</strong> aunque el derecho de desistimiento no aplica a este servicio digital (art. 103.a LGDCU), ofrecemos reembolso íntegro si el documento generado contiene errores técnicos.
    </div>

    <h2>1. Cuándo procede el reembolso</h2>
    <ul>
        <li>Errores técnicos en el documento (datos del formulario aplicados mal, plantilla rota, fallo de generación).</li>
        <li>El documento no llega al usuario por causa imputable al servicio.</li>
        <li>Doble cobro por error.</li>
        <li>Fallo persistente del servicio que impide su uso (downtime > 24h continuas).</li>
    </ul>

    <h2>2. Cuándo NO procede el reembolso</h2>
    <ul>
        <li>El usuario introdujo datos incorrectos en el formulario.</li>
        <li>La aseguradora rechaza la reclamación. El servicio no garantiza el éxito de la misma, solo la generación de una carta formal con base legal.</li>
        <li>El usuario simplemente cambia de opinión tras descargar el documento (esto está cubierto por la excepción al desistimiento del art. 103.a LGDCU, aceptada en el checkout).</li>
        <li>Suscripciones Pro ya consumidas en periodos anteriores.</li>
    </ul>

    <h2>3. Cómo solicitar un reembolso</h2>
    <ol>
        <li>Enviar email a <a href="mailto:hola@Reclama.es">hola@Reclama.es</a> en un plazo de <strong>7 días naturales</strong> desde la generación del documento.</li>
        <li>Indicar el número de referencia (visible en el email de confirmación o el panel del usuario).</li>
        <li>Describir el problema concreto.</li>
        <li>Adjuntar capturas o el documento si es necesario.</li>
    </ol>

    <h2>4. Plazo de resolución</h2>
    <p>Revisaremos la solicitud y responderemos en un plazo máximo de <strong>7 días hábiles</strong>. Si procede, el reembolso se efectuará en un plazo máximo de <strong>14 días naturales</strong> desde la aprobación, por el mismo medio empleado en el pago.</p>

    <h2>5. Reembolso parcial</h2>
    <p>En casos excepcionales (ej. uso parcial del servicio antes del fallo) puede ofrecerse un reembolso parcial o un crédito equivalente a usar en futuros documentos. El usuario podrá aceptarlo o solicitar el reembolso íntegro.</p>

    <h2>6. Reclamaciones no atendidas</h2>
    <p>Si considera que su solicitud no ha sido atendida correctamente, puede:</p>
    <ul>
        <li>Acudir a la plataforma europea ODR: <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">ec.europa.eu/consumers/odr</a></li>
        <li>Presentar reclamación ante la Junta Arbitral de Consumo de su Comunidad.</li>
        <li>Acudir a la OMIC de su municipio.</li>
    </ul>
</article>
@endsection
