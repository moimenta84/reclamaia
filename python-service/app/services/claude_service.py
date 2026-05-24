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

    # Fetch BOE normativa vigente (best-effort, cached in service)
    boe_section = ""
    try:
        import asyncio
        from app.services.boe_service import get_normativa_para_reclamacion
        loop = asyncio.new_event_loop()
        normas = loop.run_until_complete(
            get_normativa_para_reclamacion(claim_data.get("claim_type", "seguro"))
        )
        loop.close()
        refs = []
        for key, n in normas.items():
            if isinstance(n, dict) and n.get("titulo"):
                fecha = n.get("fecha_mod") or n.get("fecha_pub", "")
                refs.append(f"- {n['titulo']} (última actualización BOE: {fecha})")
        if refs:
            boe_section = "\n\nNORMATIVA VIGENTE (fuente: BOE — citar versión actualizada):\n" + "\n".join(refs)
    except Exception:
        pass

    # Fetch CENDOJ jurisprudence (best-effort, don't fail if unavailable)
    jurisprudence_section = ""
    try:
        from app.services.cendoj_service import get_relevant_jurisprudence
        juris = get_relevant_jurisprudence(
            claim_data.get("description", ""),
            claim_data.get("insurer_name", ""),
            claim_data.get("claim_type", "insurance"),
        )
        argumentacion = juris.get("argumentacion_clave", "")
        articulos = juris.get("articulos_aplicables", [])
        sentencias = juris.get("sentencias", [])
        if argumentacion or sentencias:
            jurisprudence_section = "\n\nJURISPRUDENCIA APLICABLE (incluir en los fundamentos de derecho):\n"
            for s in sentencias[:3]:
                jurisprudence_section += f"- {s.get('numero','')} ({s.get('fecha','')}): {s.get('doctrina','')}\n"
            if articulos:
                jurisprudence_section += f"Artículos: {', '.join(articulos)}\n"
            if argumentacion:
                jurisprudence_section += f"\nArgumentación sugerida:\n{argumentacion}\n"
    except Exception:
        pass

    user_prompt = f"""Datos del caso:
- Aseguradora: {claim_data.get('insurer_name', 'No especificada')}
- Tipo de seguro: {claim_data.get('claim_type', 'insurance')}
- Número de póliza: {claim_data.get('policy_number', 'No disponible')}
- Descripción del problema: {claim_data.get('description', '')}
{boe_section}
Datos del reclamante:
- Nombre completo: {claimant.get('name', '')}
- DNI/NIE: {claimant.get('dni', '')}
- Email: {claimant.get('email', '')}
- Teléfono: {claimant.get('phone', 'No proporcionado')}
- Dirección: {claimant.get('address', '')}{policy_section}{viability_section}{jurisprudence_section}

Redacta una carta formal de reclamación lista para presentar.
Incluye: cabecera completa, referencia legal (Ley 50/1980), hechos, fundamentos de derecho con jurisprudencia citada, petición concreta, cierre formal.
{"Cita las cláusulas de la póliza explícitamente por número de artículo." if policy_section else ""}
{"Cita las sentencias del TS y AP proporcionadas en los fundamentos de derecho." if jurisprudence_section else ""}
{"Verifica que citas la versión actualizada de la normativa según las fechas BOE proporcionadas." if boe_section else ""}"""

    message = _get_client().messages.create(
        model="claude-sonnet-4-6",
        max_tokens=2048,
        messages=[{"role": "user", "content": user_prompt}],
        system=system_prompt,
    )

    return message.content[0].text
