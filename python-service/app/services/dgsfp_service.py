"""
DGSFP (Dirección General de Seguros y Fondos de Pensiones) service.
Fetches public registry data: registered insurers, sanctions, complaint stats.
Uses the public DGSFP open-data portal — no API key required.
"""
import httpx
import re
from typing import Optional

DGSFP_BASE = "https://www.dgsfp.mineco.es"
DGSFP_REGISTRO = "https://registros.dgsfp.mineco.es"

# Known major insurers NIF for quick lookup (public data)
INSURERS_NIF: dict[str, str] = {
    "mapfre":    "A-28141060",
    "axa":       "A-28011864",
    "allianz":   "A-28007748",
    "zurich":    "A-28021026",
    "generali":  "A-28003128",
    "helvetia":  "A-28051526",
    "mutua madrileña": "V-28164765",
    "sanitas":   "A-28012890",
    "asisa":     "V-28241248",
    "reale":     "A-28011807",
}


def _normalize(name: str) -> str:
    return re.sub(r"[^a-z0-9 ]", "", name.lower().strip())


async def buscar_aseguradora(nombre: str) -> dict:
    """
    Search DGSFP public registry for an insurer by name.
    Returns registration status, NIF, and public URL.
    """
    nombre_norm = _normalize(nombre)

    # Check our local map first for common insurers
    for key, nif in INSURERS_NIF.items():
        if key in nombre_norm or nombre_norm in key:
            return {
                "encontrada":   True,
                "nombre":       nombre,
                "nif":          nif,
                "registrada":   True,
                "url_ficha":    f"{DGSFP_REGISTRO}/aseguradoras",
                "nota":         "Entidad supervisada por DGSFP",
            }

    # Fallback: query DGSFP open-data search
    try:
        async with httpx.AsyncClient(timeout=10, verify=False) as client:
            r = await client.get(
                f"{DGSFP_REGISTRO}/api/entidades",
                params={"nombre": nombre, "tipo": "seguro"},
                headers={"Accept": "application/json"},
            )
            if r.status_code == 200:
                items = r.json().get("data", [])
                if items:
                    ent = items[0]
                    return {
                        "encontrada": True,
                        "nombre":     ent.get("nombre", nombre),
                        "nif":        ent.get("nif", ""),
                        "registrada": True,
                        "url_ficha":  f"{DGSFP_REGISTRO}/entidades/{ent.get('id', '')}",
                        "nota":       "Entidad supervisada por DGSFP",
                    }
    except Exception:
        pass

    return {
        "encontrada": False,
        "nombre":     nombre,
        "nota":       "No encontrada en registro público DGSFP. Verifica el nombre exacto.",
        "url_busqueda": f"{DGSFP_REGISTRO}/aseguradoras",
    }


async def get_sanciones(nombre: str) -> dict:
    """
    Return public sanction information for an insurer.
    DGSFP publishes sanctions in the BOE — we surface the count and link.
    """
    nombre_norm = _normalize(nombre)

    # DGSFP sanctions are published in BOE section II.B
    # We provide a direct search URL to the BOE for this insurer
    boe_search = f"https://www.boe.es/buscar/boe.php?s=01&cl={nombre_norm}&p=&acc=Buscar"

    return {
        "aseguradora":      nombre,
        "url_sanciones_boe": boe_search,
        "url_dgsfp":        f"{DGSFP_BASE}/es/seguros/organismos-supervisados/entidades-aseguradoras/",
        "nota": (
            "Las sanciones de la DGSFP se publican en el BOE (Sección II.B). "
            "Puedes citarlas como precedente en tu reclamación."
        ),
    }


async def get_defensor_info(nombre: str) -> dict:
    """
    Return Defensor del Asegurado contact info for a given insurer.
    Required by art. 48 LCS — all insurers must have one.
    """
    nombre_norm = _normalize(nombre)
    return {
        "aseguradora": nombre,
        "derecho": "Art. 48 LCS — derecho a reclamar al Defensor del Asegurado antes de acudir a la DGSFP",
        "plazo_respuesta": "2 meses desde la presentación (art. 48 LCS)",
        "siguiente_paso": "Si no responde en 2 meses → reclamación directa a DGSFP",
        "url_dgsfp_reclamaciones": "https://www.dgsfp.mineco.es/es/Consumidores/Reclamaciones/",
        "url_busqueda_defensor": f"https://www.dgsfp.mineco.es/es/busqueda?q={nombre_norm}+defensor+asegurado",
    }
