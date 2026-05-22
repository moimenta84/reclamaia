import os
from pathlib import Path
import anthropic
import json
import re

_client = None


def _get_client() -> anthropic.Anthropic:
    global _client
    if _client is None:
        _client = anthropic.Anthropic(api_key=os.environ["ANTHROPIC_API_KEY"])
    return _client


SYSTEM_PROMPT = """Eres un abogado especialista en derecho de seguros español con 20 años de experiencia
analizando reclamaciones. Tu tarea es evaluar la viabilidad de una reclamación antes de redactarla.

Debes responder ÚNICAMENTE con un objeto JSON válido con esta estructura exacta:
{
  "score": "alta" | "media" | "baja",
  "probabilidad_estimada": "porcentaje estimado de éxito como número entero (0-100)",
  "resumen": "1-2 frases explicando el veredicto en lenguaje claro para el reclamante",
  "puntos_fuertes": ["lista de 2-3 puntos a favor del reclamante"],
  "puntos_debiles": ["lista de 1-2 puntos débiles o riesgos"],
  "base_legal": "artículo o norma principal aplicable (Ley 50/1980, DGSFP...)",
  "recomendacion": "qué debería incluir el usuario en la carta para maximizar el éxito",
  "fuera_de_ambito": false
}

Si el caso claramente no es una reclamación de seguros, devuelve fuera_de_ambito: true con score: "baja"."""


def analyze_viability(claim_data: dict) -> dict:
    """Analyze claim viability before payment. Returns structured assessment."""
    user_prompt = f"""Evalúa la viabilidad de esta reclamación:

Aseguradora: {claim_data.get('insurer_name', 'No especificada')}
Tipo de seguro: {claim_data.get('claim_type', 'insurance')}
Descripción: {claim_data.get('description', '')}
Número de póliza: {claim_data.get('policy_number', 'No disponible')}

Responde SOLO con el JSON, sin texto adicional."""

    message = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=1024,
        messages=[{"role": "user", "content": user_prompt}],
        system=SYSTEM_PROMPT,
    )

    raw = message.content[0].text.strip()

    # Extract JSON if wrapped in markdown code blocks
    json_match = re.search(r"\{[\s\S]*\}", raw)
    if json_match:
        raw = json_match.group(0)

    return json.loads(raw)
