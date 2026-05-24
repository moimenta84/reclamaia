@extends('layouts.app')
@section('title', 'Política de privacidad — Reclama')
@section('meta-description', 'Política de privacidad de Reclama — RGPD y LOPD-GDD.')

@include('legal._partials.layout-legal')

@section('content')
<article class="legal-doc">
    <h1>Política de privacidad</h1>
    <p class="meta">Última actualización: {{ date('d/m/Y') }} · Conforme al RGPD (UE 2016/679) y LOPD-GDD (LO 3/2018)</p>

    <nav class="toc mb-4" aria-label="Índice de contenidos">
        <strong>Contenido:</strong>
        <ol class="mb-0 mt-2">
            <li><a href="#responsable">Responsable del tratamiento</a></li>
            <li><a href="#datos">Datos que recogemos</a></li>
            <li><a href="#finalidades">Finalidades y base jurídica</a></li>
            <li><a href="#destinatarios">Destinatarios y encargados</a></li>
            <li><a href="#conservacion">Plazo de conservación</a></li>
            <li><a href="#derechos">Derechos del usuario</a></li>
            <li><a href="#seguridad">Seguridad</a></li>
            <li><a href="#menores">Menores de edad</a></li>
        </ol>
    </nav>

    <h2 id="responsable">1. Responsable del tratamiento</h2>
    <ul>
        <li><strong>Titular:</strong> [NOMBRE/RAZÓN SOCIAL]</li>
        <li><strong>NIF:</strong> [NIF]</li>
        <li><strong>Dirección postal:</strong> [DIRECCIÓN]</li>
        <li><strong>Email DPO:</strong> <a href="mailto:privacidad@Reclama.es">privacidad@Reclama.es</a></li>
    </ul>

    <h2 id="datos">2. Datos que recogemos</h2>
    <h3>2.1 Datos facilitados por el usuario</h3>
    <ul>
        <li>Identificativos: nombre, apellidos, DNI/NIE, dirección postal, email, teléfono.</li>
        <li>Datos del siniestro: descripción del problema, número de póliza, fechas, importes.</li>
        <li>Datos económicos: información de pago (procesada por Stripe — ver punto 4).</li>
        <li>Documentación PDF: pólizas, partes médicos, informes (solo si el usuario los sube).</li>
    </ul>
    <h3>2.2 Datos recogidos automáticamente</h3>
    <ul>
        <li>IP, navegador, sistema operativo, fecha y hora de acceso.</li>
        <li>Cookies técnicas y, con consentimiento, analíticas (ver <a href="{{ route('legal.cookies') }}">Política de Cookies</a>).</li>
    </ul>

    <h2 id="finalidades">3. Finalidades y base jurídica</h2>
    <table class="table table-bordered table-sm small mt-2">
        <thead style="background:#f1f5f9">
            <tr><th>Finalidad</th><th>Base jurídica RGPD</th><th>Datos</th></tr>
        </thead>
        <tbody>
            <tr><td>Generar la carta de reclamación solicitada</td><td>Ejecución de contrato (art. 6.1.b)</td><td>Datos personales y del siniestro</td></tr>
            <tr><td>Procesar el pago</td><td>Ejecución de contrato (art. 6.1.b)</td><td>Datos de facturación</td></tr>
            <tr><td>Cumplir obligaciones legales contables y fiscales</td><td>Obligación legal (art. 6.1.c)</td><td>Datos de facturación</td></tr>
            <tr><td>Envío de comunicaciones comerciales propias</td><td>Consentimiento (art. 6.1.a) — opt-in opcional</td><td>Email</td></tr>
            <tr><td>Mejorar el servicio (analítica)</td><td>Interés legítimo (art. 6.1.f) o consentimiento</td><td>Datos de navegación anonimizados</td></tr>
        </tbody>
    </table>

    <h2 id="destinatarios">4. Destinatarios y encargados del tratamiento</h2>
    <p>Los datos pueden ser comunicados a los siguientes encargados del tratamiento, sujetos a contratos conforme al art. 28 RGPD:</p>
    <ul>
        <li><strong>Stripe Payments Europe Ltd</strong> (Irlanda) — pasarela de pago. <a href="https://stripe.com/es/privacy" target="_blank" rel="noopener">Política Stripe</a></li>
        <li><strong>Anthropic PBC</strong> (EE.UU.) — modelo de lenguaje Claude API. Los datos se envían pseudonimizados y NO se utilizan para reentrenar modelos. Transferencia internacional al amparo de Cláusulas Contractuales Tipo (CCT).</li>
        <li><strong>Signaturit Solutions S.L.</strong> (España) — firma electrónica avanzada eIDAS (si el usuario activa esta función).</li>
        <li><strong>Google Cloud EMEA Ltd</strong> (Irlanda) — Document AI para OCR (si el usuario sube documentos PDF).</li>
        <li><strong>IONOS SE</strong> (Alemania) — alojamiento en servidor europeo.</li>
    </ul>
    <p>No se realizan otras cesiones a terceros salvo obligación legal (jueces, tribunales, AEAT, AEPD).</p>

    <h2 id="conservacion">5. Plazo de conservación</h2>
    <ul>
        <li><strong>Datos del siniestro y documento generado:</strong> mientras dure la relación + 5 años (art. 1964 CC, prescripción acciones personales).</li>
        <li><strong>Datos de facturación:</strong> 6 años (art. 30 Código de Comercio + 4 años obligaciones fiscales).</li>
        <li><strong>Documentación PDF subida por el usuario:</strong> 90 días tras la generación del documento, salvo que el usuario solicite su eliminación antes.</li>
        <li><strong>Consentimientos:</strong> mientras estén activos + 3 años desde su retirada (prueba de cumplimiento).</li>
    </ul>

    <h2 id="derechos">6. Derechos del usuario</h2>
    <p>Conforme a los arts. 15-22 RGPD y 12-18 LOPD-GDD, el usuario tiene derecho a:</p>
    <ul>
        <li><strong>Acceso</strong> — obtener confirmación y copia de sus datos.</li>
        <li><strong>Rectificación</strong> — corregir datos inexactos.</li>
        <li><strong>Supresión</strong> (derecho al olvido).</li>
        <li><strong>Limitación</strong> del tratamiento.</li>
        <li><strong>Portabilidad</strong> — recibir los datos en formato estructurado.</li>
        <li><strong>Oposición</strong> al tratamiento basado en interés legítimo.</li>
        <li><strong>No ser objeto de decisiones automatizadas</strong> con efectos jurídicos. (La generación del documento no produce decisiones jurídicas automáticas — el usuario decide si lo envía.)</li>
        <li><strong>Retirar el consentimiento</strong> en cualquier momento, sin efecto retroactivo.</li>
    </ul>
    <p>Para ejercer estos derechos: envío de correo a <a href="mailto:privacidad@Reclama.es">privacidad@Reclama.es</a> aportando copia del DNI o documento equivalente. Atenderemos la solicitud en el plazo máximo de un mes (prorrogable a dos en casos complejos).</p>
    <p>Si considera que sus derechos no han sido atendidos, puede presentar reclamación ante la <strong>Agencia Española de Protección de Datos (AEPD)</strong>: <a href="https://www.aepd.es" target="_blank" rel="noopener">www.aepd.es</a></p>

    <h2 id="seguridad">7. Medidas de seguridad</h2>
    <p>Aplicamos medidas técnicas y organizativas apropiadas al nivel de riesgo (art. 32 RGPD):</p>
    <ul>
        <li>Cifrado TLS 1.2+ en tránsito.</li>
        <li>Aislamiento de servicios internos (el microservicio de procesamiento solo es accesible desde el backend principal).</li>
        <li>Control de acceso por roles y autenticación con contraseña cifrada (bcrypt).</li>
        <li>Copias de seguridad cifradas con rotación de 30 días.</li>
        <li>Registro de accesos y auditoría.</li>
    </ul>

    <h2 id="menores">8. Menores de edad</h2>
    <p>Los servicios están dirigidos a mayores de 18 años. No tratamos datos de menores sin consentimiento del titular de la patria potestad (art. 7 LOPD-GDD).</p>
</article>
@endsection
