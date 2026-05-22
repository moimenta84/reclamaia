<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Problema con tu reclamación</title></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
<h2 style="color: #e94560;">Ha ocurrido un problema ❌</h2>
<p>Hola {{ $claim->claimant_name }},</p>
<p>Lamentamos informarte de que ha ocurrido un error técnico al generar tu reclamación contra <strong>{{ $claim->insurer_name }}</strong>.</p>
<p><strong>Tu pago será reembolsado automáticamente en las próximas 24 horas.</strong></p>
<p>Puedes intentarlo de nuevo en cualquier momento:</p>
<a href="{{ route('claim.create') }}" style="background: #1a1a2e; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 12px 0;">Volver a intentarlo</a>
<p style="color: #888; font-size: 12px;">Si el problema persiste, escríbenos a soporte@reclamaia.es<br>ReclamaIA</p>
</body>
</html>
