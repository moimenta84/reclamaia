@extends('layouts.app')
@section('title', 'Política de cookies — Reclama')
@section('meta-description', 'Política de cookies de Reclama conforme a la LSSI y guía AEPD 2024.')

@include('legal._partials.layout-legal')

@section('content')
<article class="legal-doc">
    <h1>Política de cookies</h1>
    <p class="meta">Última actualización: {{ date('d/m/Y') }} · Conforme a la LSSI-CE y Guía AEPD de Cookies 2024</p>

    <h2>1. ¿Qué son las cookies?</h2>
    <p>Las cookies son archivos pequeños que se descargan al dispositivo del usuario al visitar un sitio web. Permiten recordar información sobre la navegación, las preferencias y, en su caso, identificar al usuario.</p>

    <h2>2. Tipos de cookies que usamos</h2>
    <table class="table table-bordered table-sm small">
        <thead style="background:#f1f5f9">
            <tr>
                <th>Cookie</th>
                <th>Finalidad</th>
                <th>Tipo</th>
                <th>Duración</th>
                <th>Consentimiento</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>laravel_session</code></td>
                <td>Mantener la sesión del usuario.</td>
                <td>Técnica propia</td>
                <td>2 horas</td>
                <td>Exenta (necesaria)</td>
            </tr>
            <tr>
                <td><code>XSRF-TOKEN</code></td>
                <td>Protección contra CSRF.</td>
                <td>Técnica propia</td>
                <td>2 horas</td>
                <td>Exenta (necesaria)</td>
            </tr>
            <tr>
                <td><code>Reclama_consent</code></td>
                <td>Recordar la preferencia de cookies del usuario.</td>
                <td>Técnica propia</td>
                <td>12 meses</td>
                <td>Exenta (necesaria)</td>
            </tr>
            <tr>
                <td><code>_ga</code>, <code>_ga_*</code></td>
                <td>Google Analytics (estadísticas agregadas).</td>
                <td>Analítica de tercero</td>
                <td>13 meses</td>
                <td>Requerido (opt-in)</td>
            </tr>
        </tbody>
    </table>

    <h2>3. Tu consentimiento</h2>
    <p>En tu primera visita mostramos un banner donde puedes:</p>
    <ul>
        <li><strong>Aceptar todas</strong> — incluyendo analíticas.</li>
        <li><strong>Rechazar todas</strong> — solo se activan las técnicas necesarias.</li>
        <li><strong>Configurar</strong> — elegir individualmente cada categoría.</li>
    </ul>
    <p>Puedes retirar o modificar tu consentimiento en cualquier momento haciendo clic en el botón "Configurar cookies" del pie de página.</p>

    <h2>4. Cómo deshabilitar las cookies</h2>
    <p>Además de la herramienta de configuración, puedes bloquear las cookies desde la configuración de tu navegador:</p>
    <ul>
        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
        <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank" rel="noopener">Mozilla Firefox</a></li>
        <li><a href="https://support.apple.com/es-es/HT201265" target="_blank" rel="noopener">Safari</a></li>
        <li><a href="https://support.microsoft.com/es-es/microsoft-edge" target="_blank" rel="noopener">Microsoft Edge</a></li>
    </ul>

    <h2>5. Transferencias internacionales</h2>
    <p>Google Analytics implica transferencia internacional de datos a EE.UU. (Google LLC), amparada por el Marco de Privacidad de Datos UE-EE.UU. (Data Privacy Framework) aprobado por la Comisión Europea el 10/07/2023.</p>

    <h2>6. Más información</h2>
    <p>Para más información sobre el tratamiento de datos personales, consulte nuestra <a href="{{ route('legal.privacidad') }}">Política de Privacidad</a>.</p>

    <div class="mt-4">
        <button id="open-consent-prefs" class="btn btn-outline-primary">
            Volver a configurar mis cookies
        </button>
    </div>
</article>
@endsection

@push('scripts')
<script>
document.getElementById('open-consent-prefs')?.addEventListener('click', () => {
    document.cookie = 'Reclama_consent=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    window.location.reload();
});
</script>
@endpush
