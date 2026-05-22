import os
from datetime import datetime
import anthropic

_client = None


def _get_client() -> anthropic.Anthropic:
    global _client
    if _client is None:
        _client = anthropic.Anthropic(api_key=os.environ["ANTHROPIC_API_KEY"])
    return _client


SYSTEM_PROMPT = """Eres un abogado especialista en derecho de seguros español.
Redactas cartas de escalada formal cuando una aseguradora no responde a una reclamación en el plazo legal.
Tus cartas se dirigen simultáneamente a:
1. La aseguradora (recordatorio de obligación legal de respuesta)
2. La Dirección General de Seguros y Fondos de Pensiones (DGSFP) como queja formal

El tono es firme, formal y hace referencias explícitas a:
- Art. 54 Ley 50/1980: obligación de resolver en 3 meses
- Reglamento de Ordenación y Supervisión de los Seguros Privados
- Posibilidad de reclamación ante el Defensor del Pueblo"""


def generate_escalation_letter(claim_data: dict) -> str:
    """Generate a 30-day escalation letter to the insurer + DGSFP."""
    days_elapsed = claim_data.get("days_elapsed", 30)
    original_claim_date = claim_data.get("sent_at", "hace 30 días")
    claimant = claim_data.get("claimant", {})

    user_prompt = f"""Han pasado {days_elapsed} días desde que se envió la reclamación a la aseguradora sin respuesta.
Redacta la carta de escalada formal con:

DATOS DE LA RECLAMACIÓN ORIGINAL:
- Aseguradora: {claim_data.get('insurer_name', '')}
- Fecha de envío de la reclamación: {original_claim_date}
- Tipo de problema: {claim_data.get('description', '')}
- Número de póliza: {claim_data.get('policy_number', 'No disponible')}

DATOS DEL RECLAMANTE:
- Nombre: {claimant.get('name', '')}
- DNI/NIE: {claimant.get('dni', '')}
- Dirección: {claimant.get('address', '')}

La carta debe:
1. Recordar que han pasado {days_elapsed} días sin respuesta (violación del Art. 54 Ley 50/1980)
2. Exigir respuesta en 15 días hábiles adicionales
3. Indicar que se interpone queja simultánea ante la DGSFP
4. Advertir de posible reclamación ante el Defensor del Asegurado y los tribunales
5. Fecha actual: {datetime.now().strftime('%d de %B de %Y')}"""

    message = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=2048,
        messages=[{"role": "user", "content": user_prompt}],
        system=SYSTEM_PROMPT,
    )

    return message.content[0].text
