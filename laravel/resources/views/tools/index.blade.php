@extends('layouts.app')
@section('title', 'Herramientas Pro — Reclama')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <header class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3" style="font-size:.8rem">Plan Pro</span>
            <h1 class="fw-bold mb-2">Herramientas profesionales</h1>
            <p class="text-muted">Todas las herramientas que necesita una asesoría de seguros, integradas en un único panel.</p>
        </header>

        <div class="row g-4">
            @php
            $tools = [
                ['icon'=>'⚖️','title'=>'Baremo de tráfico','desc'=>'Calcula indemnización por accidentes de tráfico según la Ley 35/2015.','route'=>'tools.baremo.show','color'=>'#2563eb'],
                ['icon'=>'🖋️','title'=>'Firma digital eIDAS','desc'=>'Firma poderes, autorizaciones y reclamaciones con validez legal completa.','route'=>'dashboard','color'=>'#16a34a','requires_claim'=>true],
                ['icon'=>'📄','title'=>'OCR Documental','desc'=>'Extrae datos de pólizas, partes médicos, informes y sentencias.','route'=>'tools.ocr.show','color'=>'#d97706'],
                ['icon'=>'🚗','title'=>'Valoración de daños','desc'=>'Tasación de daños vehiculares vía DAT, Audatex o GT Estimate.','route'=>'tools.valoracion.show','color'=>'#7c3aed'],
                ['icon'=>'📚','title'=>'Jurisprudencia CENDOJ','desc'=>'Busca sentencias del TS y AP aplicables a tu caso. Citas listas para usar.','route'=>'tools.jurisprudencia.show','color'=>'#dc2626'],
                ['icon'=>'📊','title'=>'Análisis de viabilidad','desc'=>'Evalúa la probabilidad de éxito antes de invertir en la reclamación.','route'=>'viability.show','color'=>'#0891b2'],
            ];
            @endphp

            @foreach($tools as $t)
            @if(empty($t['requires_claim']) || (auth()->user()?->claims()->whereHas('payment')->exists() ?? false))
            <div class="col-md-6 col-lg-4">
                <a href="{{ route($t['route']) }}" class="text-decoration-none">
                    <div class="card p-4 h-100" style="transition:transform .15s">
                        <div style="width:48px;height:48px;border-radius:12px;background:{{ $t['color'] }}15;color:{{ $t['color'] }};display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1rem" aria-hidden="true">{{ $t['icon'] }}</div>
                        <h2 class="h6 fw-bold mb-2">{{ $t['title'] }}</h2>
                        <p class="text-muted small mb-0">{{ $t['desc'] }}</p>
                        <div class="mt-3 small fw-semibold" style="color:{{ $t['color'] }}">Abrir →</div>
                    </div>
                </a>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
