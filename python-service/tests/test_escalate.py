import os
import pytest
from unittest.mock import patch
from fastapi.testclient import TestClient

os.environ.setdefault("ANTHROPIC_API_KEY", "test-key")
os.environ.setdefault("INTERNAL_API_SECRET", "test-secret")
os.environ.setdefault("STORAGE_PATH", "/tmp/reclamaia-test")

from app.main import app

client = TestClient(app)

VALID_PAYLOAD = {
    "claim_id": 42,
    "insurer_name": "AXA Seguros",
    "description": "Siniestro de agua en vivienda. Sin respuesta tras 35 días.",
    "policy_number": "POL-AXA-99999",
    "sent_at": "2026-04-15T10:00:00Z",
    "days_elapsed": 35,
    "claimant": {
        "name": "María López Sánchez",
        "dni": "87654321B",
        "email": "maria@test.com",
        "address": "Paseo de la Castellana 200, Madrid",
    },
}


# ─── Auth ────────────────────────────────────────────────────────

def test_escalate_missing_auth():
    response = client.post("/api/escalate", json=VALID_PAYLOAD)
    assert response.status_code == 422


def test_escalate_wrong_key():
    response = client.post(
        "/api/escalate",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "bad-key"},
    )
    assert response.status_code == 403


# ─── Validation ──────────────────────────────────────────────────

def test_escalate_missing_sent_at():
    payload = {k: v for k, v in VALID_PAYLOAD.items() if k != "sent_at"}
    response = client.post(
        "/api/escalate",
        json=payload,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422


def test_escalate_missing_claimant():
    payload = {k: v for k, v in VALID_PAYLOAD.items() if k != "claimant"}
    response = client.post(
        "/api/escalate",
        json=payload,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422


# ─── Success ─────────────────────────────────────────────────────

@patch("app.routes.escalate.generate_escalation_letter", return_value="# Escalada DGSFP\n\nTexto formal de escalada. " * 10)
@patch("app.routes.escalate.build_documents", return_value={
    "word_path": "documents/420001/reclamacion_420001.docx",
    "pdf_path": "documents/420001/reclamacion_420001.pdf",
})
def test_escalate_success(mock_build, mock_letter):
    response = client.post(
        "/api/escalate",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["status"] == "success"
    assert "word_path" in data
    assert "pdf_path" in data
    assert "generated_at" in data
    mock_letter.assert_called_once()
    mock_build.assert_called_once()


@patch("app.routes.escalate.generate_escalation_letter", return_value="# Escalada\n\n" + "Texto. " * 20)
@patch("app.routes.escalate.build_documents", return_value={
    "word_path": "documents/420001/reclamacion_420001.docx",
    "pdf_path": "documents/420001/reclamacion_420001.pdf",
})
def test_escalate_uses_offset_claim_id(mock_build, mock_letter):
    """Escalation uses claim_id * 10000 + 1 to avoid file collisions."""
    client.post(
        "/api/escalate",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "test-secret"},
    )
    called_claim_id = mock_build.call_args[0][0]
    assert called_claim_id == 42 * 10000 + 1


@patch("app.routes.escalate.generate_escalation_letter", side_effect=Exception("API timeout"))
def test_escalate_returns_503_on_error(mock_letter):
    response = client.post(
        "/api/escalate",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 503
