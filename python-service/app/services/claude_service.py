import os
from pathlib import Path
import anthropic

_client = None


def _get_client() -> anthropic.Anthropic:
    global _client
    if _client is None:
        _client = anthropic.Anthropic(api_key=os.environ["ANTHROPIC_API_KEY"])
    return _client


def _load_system_prompt() -> str:
    template_path = Path(__file__).parent.parent / "templates" / "insurance" / "prompt_template.txt"
    return template_path.read_text(encoding="utf-8")


def generate_claim_text(claim_data: dict) -> str:
    """Call Claude API and return the formal claim document as Markdown text."""
    system_prompt = _load_system_prompt()

    claimant = claim_data.get("claimant", {})
    user_prompt = f"""Datos del caso:
- Aseguradora: {claim_data.get('insurer_name', 'No especificada')}
- Tipo de seguro: {claim_data.get('claim_type', 'insurance')}
- Número de póliza: {claim_data.get('policy_number', 'No disponible')}
- Descripción del problema: {claim_data.get('description', '')}

Datos del reclamante:
- Nombre completo: {claimant.get('name', '')}
- DNI/NIE: {claimant.get('dni', '')}
- Email: {claimant.get('email', '')}
- Teléfono: {claimant.get('phone', 'No proporcionado')}
- Dirección: {claimant.get('address', '')}

Redacta una carta formal de reclamación lista para presentar.
Incluye: cabecera completa, referencia legal (Ley 50/1980), hechos, petición concreta, cierre formal."""

    message = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=2048,
        messages=[{"role": "user", "content": user_prompt}],
        system=system_prompt,
    )

    return message.content[0].text
