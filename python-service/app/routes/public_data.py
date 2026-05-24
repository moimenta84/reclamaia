"""
Routes for public data APIs: BOE, DGSFP, AEMET.
All endpoints require the internal API secret header.
"""
import os
from fastapi import APIRouter, HTTPException, Header
from pydantic import BaseModel
from typing import Optional

from app.services.boe_service import (
    get_norma_metadata,
    search_boe,
    get_normativa_para_reclamacion,
)
from app.services.dgsfp_service import (
    buscar_aseguradora,
    get_sanciones,
    get_defensor_info,
)
from app.services.aemet_service import (
    get_alertas_nacionales,
    get_historial_meteorologico,
)

router = APIRouter()
INTERNAL_SECRET = os.getenv("INTERNAL_API_SECRET", "")


def _auth(x_internal_key: str):
    if INTERNAL_SECRET and x_internal_key != INTERNAL_SECRET:
        raise HTTPException(status_code=401, detail="Unauthorized")


# ─── BOE ──────────────────────────────────────────────────────────────────────

class BoeSearchRequest(BaseModel):
    query: str
    date_from: Optional[str] = None

class NormativaRequest(BaseModel):
    tipo_seguro: str


@router.get("/boe/norma/{norma_key}")
async def boe_norma(norma_key: str, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return await get_norma_metadata(norma_key)


@router.post("/boe/search")
async def boe_search(req: BoeSearchRequest, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return {"results": await search_boe(req.query, req.date_from)}


@router.post("/boe/normativa-reclamacion")
async def boe_normativa_reclamacion(req: NormativaRequest, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return await get_normativa_para_reclamacion(req.tipo_seguro)


# ─── DGSFP ────────────────────────────────────────────────────────────────────

class AseguradoraRequest(BaseModel):
    nombre: str


@router.post("/dgsfp/aseguradora")
async def dgsfp_aseguradora(req: AseguradoraRequest, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return await buscar_aseguradora(req.nombre)


@router.post("/dgsfp/sanciones")
async def dgsfp_sanciones(req: AseguradoraRequest, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return await get_sanciones(req.nombre)


@router.post("/dgsfp/defensor")
async def dgsfp_defensor(req: AseguradoraRequest, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return await get_defensor_info(req.nombre)


# ─── AEMET ────────────────────────────────────────────────────────────────────

class AemetHistorialRequest(BaseModel):
    fecha: str      # YYYY-MM-DD
    provincia: str


@router.get("/aemet/alertas")
async def aemet_alertas(x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return {"alertas": await get_alertas_nacionales()}


@router.post("/aemet/historial")
async def aemet_historial(req: AemetHistorialRequest, x_internal_key: str = Header("")):
    _auth(x_internal_key)
    return await get_historial_meteorologico(req.fecha, req.provincia)
