import os
import tempfile
from pathlib import Path

from fastapi import APIRouter, HTTPException, Header, UploadFile, File, Form

from app.services.policy_extractor import extract_policy_clauses

router = APIRouter()

MAX_FILE_SIZE = 10 * 1024 * 1024  # 10 MB


@router.post("/extract-policy")
async def extract_policy(
    file: UploadFile = File(...),
    claim_description: str = Form(...),
    x_internal_key: str = Header(alias="X-Internal-Key"),
):
    expected_key = os.environ.get("INTERNAL_API_SECRET", "")
    if not expected_key or x_internal_key != expected_key:
        raise HTTPException(status_code=403, detail="Forbidden")

    if not file.filename or not file.filename.lower().endswith(".pdf"):
        raise HTTPException(status_code=422, detail="Solo se aceptan archivos PDF")

    content = await file.read()
    if len(content) > MAX_FILE_SIZE:
        raise HTTPException(status_code=422, detail="El archivo PDF no puede superar 10 MB")

    with tempfile.NamedTemporaryFile(suffix=".pdf", delete=False) as tmp:
        tmp.write(content)
        tmp_path = tmp.name

    try:
        clauses = extract_policy_clauses(tmp_path, claim_description)
        return {"status": "success", "clauses": clauses}
    except Exception as exc:
        raise HTTPException(
            status_code=503,
            detail={"status": "error", "message": str(exc)},
        )
    finally:
        Path(tmp_path).unlink(missing_ok=True)
