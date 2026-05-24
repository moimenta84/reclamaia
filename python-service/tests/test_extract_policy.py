import os
import io
import pytest
from unittest.mock import patch
from fastapi.testclient import TestClient

os.environ.setdefault("ANTHROPIC_API_KEY", "test-key")
os.environ.setdefault("INTERNAL_API_SECRET", "test-secret")

from app.main import app

client = TestClient(app)

MOCK_CLAUSES = {
    "clausulas_clave": [
        {"titulo": "Cobertura de daños por agua", "articulo": "12", "texto": "Se cubren daños por rotura de tuberías..."},
        {"titulo": "Franquicia", "articulo": "15", "texto": "Franquicia de 300 EUR por siniestro."},
    ],
    "limite_indemnizacion": "60.000 EUR",
    "exclusiones": ["Daños por humedad ambiental", "Daños estéticos sin causa accidental"],
}

PDF_HEADER = b"%PDF-1.4 fake pdf content for testing purposes only"


# ─── Auth ────────────────────────────────────────────────────────

def test_extract_policy_missing_auth():
    response = client.post(
        "/api/extract-policy",
        files={"file": ("test.pdf", io.BytesIO(PDF_HEADER), "application/pdf")},
        data={"claim_description": "Daños por agua en vivienda."},
    )
    assert response.status_code == 422


def test_extract_policy_wrong_key():
    response = client.post(
        "/api/extract-policy",
        files={"file": ("test.pdf", io.BytesIO(PDF_HEADER), "application/pdf")},
        data={"claim_description": "Daños por agua en vivienda."},
        headers={"X-Internal-Key": "wrong"},
    )
    assert response.status_code == 403


# ─── Validation ──────────────────────────────────────────────────

def test_extract_policy_rejects_non_pdf():
    response = client.post(
        "/api/extract-policy",
        files={"file": ("document.docx", io.BytesIO(b"not a pdf"), "application/octet-stream")},
        data={"claim_description": "Daños por agua."},
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422
    assert "PDF" in response.json()["detail"]


def test_extract_policy_rejects_oversized_file():
    big_content = b"%PDF-1.4 " + b"x" * (10 * 1024 * 1024 + 1)
    response = client.post(
        "/api/extract-policy",
        files={"file": ("big.pdf", io.BytesIO(big_content), "application/pdf")},
        data={"claim_description": "Descripción del siniestro."},
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422
    assert "10 MB" in response.json()["detail"]


# ─── Success ─────────────────────────────────────────────────────

@patch("app.routes.extract_policy.extract_policy_clauses", return_value=MOCK_CLAUSES)
def test_extract_policy_success(mock_extractor):
    response = client.post(
        "/api/extract-policy",
        files={"file": ("poliza.pdf", io.BytesIO(PDF_HEADER), "application/pdf")},
        data={"claim_description": "Daños por agua en el piso de abajo."},
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["status"] == "success"
    assert "clauses" in data
    assert len(data["clauses"]["clausulas_clave"]) == 2
    mock_extractor.assert_called_once()


@patch("app.routes.extract_policy.extract_policy_clauses", side_effect=Exception("OCR failed"))
def test_extract_policy_returns_503_on_error(mock_extractor):
    response = client.post(
        "/api/extract-policy",
        files={"file": ("poliza.pdf", io.BytesIO(PDF_HEADER), "application/pdf")},
        data={"claim_description": "Daños por agua."},
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 503
