import os
import tempfile
from pathlib import Path

import pytest

from app.services.document_builder import build_documents, _clean_markdown

SAMPLE_TEXT = """# RECLAMACIÓN FORMAL DE SEGURO

## DATOS DEL RECLAMANTE

**María García López**, con DNI 12345678A.

## FUNDAMENTOS DE DERECHO

Artículo 18 de la Ley 50/1980, de Contrato de Seguro (LCS).
La aseguradora ha incumplido el plazo máximo de 40 días hábiles.

### SENTENCIA APLICABLE

STS 234/2023 — incumplimiento del deber de indemnizar.

Solicitamos indemnización de 3.240 euros.
"""


# ─── _clean_markdown ─────────────────────────────────────────────

def test_clean_markdown_removes_bold():
    assert _clean_markdown("**texto importante**") == "texto importante"


def test_clean_markdown_removes_italic():
    assert _clean_markdown("*texto cursiva*") == "texto cursiva"


def test_clean_markdown_removes_headings():
    assert _clean_markdown("# Título principal") == "Título principal"
    assert _clean_markdown("## Subtítulo") == "Subtítulo"
    assert _clean_markdown("### Sub-subtítulo") == "Sub-subtítulo"


def test_clean_markdown_preserves_plain_text():
    text = "Texto sin formato alguno."
    assert _clean_markdown(text) == text


def test_clean_markdown_handles_combined():
    result = _clean_markdown("## **Sección importante**")
    assert result == "Sección importante"


# ─── build_documents ─────────────────────────────────────────────

def test_build_documents_creates_both_files():
    with tempfile.TemporaryDirectory() as tmpdir:
        paths = build_documents(claim_id=999, text=SAMPLE_TEXT, output_dir=tmpdir)

        word_file = Path(tmpdir) / paths["word_path"]
        pdf_file  = Path(tmpdir) / paths["pdf_path"]

        assert word_file.exists(), "Word file not created"
        assert pdf_file.exists(),  "PDF file not created"
        assert word_file.stat().st_size > 0
        assert pdf_file.stat().st_size > 0


def test_build_documents_returns_correct_relative_paths():
    with tempfile.TemporaryDirectory() as tmpdir:
        paths = build_documents(claim_id=123, text=SAMPLE_TEXT, output_dir=tmpdir)

    assert paths["word_path"] == "documents/123/reclamacion_123.docx"
    assert paths["pdf_path"]  == "documents/123/reclamacion_123.pdf"


def test_build_documents_creates_output_directory():
    with tempfile.TemporaryDirectory() as tmpdir:
        subdir = os.path.join(tmpdir, "nested", "storage")
        build_documents(claim_id=1, text=SAMPLE_TEXT, output_dir=subdir)

        assert Path(subdir, "documents", "1").is_dir()


def test_build_documents_handles_minimal_text():
    with tempfile.TemporaryDirectory() as tmpdir:
        paths = build_documents(
            claim_id=2,
            text="Reclamación simple sin formato.",
            output_dir=tmpdir,
        )
        assert Path(tmpdir, paths["word_path"]).exists()
        assert Path(tmpdir, paths["pdf_path"]).exists()


def test_build_documents_handles_special_chars_in_pdf():
    text_with_specials = "Daños valorados en 3.240 € — Siniestro: <incendio> & robo"
    with tempfile.TemporaryDirectory() as tmpdir:
        paths = build_documents(claim_id=3, text=text_with_specials, output_dir=tmpdir)
        assert Path(tmpdir, paths["pdf_path"]).stat().st_size > 0


def test_build_documents_different_ids_dont_collide():
    with tempfile.TemporaryDirectory() as tmpdir:
        paths_a = build_documents(claim_id=10, text="Reclamación A " * 5, output_dir=tmpdir)
        paths_b = build_documents(claim_id=11, text="Reclamación B " * 5, output_dir=tmpdir)

        assert paths_a["word_path"] != paths_b["word_path"]
        assert paths_a["pdf_path"]  != paths_b["pdf_path"]
