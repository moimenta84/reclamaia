@extends('layouts.app')
@section('title', 'OCR Documental — Reclama')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <header class="mb-4">
            <a href="{{ route('tools.index') }}" class="small text-muted text-decoration-none">← Herramientas</a>
            <h1 class="fw-bold mt-2 mb-1">OCR Documental</h1>
            <p class="text-muted mb-0">Extrae datos estructurados de pólizas, partes médicos, informes periciales y sentencias.</p>
        </header>

        <div class="card p-4 p-md-5 mb-4">
            <form id="ocr-form" novalidate enctype="multipart/form-data" aria-label="Subir documento para OCR">
                @csrf
                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Tipo de documento</legend>
                    <div class="row g-2" role="group" aria-label="Seleccionar tipo de documento">
                        @foreach([
                            ['poliza','📋','Póliza de seguro','Condiciones generales, particulares, coberturas, exclusiones'],
                            ['parte_medico','🏥','Parte médico','Diagnóstico, lesiones, días de baja, tratamiento'],
                            ['informe_pericial','🔧','Informe pericial','Valoración de daños, causa, cuantía estimada'],
                            ['sentencia','⚖️','Sentencia','Fallo, fundamentos jurídicos, cuantía reconocida'],
                        ] as [$val, $icon, $label, $desc])
                        <div class="col-sm-6">
                            <label class="d-block p-3 rounded border cursor-pointer doc-type-card" style="cursor:pointer">
                                <input type="radio" name="doc_type" value="{{ $val }}" class="d-none doc-type-radio"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <div class="d-flex gap-2 align-items-start">
                                    <span style="font-size:1.3rem" aria-hidden="true">{{ $icon }}</span>
                                    <div>
                                        <div class="fw-semibold small">{{ $label }}</div>
                                        <div class="text-muted" style="font-size:.75rem">{{ $desc }}</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="h6 fw-bold mb-3">Documento PDF</legend>
                    <div class="upload-area p-4 rounded border-2 border-dashed text-center" id="upload-area"
                        style="border:2px dashed #cbd5e1;cursor:pointer" role="region" aria-label="Área de carga de archivo">
                        <span style="font-size:2rem" aria-hidden="true">📄</span>
                        <p class="mb-1 fw-semibold">Arrastra el PDF aquí o haz clic para seleccionar</p>
                        <p class="text-muted small mb-0" id="file-name-display">Máx. 20 MB · Solo PDF</p>
                        <input type="file" id="documento_pdf" name="documento_pdf" accept=".pdf,application/pdf"
                            class="d-none" required aria-required="true" aria-describedby="file-name-display">
                    </div>
                </fieldset>

                <button type="submit" id="ocr-submit" class="btn btn-primary btn-lg w-100">
                    <span id="ocr-submit-text">Extraer datos</span>
                    <span id="ocr-spinner" class="d-none spinner-border spinner-border-sm ms-2" role="status" aria-label="Procesando..."></span>
                </button>
            </form>
        </div>

        <div id="ocr-result" class="card p-4 p-md-5 d-none" role="region" aria-live="polite" aria-label="Datos extraídos">
            <h2 class="h5 fw-bold mb-3">Datos extraídos</h2>
            <div class="mb-3">
                <span class="badge bg-secondary me-2" id="result-method"></span>
                <span class="badge bg-info text-dark" id="result-doctype"></span>
            </div>
            <pre id="result-json" class="p-3 rounded small" style="background:#f8fafc;white-space:pre-wrap;word-break:break-word;max-height:500px;overflow-y:auto"></pre>
            <button class="btn btn-outline-secondary btn-sm mt-3" id="copy-btn" type="button">Copiar JSON</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
.doc-type-card { transition: border-color .15s, background .15s; }
.doc-type-card:has(.doc-type-radio:checked) { border-color: #2563eb !important; background: #eff6ff; }
</style>
<script>
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('documento_pdf');
const fileNameDisplay = document.getElementById('file-name-display');

uploadArea.addEventListener('click', () => fileInput.click());
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.style.background='#f0f9ff'; });
uploadArea.addEventListener('dragleave', () => uploadArea.style.background='');
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.style.background='';
    const file = e.dataTransfer.files[0];
    if (file && file.type === 'application/pdf') {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        fileNameDisplay.textContent = file.name + ' (' + (file.size/1024/1024).toFixed(1) + ' MB)';
    }
});
fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) fileNameDisplay.textContent = fileInput.files[0].name;
});

document.getElementById('ocr-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    if (!fileInput.files[0]) { alert('Selecciona un archivo PDF.'); return; }

    document.getElementById('ocr-submit-text').textContent = 'Procesando…';
    document.getElementById('ocr-spinner').classList.remove('d-none');
    document.getElementById('ocr-submit').disabled = true;

    const fd = new FormData(form);
    const res = await fetch('{{ route('tools.ocr.process') }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: fd,
    });
    const r = await res.json();

    document.getElementById('ocr-submit-text').textContent = 'Extraer datos';
    document.getElementById('ocr-spinner').classList.add('d-none');
    document.getElementById('ocr-submit').disabled = false;

    if (!res.ok) { alert(r.error || 'Error al procesar el documento.'); return; }

    document.getElementById('result-method').textContent = 'Método: ' + (r.extraction_method || '—');
    document.getElementById('result-doctype').textContent = 'Tipo: ' + (r.document_type || '—');
    document.getElementById('result-json').textContent = JSON.stringify(r.data || r, null, 2);
    document.getElementById('ocr-result').classList.remove('d-none');
    document.getElementById('ocr-result').scrollIntoView({behavior:'smooth', block:'start'});
});

document.getElementById('copy-btn')?.addEventListener('click', () => {
    const text = document.getElementById('result-json').textContent;
    navigator.clipboard.writeText(text);
    document.getElementById('copy-btn').textContent = '¡Copiado!';
    setTimeout(() => document.getElementById('copy-btn').textContent = 'Copiar JSON', 2000);
});
</script>
@endpush
