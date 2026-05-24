import os
import pytest
from unittest.mock import patch, MagicMock

os.environ.setdefault("ANTHROPIC_API_KEY", "test-key")

from app.services.claude_service import generate_claim_text


def _mock_message(text: str) -> MagicMock:
    msg = MagicMock()
    msg.content = [MagicMock(text=text)]
    return msg


BASE_CLAIM = {
    "claim_id": 1,
    "claim_type": "hogar",
    "insurer_name": "MAPFRE Seguros",
    "description": "Daños graves por agua en el baño de mi vivienda.",
    "policy_number": "POL-2024-MAPFRE",
    "claimant": {
        "name": "Juan García",
        "dni": "12345678A",
        "email": "juan@test.com",
        "phone": "600123456",
        "address": "Calle Mayor 1, Madrid",
    },
}

EXPECTED_LETTER = "Estimada MAPFRE Seguros, en virtud del artículo 18 LCS... [carta completa]"


# ─── Basic generation ─────────────────────────────────────────────

@patch("app.services.claude_service._get_client")
def test_generate_returns_string(mock_get_client):
    mock_get_client.return_value.messages.create.return_value = _mock_message(EXPECTED_LETTER)

    result = generate_claim_text(BASE_CLAIM)

    assert isinstance(result, str)
    assert len(result) > 0


@patch("app.services.claude_service._get_client")
def test_generate_calls_claude_with_correct_model(mock_get_client):
    mock_client = MagicMock()
    mock_client.messages.create.return_value = _mock_message(EXPECTED_LETTER)
    mock_get_client.return_value = mock_client

    generate_claim_text(BASE_CLAIM)

    call_kwargs = mock_client.messages.create.call_args[1]
    assert call_kwargs["model"] == "claude-sonnet-4-6"
    assert call_kwargs["max_tokens"] == 2048


@patch("app.services.claude_service._get_client")
def test_generate_includes_insurer_in_prompt(mock_get_client):
    mock_client = MagicMock()
    mock_client.messages.create.return_value = _mock_message(EXPECTED_LETTER)
    mock_get_client.return_value = mock_client

    generate_claim_text(BASE_CLAIM)

    user_prompt = mock_client.messages.create.call_args[1]["messages"][0]["content"]
    assert "MAPFRE Seguros" in user_prompt


@patch("app.services.claude_service._get_client")
def test_generate_includes_claimant_name_in_prompt(mock_get_client):
    mock_client = MagicMock()
    mock_client.messages.create.return_value = _mock_message(EXPECTED_LETTER)
    mock_get_client.return_value = mock_client

    generate_claim_text(BASE_CLAIM)

    user_prompt = mock_client.messages.create.call_args[1]["messages"][0]["content"]
    assert "Juan García" in user_prompt
    assert "12345678A" in user_prompt


# ─── Policy clauses enrichment ───────────────────────────────────

@patch("app.services.claude_service._get_client")
def test_generate_includes_policy_clauses_when_present(mock_get_client):
    mock_client = MagicMock()
    mock_client.messages.create.return_value = _mock_message(EXPECTED_LETTER)
    mock_get_client.return_value = mock_client

    claim_with_clauses = {
        **BASE_CLAIM,
        "policy_clauses": {
            "clausulas_clave": [
                {"titulo": "Cobertura agua", "articulo": "12", "texto": "Se cubren daños por rotura."},
            ],
            "limite_indemnizacion": "60.000 EUR",
        },
    }

    generate_claim_text(claim_with_clauses)

    user_prompt = mock_client.messages.create.call_args[1]["messages"][0]["content"]
    assert "CLÁUSULAS DE LA PÓLIZA" in user_prompt
    assert "Cobertura agua" in user_prompt


@patch("app.services.claude_service._get_client")
def test_generate_skips_clauses_section_when_absent(mock_get_client):
    mock_client = MagicMock()
    mock_client.messages.create.return_value = _mock_message(EXPECTED_LETTER)
    mock_get_client.return_value = mock_client

    generate_claim_text(BASE_CLAIM)

    user_prompt = mock_client.messages.create.call_args[1]["messages"][0]["content"]
    assert "CLÁUSULAS DE LA PÓLIZA" not in user_prompt


# ─── Viability enrichment ────────────────────────────────────────

@patch("app.services.claude_service._get_client")
def test_generate_includes_viability_base_legal(mock_get_client):
    mock_client = MagicMock()
    mock_client.messages.create.return_value = _mock_message(EXPECTED_LETTER)
    mock_get_client.return_value = mock_client

    claim_with_viability = {
        **BASE_CLAIM,
        "viability_analysis": {
            "base_legal": "Art. 18 LCS — plazo incumplido",
            "recomendacion": "Citar fecha exacta del siniestro.",
        },
    }

    generate_claim_text(claim_with_viability)

    user_prompt = mock_client.messages.create.call_args[1]["messages"][0]["content"]
    assert "BASE LEGAL IDENTIFICADA" in user_prompt
    assert "Art. 18 LCS" in user_prompt


# ─── Error propagation ───────────────────────────────────────────

@patch("app.services.claude_service._get_client")
def test_generate_propagates_api_exception(mock_get_client):
    mock_get_client.return_value.messages.create.side_effect = Exception("API timeout")

    with pytest.raises(Exception, match="API timeout"):
        generate_claim_text(BASE_CLAIM)
