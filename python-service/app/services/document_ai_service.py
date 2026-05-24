"""
Google Document AI service — enhanced OCR for scanned PDFs.
Falls back to pypdf automatically when GCP credentials are not configured.
"""
import os
from pathlib import Path

# Lazy imports so the service starts even without GCP installed
_docai = None
_pypdf = None

GOOGLE_PROJECT_ID   = os.environ.get("GOOGLE_CLOUD_PROJECT", "")
GOOGLE_LOCATION     = os.environ.get("GOOGLE_CLOUD_LOCATION", "eu")
DOCAI_PROCESSOR_ID  = os.environ.get("GOOGLE_DOCAI_PROCESSOR_ID", "")


def _is_docai_available() -> bool:
    return bool(GOOGLE_PROJECT_ID and DOCAI_PROCESSOR_ID)


def _extract_with_docai(pdf_path: str) -> str:
    """Use Google Document AI for OCR (handles scanned PDFs)."""
    try:
        from google.cloud import documentai  # noqa: PLC0415
    except ImportError:
        raise RuntimeError("google-cloud-documentai not installed. Run: pip install google-cloud-documentai")

    client = documentai.DocumentProcessorServiceClient()
    name   = client.processor_path(GOOGLE_PROJECT_ID, GOOGLE_LOCATION, DOCAI_PROCESSOR_ID)

    with open(pdf_path, "rb") as f:
        raw_doc = documentai.RawDocument(content=f.read(), mime_type="application/pdf")

    request  = documentai.ProcessRequest(name=name, raw_document=raw_doc)
    result   = client.process_document(request=request)
    document = result.document

    return document.text


def _extract_with_pypdf(pdf_path: str, max_pages: int = 50) -> str:
    """Fallback: plain-text extraction via pypdf."""
    from pypdf import PdfReader  # noqa: PLC0415
    reader = PdfReader(pdf_path)
    pages  = reader.pages[:max_pages]
    return "\n".join(page.extract_text() or "" for page in pages)


def extract_text(pdf_path: str) -> tuple[str, str]:
    """
    Extract text from PDF. Returns (text, method_used).
    method_used is 'docai' or 'pypdf'.
    """
    if _is_docai_available():
        try:
            text = _extract_with_docai(pdf_path)
            if len(text.strip()) > 100:
                return text, "docai"
        except Exception:
            pass  # fall through to pypdf

    return _extract_with_pypdf(pdf_path), "pypdf"


def extract_structured_data(pdf_path: str, doc_type: str = "poliza") -> dict:
    """
    Extract structured data from a document using Document AI form parser.
    doc_type: 'poliza' | 'parte_medico' | 'informe' | 'sentencia'
    """
    text, method = extract_text(pdf_path)

    # Build structured extraction prompt based on doc type
    schemas = {
        "poliza": {
            "campos": ["numero_poliza", "tomador", "asegurado", "aseguradora",
                       "tipo_seguro", "vigencia", "capital_asegurado",
                       "coberturas", "exclusiones", "franquicia"],
        },
        "parte_medico": {
            "campos": ["fecha", "medico", "paciente", "diagnostico", "tratamiento",
                       "dias_baja", "secuelas", "prognosis"],
        },
        "informe_pericial": {
            "campos": ["fecha", "perito", "bien_asegurado", "danos_descripcion",
                       "valoracion_euros", "causa", "conclusion"],
        },
        "sentencia": {
            "campos": ["tribunal", "numero", "fecha", "partes", "fallo",
                       "indemnizacion", "costas", "fundamentos_juridicos"],
        },
    }

    schema = schemas.get(doc_type, schemas["poliza"])

    return {
        "texto_extraido": text[:8000],
        "metodo_ocr": method,
        "tipo_documento": doc_type,
        "campos_solicitados": schema["campos"],
        "docai_disponible": _is_docai_available(),
    }
