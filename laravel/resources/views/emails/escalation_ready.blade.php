<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Tu reclamación ha sido escalada</title></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
<h2 style="color: #1a1a2e;">Tu reclamación ha sido escalada a la DGSFP ⚖️</h2>
<p>Hola {{ $claim->claimant_name }},</p>
<p>Han pasado <strong>30 días</strong> desde que enviaste tu reclamación a <strong>{{ $claim->insurer_name }}</strong> sin recibir respuesta.</p>
<p>Reclama ha generado automáticamente una <strong>carta de escalada formal</strong> dirigida a:</p>
<ul>
    <li>La aseguradora (recordatorio de obligación legal)</li>
    <li>La Dirección General de Seguros y Fondos de Pensiones (DGSFP)</li>
</ul>
<p>Accede a tu panel para descargar la carta de escalada:</p>
<a href="{{ route('dashboard') }}" style="background: #e94560; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 12px 0;">Ver mi reclamación escalada</a>
<div style="background: #f8f9fa; padding: 12px; border-radius: 4px; margin-top: 16px;">
    <strong>¿Qué pasa ahora?</strong><br>
    La aseguradora tiene 15 días hábiles adicionales para responder. Si sigue sin hacerlo, puedes presentar la documentación directamente en la DGSFP.
</div>
<p style="color: #888; font-size: 12px; margin-top: 20px;">Reclama · Este servicio es automático y gratuito para usuarios registrados.</p>
</body>
</html>
