"""
CENDOJ jurisprudence service.
Searches the CGPJ CENDOJ database for relevant sentences and uses Claude
to extract the most applicable precedents for a given claim.
"""
import os
import re
import json
import httpx
import anthropic

_client = None
CENDOJ_SEARCH = "https://www.poderjudicial.es/search/indexAN.jsp"
CENDOJ_OPEN   = "https://www.poderjudicial.es/search/opendata"


def _get_client() -> anthropic.Anthropic:
    global _client
    if _client is None:
        _client = anthropic.Anthropic(api_key=os.environ["ANTHROPIC_API_KEY"])
    return _client


def _search_cendoj(query: str, rango: str = "AP", num: int = 5) -> list[dict]:
    """
    Query CENDOJ public search. rango: TS=Tribunal Supremo, AP=Audiencia Provincial.
    Returns list of {tribunal, fecha, numero, resumen} dicts.
    Falls back to empty list on any network/parse error.
    """
    try:
        params = {
            "texto":   query,
            "tem":     "AN",
            "rango":   rango,
            "num_hits": num,
        }
        r = httpx.get(CENDOJ_SEARCH, params=params, timeout=8, follow_redirects=True)
        # CENDOJ returns HTML — parse the result summaries
        hits = []
        pattern = re.compile(
            r'<span class="resolucionLink"[^>]*>(.*?)</span>.*?'
            r'<span class="nrg"[^>]*>(.*?)</span>.*?'
            r'<span class="fecha"[^>]*>(.*?)</span>',
            re.DOTALL,
        )
        for m in pattern.finditer(r.text)[:num]:
            hits.append({
                "tribunal": m.group(1).strip(),
                "numero":   m.group(2).strip(),
                "fecha":    m.group(3).strip(),
            })
        return hits
    except Exception:
        return []


_JURISPRUDENCE_SYSTEM = """Eres un experto en jurisprudencia española de seguros.
Tu tarea es identificar sentencias relevantes del Tribunal Supremo y Audiencias Provinciales
aplicables al caso descrito. Responde ÚNICAMENTE con un JSON válido:
{
  "sentencias": [
    {
      "tribunal": "TS / AP Ciudad / Juzgado",
      "sala": "1ª Civil / etc.",
      "numero": "STS 1234/2024 o similar",
      "fecha": "dd/mm/yyyy",
      "doctrina": "Resumen de la doctrina aplicable (2-3 frases)",
      "aplicacion": "Cómo refuerza esta reclamación concreta"
    }
  ],
  "articulos_aplicables": ["Art. X Ley 50/1980", "Art. Y RD ..."],
  "argumentacion_clave": "Párrafo listo para insertar en la carta citando jurisprudencia"
}"""


def get_relevant_jurisprudence(claim_description: str, insurer_name: str, claim_type: str) -> dict:
    """
    Returns relevant Spanish jurisprudence for the given claim.
    First tries CENDOJ, then uses Claude's legal knowledge to supplement.
    """
    # Try CENDOJ for real hits
    query = f"seguro {claim_type} {insurer_name} reclamación denegación cobertura"
    cendoj_hits = _search_cendoj(query, rango="TS")

    cendoj_context = ""
    if cendoj_hits:
        cendoj_context = "\n\nResultados CENDOJ encontrados:\n"
        for h in cendoj_hits:
            cendoj_context += f"- {h.get('tribunal', '')} {h.get('numero', '')} ({h.get('fecha', '')})\n"

    user_prompt = f"""Caso de reclamación de seguros:
Aseguradora: {insurer_name}
Tipo de seguro: {claim_type}
Descripción: {claim_description}
{cendoj_context}
Identifica las sentencias más relevantes del TS y AP aplicables a este caso.
Incluye la doctrina específica y cómo refuerza la reclamación.
Responde SOLO con el JSON."""

    msg = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=1024,
        messages=[{"role": "user", "content": user_prompt}],
        system=_JURISPRUDENCE_SYSTEM,
    )

    raw = msg.content[0].text.strip()
    json_match = re.search(r"\{[\s\S]*\}", raw)
    if json_match:
        raw = json_match.group(0)

    try:
        return json.loads(raw)
    except json.JSONDecodeError:
        return {"sentencias": [], "articulos_aplicables": [], "argumentacion_clave": ""}
