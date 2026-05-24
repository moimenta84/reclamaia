import os
import json
import pytest
from unittest.mock import patch, MagicMock
from fastapi.testclient import TestClient

os.environ.setdefault("ANTHROPIC_API_KEY", "test-key")
os.environ.setdefault("INTERNAL_API_SECRET", "test-secret")

from app.main import app

client = TestClient(app)

VALID_PAYLOAD = {
    "claim_type": "hogar",
    "insurer_name": "MAPFRE Hogar",
    "description": "Daños por agua en mi vivienda — la aseguradora lleva 45 días sin responder.",
    "policy_number": "POL-2024-001",
}

VIABILITY_MOCK = {
    "score": "alta",
    "probabilidad_estimada": 82,
    "resumen": "Caso sólido: hay incumplimiento del plazo legal de 40 días (art. 18 LCS).",
    "puntos_fuertes": ["Plazo LCS incumplido", "Daños documentados"],
    "puntos_debiles": ["No se ha enviado burofax previo"],
    "base_legal": "Art. 18 Ley 50/1980 LCS",
    "recomendacion": "Incluir fecha exacta de denuncia del siniestro y referencia al expediente.",
    "fuera_de_ambito": False,
}


# ─── Auth ────────────────────────────────────────────────────────

def test_analyze_missing_auth_header():
    response = client.post("/api/analyze", json=VALID_PAYLOAD)
    assert response.status_code == 422  # pydantic header validation


def test_analyze_wrong_key():
    response = client.post(
        "/api/analyze",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "wrong"},
    )
    assert response.status_code == 403


# ─── Validation ──────────────────────────────────────────────────

def test_analyze_description_too_short():
    payload = {**VALID_PAYLOAD, "description": "corto"}
    response = client.post(
        "/api/analyze",
        json=payload,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422


def test_analyze_missing_insurer():
    payload = {k: v for k, v in VALID_PAYLOAD.items() if k != "insurer_name"}
    response = client.post(
        "/api/analyze",
        json=payload,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 422


# ─── Success ─────────────────────────────────────────────────────

@patch("app.routes.analyze.analyze_viability", return_value=VIABILITY_MOCK)
def test_analyze_success(mock_service):
    response = client.post(
        "/api/analyze",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["status"] == "success"
    assert "analysis" in data
    assert data["analysis"]["score"] == "alta"
    assert data["analysis"]["probabilidad_estimada"] == 82
    mock_service.assert_called_once()


@patch("app.routes.analyze.analyze_viability", return_value=VIABILITY_MOCK)
def test_analyze_passes_claim_data_to_service(mock_service):
    client.post(
        "/api/analyze",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "test-secret"},
    )
    call_args = mock_service.call_args[0][0]
    assert call_args["insurer_name"] == "MAPFRE Hogar"
    assert call_args["claim_type"] == "hogar"


@patch("app.routes.analyze.analyze_viability", side_effect=Exception("Claude unavailable"))
def test_analyze_returns_503_on_service_error(mock_service):
    response = client.post(
        "/api/analyze",
        json=VALID_PAYLOAD,
        headers={"X-Internal-Key": "test-secret"},
    )
    assert response.status_code == 503
