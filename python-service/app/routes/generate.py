import os
from datetime import datetime, timezone
from typing import Optional

from fastapi import APIRouter, HTTPException, Header, Request
from pydantic import BaseModel, field_validator

from app.services.claude_service import generate_claim_text
from app.services.document_builder import build_documents

router = APIRouter()


class ClaimantData(BaseModel):
    name: str
    dni: str
    email: str
    phone: Optional[str] = None
    address: str


class GenerateRequest(BaseModel):
    claim_id: int
    claim_type: str = "insurance"
    insurer_name: str
    description: str
    claimant: ClaimantData
    policy_number: Optional[str] = None

    @field_validator("description")
    @classmethod
    def description_min_length(cls, v: str) -> str:
        if len(v.strip()) < 50:
            raise ValueError("La descripción debe tener al menos 50 caracteres")
        return v


@router.post("/generate")
async def generate_claim(
    request: GenerateRequest,
    x_internal_key: str = Header(alias="X-Internal-Key"),
):
    expected_key = os.environ.get("INTERNAL_API_SECRET", "")
    if not expected_key or x_internal_key != expected_key:
        raise HTTPException(status_code=403, detail="Forbidden")

    try:
        text = generate_claim_text(request.model_dump())
    except Exception as exc:
        raise HTTPException(
            status_code=503,
            detail={"status": "error", "error": "claude_api_unavailable", "message": str(exc)},
        )

    storage_path = os.environ.get("STORAGE_PATH", "/tmp/reclamaia-storage")
    paths = build_documents(request.claim_id, text, storage_path)

    return {
        "status": "success",
        "word_path": paths["word_path"],
        "pdf_path": paths["pdf_path"],
        "generated_at": datetime.now(timezone.utc).isoformat(),
    }
