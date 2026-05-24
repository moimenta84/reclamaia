@extends('layouts.app')
@section('title', 'Términos y condiciones — Reclama')
@section('meta-description', 'Condiciones generales de contratación de Reclama conforme a la LGDCU.')

@include('legal._partials.layout-legal')

@section('content')
<article class="legal-doc">
    <h1>Términos y condiciones de contratación</h1>
    <p class="meta">Última actualización: {{ date('d/m/Y') }} · Conformes al RD Legislativo 1/2007 (LGDCU) y Ley 34/2002 (LSSI-CE)</p>

    <h2>1. Objeto</h2>
    <p>Las presentes condiciones regulan el uso de los servicios de generación automatizada de reclamaciones ofrecidos en https://Reclama.es por [TITULAR — completar].</p>

    <h2>2. Servicios y precios</h2>
    <ul>
        <li><strong>Generación de carta (pago por documento):</strong> 9,99 € por documento, IVA incluido (servicio digital — art. 70.1.4º LIVA).</li>
        <li><strong>Plan Pro (suscripción mensual):</strong> 29,99 € al mes, con cargos automáticos hasta cancelación. Incluye reclamaciones ilimitadas, análisis de viabilidad, OCR avanzado, valoración de daños, jurisprudencia, baremo de tráfico y firma electrónica.</li>
    </ul>
    <p>Todos los precios incluyen impuestos vigentes en España. El proveedor podrá modificar los precios en cualquier momento, notificándolo con al menos 30 días de antelación a los suscriptores activos.</p>

    <h2>3. Proceso de contratación</h2>
    <ol>
        <li>El usuario rellena el formulario con los datos del caso.</li>
        <li>Se muestra un resumen para revisión antes del pago.</li>
        <li>El pago se procesa a través de <strong>Stripe</strong> (cifrado PCI-DSS).</li>
        <li>Tras la confirmación del pago, se genera automáticamente el documento en formato Word y PDF.</li>
        <li>Se envía un email con el enlace de descarga, válido durante 30 días.</li>
    </ol>
    <p>El contrato se considera perfeccionado en el momento del cobro del precio.</p>

    <h2>4. Derecho de desistimiento — IMPORTANTE</h2>
    <div class="highlight">
        <p class="mb-2"><strong>Excepción al derecho de desistimiento (art. 103.a LGDCU):</strong></p>
        <p class="mb-0">El servicio consiste en la generación inmediata de un contenido digital no suministrado en soporte material. Al confirmar el pago, el usuario declara expresamente que <strong>SOLICITA el inicio inmediato del servicio</strong> y <strong>RENUNCIA al derecho de desistimiento de 14 días naturales</strong>, conforme al art. 103.a del RD Legislativo 1/2007.</p>
    </div>
    <p>Esta renuncia se acepta mediante un checkbox específico en la pantalla de pago. Sin esta aceptación, el sistema no permite finalizar la compra.</p>

    <h2>5. Garantía y reembolso</h2>
    <p>Aunque el desistimiento de 14 días no aplica, Reclama ofrece una <strong>garantía de satisfacción</strong>:</p>
    <ul>
        <li>Reembolso íntegro si el documento generado tiene errores técnicos (datos incorrectos, plantilla mal aplicada, fallo del sistema).</li>
        <li>Solicitud por email a <a href="mailto:hola@Reclama.es">hola@Reclama.es</a> indicando el problema, en un plazo de 7 días desde la generación.</li>
        <li>Plazo de resolución: 14 días naturales máximo desde la solicitud.</li>
        <li>El reembolso se efectúa por el mismo medio empleado en el pago.</li>
    </ul>
    <p>Detalles completos en la <a href="{{ route('legal.reembolso') }}">Política de Reembolso</a>.</p>

    <h2>6. Cancelación del Plan Pro</h2>
    <p>La suscripción Pro puede cancelarse en cualquier momento desde el panel del usuario o solicitándolo por email. La cancelación tiene efectos al final del periodo de facturación en curso. No se realizan reembolsos prorrateados salvo error técnico atribuible al servicio.</p>

    <h2>7. Obligaciones del usuario</h2>
    <ul>
        <li>Facilitar datos veraces, exactos y actualizados.</li>
        <li>No utilizar el servicio para fines ilícitos.</li>
        <li>Verificar el documento generado antes de presentarlo. El servicio NO sustituye al asesoramiento jurídico individualizado.</li>
        <li>No compartir credenciales de acceso ni revender el servicio sin autorización.</li>
    </ul>

    <h2>8. Responsabilidad y limitaciones</h2>
    <div class="highlight">
        <strong>El servicio es una herramienta automatizada y no constituye asesoramiento jurídico individualizado.</strong> Reclama no garantiza el éxito de la reclamación generada y no responde por las decisiones que tomen las aseguradoras, organismos públicos o tribunales en base al documento.
    </div>
    <p>La responsabilidad de Reclama frente al consumidor está limitada al importe pagado por el servicio en el momento del incidente.</p>

    <h2>9. Protección de datos</h2>
    <p>El tratamiento de los datos personales se regula por la <a href="{{ route('legal.privacidad') }}">Política de Privacidad</a>, que forma parte integrante de estos términos.</p>

    <h2>10. Modificaciones</h2>
    <p>Reclama podrá modificar estos términos en cualquier momento. Las modificaciones se publicarán en esta página y, en caso de afectar a suscripciones activas, se notificarán con 30 días de antelación.</p>

    <h2>11. Ley aplicable y resolución de conflictos</h2>
    <p>Estos términos se rigen por la legislación española. Cualquier controversia se someterá a los Juzgados y Tribunales del domicilio del consumidor (art. 90.2 LGDCU).</p>
    <p>El consumidor puede acudir a:</p>
    <ul>
        <li>Plataforma europea ODR: <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">ec.europa.eu/consumers/odr</a></li>
        <li>Junta Arbitral de Consumo de su Comunidad Autónoma.</li>
        <li>Oficina Municipal de Información al Consumidor (OMIC).</li>
    </ul>
</article>
@endsection
