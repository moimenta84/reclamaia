import os
from datetime import datetime, timezone
from typing import Optional

from fastapi import APIRouter, HTTPException, Header
from pydantic import BaseModel

from app.services.escalation_service import generate_escalation_letter
from app.services.document_builder import build_documents

router = APIRouter()


class EscalationRequest(BaseModel):
    claim_id: int
    insurer_name: str
    description: str
    policy_number: Optional[str] = None
    sent_at: str
    days_elapsed: int
    claimant: dict


@router.post("/escalate")
async def escalate_claim(
    request: EscalationRequest,
    x_internal_key: str = Header(alias="X-Internal-Key"),
):
    expected_key = os.environ.get("INTERNAL_API_SECRET", "")
    if not expected_key or x_internal_key != expected_key:
        raise HTTPException(status_code=403, detail="Forbidden")

    try:
        text = generate_escalation_letter(request.model_dump())
    except Exception as exc:
        raise HTTPException(status_code=503, detail=str(exc))

    storage_path = os.environ.get("STORAGE_PATH", "/tmp/reclamaia-storage")
    claim_id = request.claim_id * 10000 + 1  # offset to avoid name collision
    paths = build_documents(claim_id, text, storage_path)

    return {
        "status": "success",
        "word_path": paths["word_path"],
        "pdf_path": paths["pdf_path"],
        "generated_at": datetime.now(timezone.utc).isoformat(),
    }
