import os
import pytest
from unittest.mock import patch, MagicMock
from fastapi.testclient import TestClient

os.environ.setdefault("ANTHROPIC_API_KEY", "test-key")
os.environ.setdefault("INTERNAL_API_SECRET", "test-secret")
os.environ.setdefault("STORAGE_PATH", "/tmp/reclamaia-test")

from app.main import app

client = TestClient(app)


def test_health():
    response = client.get("/health")
    assert response.status_code == 200
    data = response.json()
    assert data["status"] == "ok"
    assert data["model"] == "claude-sonnet-4-6"


def test_generate_missing_auth():
    response = client.post("/api/generate", json={}, headers={})
    assert response.status_code == 422  # Missing header


def test_generate_wrong_key():
    payload = {
        "claim_id": 1,
        "insurer_name": "Mapfre",
        "description": "A" * 50,
        "claimant": {
            "name": "Juan García",
            "dni": "12345678A",
            "email": "juan@test.com",
            "address": "Calle Mayor 1, Madrid",
        },
    }
    response = client.post(
        "/api/generate",
        json=payload,
        headers={"X-Internal-Key": "wrong-key"},
    )
    assert response.status_code == 403


def test_generate_description_too_short():
    payload = {
        "claim_id": 1,
        "insurer_name": "Mapfre",
        "description": "corto",
        "claimant": {
            "name": "Juan García",
            "dni": "12345678A",
            "email": "juan@test.com",
            "address": "Calle Mayor 1, Madrid",
        },
    }
    response = client.post(
        "/api/generate",
        json=payload,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422


@patch("app.routes.generate.generate_claim_text", return_value="# Reclamación\n\nTexto de prueba " * 10)
@patch("app.routes.generate.build_documents", return_value={
    "word_path": "documents/1/reclamacion_1.docx",
    "pdf_path": "documents/1/reclamacion_1.pdf",
})
def test_generate_success(mock_build, mock_claude):
    payload = {
        "claim_id": 1,
        "insurer_name": "Mapfre Seguros",
        "description": "Mi seguro de hogar no cubre los daños por agua que sufrí en mi vivienda el pasado mes.",
        "claimant": {
            "name": "Juan García López",
            "dni": "12345678A",
            "email": "juan@test.com",
            "address": "Calle Mayor 1, 28001 Madrid",
        },
        "policy_number": "POL-2024-001",
    }
    response = client.post(
        "/api/generate",
        json=payload,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["status"] == "success"
    assert "word_path" in data
    assert "pdf_path" in data
    assert "generated_at" in data
