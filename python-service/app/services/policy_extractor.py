import os
import re
import json
import anthropic
from pypdf import PdfReader


_client = None


def _get_client() -> anthropic.Anthropic:
    global _client
    if _client is None:
        _client = anthropic.Anthropic(api_key=os.environ["ANTHROPIC_API_KEY"])
    return _client


def extract_text_from_pdf(pdf_path: str) -> str:
    """Extract raw text from a PDF file (max 50 pages)."""
    reader = PdfReader(pdf_path)
    pages = reader.pages[:50]
    return "\n".join(page.extract_text() or "" for page in pages)


SYSTEM_PROMPT = """Eres un especialista en análisis de pólizas de seguros españolas.
Tu tarea es extraer las cláusulas más relevantes de una póliza para apoyar una reclamación.

Responde ÚNICAMENTE con un JSON válido con esta estructura:
{
  "numero_poliza": "número de póliza si aparece",
  "aseguradora": "nombre de la aseguradora",
  "tipo_seguro": "tipo de seguro (hogar, auto, vida, salud...)",
  "coberturas_principales": ["lista de coberturas cubiertas relevantes"],
  "exclusiones_relevantes": ["exclusiones que podrían afectar la reclamación"],
  "clausulas_clave": [
    {
      "titulo": "nombre de la cláusula",
      "articulo": "número de artículo si aplica",
      "texto": "extracto literal de la cláusula más relevante (max 200 chars)",
      "relevancia": "por qué es importante para la reclamación"
    }
  ],
  "periodo_carencia": "si aplica, descripción del período de carencia",
  "limite_indemnizacion": "importe máximo asegurado si aparece",
  "plazo_notificacion": "plazo para notificar el siniestro si aparece"
}"""


def extract_policy_clauses(pdf_path: str, claim_description: str) -> dict:
    """Extract relevant clauses from policy PDF for the given claim."""
    text = extract_text_from_pdf(pdf_path)

    # Limit text to avoid token limits (first 15k chars covers most policies)
    text = text[:15000]

    if len(text.strip()) < 100:
        return {"error": "No se pudo extraer texto del PDF. Asegúrate de que no es un PDF escaneado."}

    user_prompt = f"""Analiza esta póliza de seguros y extrae las cláusulas relevantes para la siguiente reclamación:

RECLAMACIÓN: {claim_description}

TEXTO DE LA PÓLIZA:
{text}

Responde SOLO con el JSON."""

    message = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=2048,
        messages=[{"role": "user", "content": user_prompt}],
        system=SYSTEM_PROMPT,
    )

    raw = message.content[0].text.strip()
    json_match = re.search(r"\{[\s\S]*\}", raw)
    if json_match:
        raw = json_match.group(0)

    return json.loads(raw)
