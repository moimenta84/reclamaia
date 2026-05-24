"""Tool endpoints: damage valuation, document OCR, CENDOJ jurisprudence."""
import os
from fastapi import APIRouter, HTTPException, Header, UploadFile, File
from pydantic import BaseModel
import tempfile

from app.services.damage_valuation_service import get_damage_valuation, DamageValuationError
from app.services.document_ai_service import extract_structured_data, extract_text
from app.services.cendoj_service import get_relevant_jurisprudence

router = APIRouter()


def _auth(x_internal_key: str | None):
    if x_internal_key != os.environ.get("INTERNAL_API_SECRET", ""):
        raise HTTPException(status_code=401, detail="Unauthorized")


# ── Damage Valuation ─────────────────────────────────────────────────────────
class VehicleData(BaseModel):
    vin:      str = ""
    plate:    str = ""
    make:     str = ""
    model:    str = ""
    year:     int = 2020
    mileage:  int = 50000

class DamageData(BaseModel):
    description: str
    parts:       list[dict] = []

class ValuationRequest(BaseModel):
    vehicle: VehicleData
    damage:  DamageData

@router.post("/valuacion-danos")
def valuation(req: ValuationRequest, x_internal_key: str = Header(None)):
    _auth(x_internal_key)
    try:
        result = get_damage_valuation(req.vehicle.model_dump(), req.damage.model_dump())
        return {"status": "success", "valuation": result}
    except DamageValuationError as e:
        raise HTTPException(status_code=503, detail=str(e))


# ── Document OCR ─────────────────────────────────────────────────────────────
@router.post("/ocr-documento")
async def ocr_document(
    doc_type: str = "poliza",
    file: UploadFile = File(...),
    x_internal_key: str = Header(None),
):
    _auth(x_internal_key)
    if not file.filename.lower().endswith(".pdf"):
        raise HTTPException(status_code=400, detail="Solo se aceptan archivos PDF")

    content = await file.read()
    if len(content) > 20 * 1024 * 1024:  # 20 MB
        raise HTTPException(status_code=400, detail="El archivo no puede superar 20 MB")

    with tempfile.NamedTemporaryFile(suffix=".pdf", delete=False) as tmp:
        tmp.write(content)
        tmp_path = tmp.name

    result = extract_structured_data(tmp_path, doc_type)
    os.unlink(tmp_path)
    return {"status": "success", "document": result}


# ── CENDOJ Jurisprudence ──────────────────────────────────────────────────────
class JurisprudenceRequest(BaseModel):
    claim_description: str
    insurer_name:      str = ""
    claim_type:        str = "seguros"

@router.post("/jurisprudencia")
def jurisprudencia(req: JurisprudenceRequest, x_internal_key: str = Header(None)):
    _auth(x_internal_key)
    result = get_relevant_jurisprudence(
        req.claim_description, req.insurer_name, req.claim_type
    )
    return {"status": "success", "jurisprudencia": result}
