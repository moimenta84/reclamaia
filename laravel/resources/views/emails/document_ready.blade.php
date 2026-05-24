<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Tu reclamación está lista</title></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
<h2 style="color: #1a1a2e;">Tu reclamación está lista ✅</h2>
<p>Hola {{ $claim->claimant_name }},</p>
<p>Tu carta formal de reclamación contra <strong>{{ $claim->insurer_name }}</strong> ha sido generada y está lista para descargar.</p>
<div style="margin: 24px 0;">
    <a href="{{ $wordUrl }}" style="background: #e94560; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin-right: 12px;">Descargar Word</a>
    <a href="{{ $pdfUrl }}" style="background: #1a1a2e; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">Descargar PDF</a>
</div>
<p style="color: #888; font-size: 12px;">
    <strong>Aviso legal:</strong> Este documento es orientativo. Consulte con un abogado para casos complejos.<br>
    Reclama — <a href="{{ config('app.url') }}">Reclama.es</a>
</p>
</body>
</html>
