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

    # Build policy clauses section if available
    policy_section = ""
    policy_clauses = claim_data.get("policy_clauses")
    if policy_clauses and isinstance(policy_clauses, dict):
        clausulas = policy_clauses.get("clausulas_clave", [])
        if clausulas:
            policy_section = "\n\nCLÁUSULAS DE LA PÓLIZA (citar explícitamente en la carta):\n"
            for c in clausulas[:5]:  # max 5 clauses to avoid token bloat
                policy_section += f"- {c.get('titulo', '')}"
                if c.get('articulo'):
                    policy_section += f" (Art. {c['articulo']})"
                policy_section += f": {c.get('texto', '')}\n"
        limite = policy_clauses.get("limite_indemnizacion")
        if limite:
            policy_section += f"\nLímite de indemnización según póliza: {limite}\n"

    # Build viability section if available
    viability_section = ""
    viability = claim_data.get("viability_analysis")
    if viability and isinstance(viability, dict):
        base_legal = viability.get("base_legal", "")
        recomendacion = viability.get("recomendacion", "")
        if base_legal:
            viability_section = f"\n\nBASE LEGAL IDENTIFICADA: {base_legal}"
        if recomendacion:
            viability_section += f"\nINCLUIR EN LA CARTA: {recomendacion}"

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
- Dirección: {claimant.get('address', '')}{policy_section}{viability_section}

Redacta una carta formal de reclamación lista para presentar.
Incluye: cabecera completa, referencia legal (Ley 50/1980), hechos, petición concreta, cierre formal.
{"Si se han proporcionado cláusulas de la póliza, cítalas explícitamente por número de artículo." if policy_section else ""}"""

    message = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=2048,
        messages=[{"role": "user", "content": user_prompt}],
        system=system_prompt,
    )

    return message.content[0].text
