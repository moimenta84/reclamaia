@extends('layouts.app')
@section('title', 'Aviso legal — Reclama')
@section('meta-description', 'Aviso legal de Reclama conforme a la Ley 34/2002 de Servicios de la Sociedad de la Información.')

@include('legal._partials.layout-legal')

@section('content')
<article class="legal-doc">
    <h1>Aviso legal</h1>
    <p class="meta">Última actualización: {{ date('d/m/Y') }} · Conforme a la Ley 34/2002 (LSSI-CE)</p>

    <h2>1. Identificación del prestador</h2>
    <p>En cumplimiento del art. 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), se informa de los siguientes datos:</p>
    <ul>
        <li><strong>Titular:</strong> [NOMBRE/RAZÓN SOCIAL — completar antes de producción]</li>
        <li><strong>NIF/CIF:</strong> [NIF — completar]</li>
        <li><strong>Domicilio social:</strong> [DIRECCIÓN COMPLETA — completar]</li>
        <li><strong>Email de contacto:</strong> <a href="mailto:hola@Reclama.es">hola@Reclama.es</a></li>
        <li><strong>Dominio web:</strong> https://Reclama.es</li>
        <li><strong>Registro Mercantil:</strong> [DATOS REGISTRALES — completar si aplica]</li>
    </ul>

    <h2>2. Objeto del sitio web</h2>
    <p>Reclama proporciona una herramienta automatizada para generar borradores de cartas de reclamación a aseguradoras, basados en la Ley 50/1980 de Contrato de Seguro, RD 1386/2011 y normativa sectorial de la DGSFP.</p>
    <div class="highlight">
        <strong>Importante:</strong> Los documentos generados son de carácter orientativo. Reclama no presta servicios de asesoramiento jurídico individualizado y no sustituye a un abogado o gestor profesional.
    </div>

    <h2>3. Condiciones de uso</h2>
    <p>El uso del sitio implica la aceptación expresa de este Aviso Legal y de las <a href="{{ route('legal.terminos') }}">Condiciones Generales de Contratación</a>. El usuario se compromete a hacer un uso lícito del sitio y a no realizar conductas que puedan dañar la imagen, los intereses y los derechos de Reclama o de terceros.</p>

    <h2>4. Propiedad intelectual e industrial</h2>
    <p>Todos los contenidos del sitio (textos, imágenes, código fuente, marcas, diseños) son propiedad del titular o cuenta con autorización para su uso, y están protegidos por la legislación española e internacional de propiedad intelectual e industrial. Queda prohibida cualquier reproducción, distribución, comunicación pública o transformación sin autorización expresa.</p>

    <h2>5. Responsabilidad</h2>
    <p>Reclama no se hace responsable de:</p>
    <ul>
        <li>Daños derivados del uso indebido de los documentos generados.</li>
        <li>Interrupciones técnicas, caídas de servicio o pérdidas de datos.</li>
        <li>Decisiones jurídicas o administrativas adoptadas por el usuario en base a los documentos.</li>
        <li>Contenidos de sitios externos enlazados.</li>
    </ul>

    <h2>6. Jurisdicción y ley aplicable</h2>
    <p>Las relaciones derivadas del uso de este sitio se rigen por la legislación española. Cualquier controversia se someterá a los Juzgados y Tribunales del domicilio del titular, salvo que la normativa aplicable establezca otro fuero imperativo para el consumidor.</p>

    <h2>7. Resolución extrajudicial de conflictos</h2>
    <p>Conforme al Reglamento (UE) 524/2013, el consumidor puede acudir a la plataforma europea de resolución de litigios en línea: <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr</a></p>
</article>
@endsection
