"""
BOE (Boletín Oficial del Estado) service.
Fetches current Spanish legislation to ensure claims always cite the latest normativa.
No API key required — completely public.
"""
import httpx
import xml.etree.ElementTree as ET
from typing import Optional

BOE_API = "https://www.boe.es/datosabiertos/api"
BOE_BASE = "https://www.boe.es"

NORMATIVA_IDS = {
    "LCS":       "BOE-A-1980-20955",   # Ley 50/1980 Contrato de Seguro
    "LEY35":     "BOE-A-2015-11117",   # Ley 35/2015 baremo tráfico
    "LGDCU":     "BOE-A-2007-20555",   # Ley consumidores
    "LOPD":      "BOE-A-2018-16673",   # LOPD-GDD LO 3/2018
    "LSSI":      "BOE-A-2002-13758",   # LSSI-CE
}

async def get_norma_metadata(norma_key: str) -> dict:
    """Return title, last modification date and URL for a known norma."""
    norma_id = NORMATIVA_IDS.get(norma_key.upper())
    if not norma_id:
        return {"error": f"Norma '{norma_key}' no reconocida"}

    url = f"{BOE_API}/legislacion/norma/{norma_id}"
    try:
        async with httpx.AsyncClient(timeout=10) as client:
            r = await client.get(url, headers={"Accept": "application/json"})
            if r.status_code == 200:
                data = r.json()
                entry = data.get("data", {})
                return {
                    "id":            norma_id,
                    "titulo":        entry.get("titulo", ""),
                    "fecha_pub":     entry.get("fecha_publicacion", ""),
                    "fecha_mod":     entry.get("fecha_actualizacion", ""),
                    "url":           f"{BOE_BASE}/buscar/act.php?id={norma_id}",
                    "vigente":       entry.get("vigente", True),
                }
    except Exception as e:
        return {"error": str(e)}
    return {"error": "Sin respuesta del BOE"}


async def search_boe(query: str, date_from: Optional[str] = None) -> list[dict]:
    """
    Search BOE for normativa/anuncios matching query.
    Returns list of {titulo, fecha, seccion, url}.
    """
    params = {"q": query, "sort": "fecha", "order": "desc"}
    if date_from:
        params["fechaDesde"] = date_from

    try:
        async with httpx.AsyncClient(timeout=12) as client:
            r = await client.get(
                f"{BOE_API}/buscar/anuncios",
                params=params,
                headers={"Accept": "application/json"},
            )
            if r.status_code == 200:
                items = r.json().get("data", {}).get("response", {}).get("docs", [])
                return [
                    {
                        "titulo":  d.get("titulo", ""),
                        "fecha":   d.get("fecha_publicacion", ""),
                        "seccion": d.get("seccion", ""),
                        "url":     f"{BOE_BASE}/diario_boe/txt.php?id={d.get('id','')}",
                    }
                    for d in items[:10]
                ]
    except Exception as e:
        return [{"error": str(e)}]
    return []


async def get_normativa_para_reclamacion(tipo_seguro: str) -> dict:
    """
    Return the key normativa references for a given insurance type.
    Used to inject into the claim generation prompt.
    """
    base = {
        "LCS":   await get_norma_metadata("LCS"),
        "LGDCU": await get_norma_metadata("LGDCU"),
    }

    tipo = tipo_seguro.lower()
    if any(w in tipo for w in ["auto", "tráfico", "trafico", "vehículo", "vehiculo", "coche", "moto"]):
        base["LEY35"] = await get_norma_metadata("LEY35")
    if any(w in tipo for w in ["datos", "privacidad", "gdpr", "rgpd"]):
        base["LOPD"] = await get_norma_metadata("LOPD")

    return base
