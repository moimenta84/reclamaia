import os
from typing import Optional

from fastapi import APIRouter, HTTPException, Header
from pydantic import BaseModel, field_validator

from app.services.viability_service import analyze_viability

router = APIRouter()


class AnalyzeRequest(BaseModel):
    claim_type: str = "insurance"
    insurer_name: str
    description: str
    policy_number: Optional[str] = None

    @field_validator("description")
    @classmethod
    def description_min_length(cls, v: str) -> str:
        if len(v.strip()) < 30:
            raise ValueError("La descripción debe tener al menos 30 caracteres")
        return v


@router.post("/analyze")
async def analyze_claim(
    request: AnalyzeRequest,
    x_internal_key: str = Header(alias="X-Internal-Key"),
):
    expected_key = os.environ.get("INTERNAL_API_SECRET", "")
    if not expected_key or x_internal_key != expected_key:
        raise HTTPException(status_code=403, detail="Forbidden")

    try:
        result = analyze_viability(request.model_dump())
        return {"status": "success", "analysis": result}
    except Exception as exc:
        raise HTTPException(
            status_code=503,
            detail={"status": "error", "message": str(exc)},
        )
