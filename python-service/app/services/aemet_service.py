"""
AEMET (Agencia Estatal de Meteorología) service.
Free API — requires registration at opendata.aemet.es
Used for:
  1. Weather alerts → dashboard widget warning users to check insured assets
  2. Historical weather data → proof of weather event for insurance claims
"""
import os
import httpx
from datetime import datetime, timedelta
from typing import Optional

AEMET_BASE = "https://opendata.aemet.es/opendata/api"
API_KEY = os.getenv("AEMET_API_KEY", "")

# AEMET province codes (codigos de provincia INE)
PROVINCE_CODES: dict[str, str] = {
    "madrid":      "28",
    "barcelona":   "08",
    "valencia":    "46",
    "sevilla":     "41",
    "zaragoza":    "50",
    "malaga":      "29",
    "murcia":      "30",
    "palma":       "07",
    "las palmas":  "35",
    "bilbao":      "48",
    "alicante":    "03",
    "cordoba":     "14",
    "valladolid":  "47",
    "vigo":        "36",
    "gijon":       "33",
    "granada":     "18",
    "elche":       "03",
    "oviedo":      "33",
    "santander":   "39",
    "pamplona":    "31",
}

ALERT_ICONS = {
    "lluvia":    "🌧️",
    "nieve":     "❄️",
    "viento":    "💨",
    "tormenta":  "⛈️",
    "granizo":   "🌨️",
    "niebla":    "🌫️",
    "calor":     "🌡️",
    "frio":      "🥶",
    "costero":   "🌊",
    "aludes":    "⛰️",
}

ALERT_COVERAGE_TIPS = {
    "lluvia":    ["hogar", "auto"],
    "nieve":     ["hogar", "auto", "vida"],
    "viento":    ["hogar", "comercio"],
    "tormenta":  ["hogar", "auto", "comercio"],
    "granizo":   ["hogar", "auto"],
    "niebla":    ["auto"],
    "calor":     ["hogar", "salud"],
    "frio":      ["hogar"],
    "costero":   ["hogar", "viaje"],
    "dana":      ["hogar", "auto", "comercio", "vida"],
    "inundacion":["hogar", "auto", "comercio"],
    "riesgo":    ["hogar", "auto"],
}

ALERT_MESSAGES: dict[str, dict[str, str]] = {
    "dana": {
        "amarillo": "⚠️ DANA activa en la zona. Recuerda a tus clientes que fotografíen cualquier daño y guarden los tickets de reparaciones de urgencia.",
        "naranja":  "🟠 DANA de nivel naranja. Inundaciones probables. Tus clientes con seguro de hogar deben notificar el siniestro ANTES de iniciar limpieza. No tiren nada.",
        "rojo":     "🔴 DANA nivel rojo — EMERGENCIA. Daños graves probables. Abre expedientes preventivos. La aseguradora tiene 7 días para enviar perito (art. 38 LCS).",
    },
    "lluvia": {
        "amarillo": "🌧️ Lluvias intensas previstas. Recuerda a tus clientes revisar canalones y bajantes antes de la tormenta.",
        "naranja":  "🌧️ Lluvia intensa nivel naranja. Inundaciones posibles en zonas bajas. Documentar el estado previo de los inmuebles.",
        "rojo":     "🔴 Lluvia torrencial nivel rojo. Avisa a todos los clientes con póliza de hogar en zonas de riesgo.",
    },
    "tormenta": {
        "amarillo": "⛈️ Tormentas previstas. Protege vehículos descapotables y con cristal solar. El granizo puede no estar cubierto en pólizas básicas.",
        "naranja":  "⛈️ Tormenta severa nivel naranja. Granizo y viento probable. Revisar cobertura de daños eléctricos por rayo en pólizas de hogar.",
        "rojo":     "🔴 Tormenta extrema nivel rojo. Paraliza desplazamientos. Los accidentes en alerta roja pueden tener implicaciones en la cobertura.",
    },
    "granizo": {
        "amarillo": "🌨️ Granizo posible. Los daños en carrocería por granizo cubren muchas pólizas de auto — recuerda a tus clientes documentarlo.",
        "naranja":  "🌨️ Granizo intenso nivel naranja. Los vehículos al descubierto son los más expuestos. Peritación urgente si hay daños.",
        "rojo":     "🔴 Granizo extremo. Daños masivos esperados en vehículos e inmuebles. Prepara gestión de siniestros múltiples.",
    },
    "nieve": {
        "amarillo": "❄️ Nevada prevista. Accidentes de tráfico más probables. Revisar que los clientes tengan cadenas o neumáticos de invierno (puede afectar a la cobertura).",
        "naranja":  "❄️ Nevada intensa nivel naranja. Riesgo de daños en tejados y estructuras. Revisar pólizas de hogar con cobertura de daños por peso de nieve.",
        "rojo":     "🔴 Nevada extrema nivel rojo. Posibles cortes de luz prolongados. Activar cobertura de pérdida de alimentos en pólizas de hogar si aplica.",
    },
    "viento": {
        "amarillo": "💨 Viento fuerte previsto. Asegurar objetos en terrazas y jardines. Los daños a terceros por objetos volantes pueden recaer en RC del hogar.",
        "naranja":  "💨 Viento intenso nivel naranja. Riesgo de caída de árboles y señales. Fotografiar el estado previo de propiedades con elementos vulnerables.",
        "rojo":     "🔴 Viento extremo nivel rojo. No salir salvo urgencia. Los daños en vehículos estacionados en vía pública pueden reclamarse al ayuntamiento.",
    },
    "niebla": {
        "amarillo": "🌫️ Niebla densa prevista. Mayor riesgo de accidentes de tráfico. Recordar a clientes que la niebla no exime de responsabilidad.",
        "naranja":  "🌫️ Niebla intensa nivel naranja. Visibilidad muy reducida. Los accidentes en estas condiciones requieren parte amistoso detallado.",
        "rojo":     "🔴 Niebla extrema nivel rojo. Recomendable no conducir. Informar a clientes sobre cobertura de asistencia en carretera.",
    },
    "calor": {
        "amarillo": "🌡️ Calor intenso previsto. Recordar cobertura de daños eléctricos por sobrecalentamiento en pólizas de hogar y comercio.",
        "naranja":  "🌡️ Ola de calor nivel naranja. Mayor riesgo de incendios forestales en zonas rurales. Verificar coberturas de viviendas en zona de interfaz.",
        "rojo":     "🔴 Calor extremo nivel rojo. Riesgo de incendio muy alto. Revisar urgentemente pólizas de clientes en zonas forestales.",
    },
    "frio": {
        "amarillo": "🥶 Frío intenso previsto. Riesgo de rotura de tuberías. Recordar que muchas pólizas de hogar cubren daños por congelación de instalaciones.",
        "naranja":  "🥶 Frío extremo nivel naranja. Alta probabilidad de daños en instalaciones de agua. Preparar gestión de siniestros por rotura de tuberías.",
        "rojo":     "🔴 Frío extremo nivel rojo. Emergencia por heladas. Activar protocolo de siniestros masivos por daños en instalaciones.",
    },
    "costero": {
        "amarillo": "🌊 Aviso costero activo. Oleaje fuerte. Revisar pólizas de embarcaciones y propiedades en primera línea de costa.",
        "naranja":  "🌊 Aviso costero nivel naranja. Daños en zonas costeras probables. Fotografiar estado previo de propiedades en riesgo.",
        "rojo":     "🔴 Aviso costero nivel rojo. Riesgo de inundación por mar. Documentar todo. Las aseguradoras tienen 72h para responder en catástrofe.",
    },
    "inundacion": {
        "amarillo": "🌊 Riesgo de inundación. Recordar que el Consorcio de Compensación de Seguros cubre daños por inundación extraordinaria.",
        "naranja":  "🌊 Inundación probable nivel naranja. El Consorcio activa automáticamente cobertura. Documentar daños con fotos y vídeo ANTES de limpiar.",
        "rojo":     "🔴 Inundación severa nivel rojo. Catástrofe natural. El Consorcio tiene plazo de 1 año para tramitar. Abrir expedientes ahora.",
    },
}


async def _aemet_get(endpoint: str) -> Optional[dict]:
    """Two-step AEMET request: get data URL, then fetch data."""
    if not API_KEY:
        return None
    headers = {"api_key": API_KEY, "Accept": "application/json"}
    try:
        async with httpx.AsyncClient(timeout=10, verify=False) as client:
            r1 = await client.get(f"{AEMET_BASE}{endpoint}", headers=headers)
            if r1.status_code != 200:
                return None
            meta = r1.json()
            data_url = meta.get("datos")
            if not data_url:
                return None
            r2 = await client.get(data_url, headers={"Accept": "application/json"})
            if r2.status_code == 200:
                return r2.json()
    except Exception:
        pass
    return None


async def get_alertas_nacionales() -> list[dict]:
    """
    Fetch current national weather alerts (CAP format).
    Returns list of active alerts with province, type, severity, and insurance tip.
    """
    if not API_KEY:
        return _mock_alerts()

    data = await _aemet_get("/avisos_cap/ultimoelaborado/nacional/")
    if not data:
        return _mock_alerts()

    alerts = []
    items = data if isinstance(data, list) else [data]
    for item in items:
        tipo = item.get("event", "").lower()
        nivel = item.get("severity", "Minor").lower()
        area = item.get("areaDesc", "España")

        if nivel in ("minor", "unknown"):
            continue  # skip low-level alerts

        icon = next((v for k, v in ALERT_ICONS.items() if k in tipo), "⚠️")
        seguros = next((v for k, v in ALERT_COVERAGE_TIPS.items() if k in tipo), [])

        alerts.append({
            "tipo":     tipo.capitalize(),
            "nivel":    nivel.capitalize(),
            "area":     area,
            "icon":     icon,
            "seguros_afectados": seguros,
            "consejo":  _build_tip(tipo, nivel, seguros),
            "inicio":   item.get("onset", ""),
            "fin":      item.get("expires", ""),
        })

    return alerts or _mock_alerts()


async def get_historial_meteorologico(
    fecha: str,
    provincia: str,
) -> dict:
    """
    Get historical weather data for a given date and province.
    Used to document weather events in insurance claims.
    fecha format: YYYY-MM-DD
    """
    if not API_KEY:
        return _mock_historical(fecha, provincia)

    cod = PROVINCE_CODES.get(provincia.lower(), "28")
    fecha_ini = f"{fecha}T00:00:00UTC"
    fecha_fin = f"{fecha}T23:59:59UTC"

    data = await _aemet_get(
        f"/valores/climatologicos/diarios/datos/fechaini/{fecha_ini}/fechafin/{fecha_fin}/todasestaciones/"
    )

    if not data:
        return _mock_historical(fecha, provincia)

    # Filter stations in the province
    prov_stations = [
        s for s in (data if isinstance(data, list) else [])
        if str(s.get("provincia", "")).lower() == provincia.lower()
        or str(s.get("indicativo", "")).startswith(cod)
    ]

    if not prov_stations:
        prov_stations = (data if isinstance(data, list) else [])[:3]

    station = prov_stations[0] if prov_stations else {}

    return {
        "fecha":        fecha,
        "provincia":    provincia.capitalize(),
        "fuente":       "AEMET — Agencia Estatal de Meteorología (datos oficiales)",
        "estacion":     station.get("nombre", "Estación principal"),
        "temperatura_max": station.get("tmax", "N/D"),
        "temperatura_min": station.get("tmin", "N/D"),
        "precipitacion":   station.get("prec", "0") ,
        "viento_max":      station.get("racha", "N/D"),
        "nieve":           station.get("nieve", "0"),
        "nota_legal": (
            "Datos meteorológicos oficiales de la Agencia Estatal de Meteorología (AEMET), "
            "organismo adscrito al Ministerio para la Transición Ecológica. "
            "Válidos como prueba documental en reclamaciones a aseguradoras."
        ),
    }


def _build_tip(tipo: str, nivel: str, seguros: list) -> str:
    tipo_norm = tipo.lower()
    nivel_norm = nivel.lower()

    # Look for matching alert type (also handles 'dana' inside 'tormenta convectiva' etc.)
    for key, msgs in ALERT_MESSAGES.items():
        if key in tipo_norm or tipo_norm in key:
            msg = msgs.get(nivel_norm) or msgs.get("amarillo", "")
            if msg:
                return msg

    # Generic fallback
    if not seguros:
        return f"Alerta meteorológica activa. Recuerda a tus clientes revisar sus coberturas."
    seguros_str = ", ".join(s.capitalize() for s in seguros)
    return (
        f"Alerta de {tipo} ({nivel}). Clientes con seguros de {seguros_str}: "
        f"documenten cualquier daño con fotos y vídeo antes de limpiar o reparar."
    )


def _mock_alerts() -> list[dict]:
    """Return empty list when no API key — no fake alerts shown."""
    return []


def _mock_historical(fecha: str, provincia: str) -> dict:
    return {
        "fecha":        fecha,
        "provincia":    provincia.capitalize(),
        "fuente":       "AEMET (sin API key configurada — datos de ejemplo)",
        "estacion":     "Estación principal",
        "temperatura_max": "N/D",
        "temperatura_min": "N/D",
        "precipitacion":   "N/D",
        "viento_max":      "N/D",
        "nieve":           "N/D",
        "nota_legal":      "Configura AEMET_API_KEY en .env para datos reales.",
    }
