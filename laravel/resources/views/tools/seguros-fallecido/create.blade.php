@extends('layouts.dashboard')
@section('title', 'Nuevo Expediente RCSCF — Reclama')
@section('page-title', 'Nuevo expediente — Seguros del Fallecido')

@section('content')

<div class="row justify-content-center fade-up">
  <div class="col-lg-8">

    {{-- Info RCSCF --}}
    <div style="background:var(--b-50);border:1px solid var(--b-200);border-radius:var(--r-lg);padding:var(--sp-5);margin-bottom:var(--sp-6)">
      <h2 style="font-size:var(--t-base);font-weight:var(--fw-bold);color:var(--b-800);margin:0 0 var(--sp-2)">
        ℹ️ ¿Qué es el RCSCF?
      </h2>
      <p style="font-size:var(--t-sm);color:var(--b-700);margin:0">
        El <strong>Registro de Contratos de Seguros de Cobertura de Fallecimiento</strong> del
        Ministerio de Justicia recoge todos los seguros de vida y decesos contratados en España.
        Permite comprobar si un fallecido tenía seguro y con qué compañía.
        El certificado oficial cuesta <strong>3,70 €</strong> y tarda <strong>10-15 días hábiles</strong>.
      </p>
    </div>

    <div class="section-card">
      <div class="section-card-header">
        <h2>Datos del expediente</h2>
      </div>
      <div style="padding:var(--sp-6)">
        <form method="POST" action="{{ route('tools.fallecido.store') }}" novalidate>
          @csrf

          {{-- Fallecido --}}
          <h3 style="font-size:var(--t-sm);font-weight:var(--fw-bold);text-transform:uppercase;letter-spacing:.07em;color:var(--text-3);margin:0 0 var(--sp-4)">
            Datos del fallecido
          </h3>

          <div class="row g-4 mb-4">
            <div class="col-md-8">
              <label class="form-label" for="deceased_name">
                Nombre completo <span class="required" aria-hidden="true">*</span>
              </label>
              <input type="text" id="deceased_name" name="deceased_name"
                     value="{{ old('deceased_name') }}" required
                     class="form-control @error('deceased_name') is-invalid @enderror"
                     placeholder="Nombre y apellidos del fallecido">
              @error('deceased_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label" for="deceased_dni">
                DNI / NIE <span class="required" aria-hidden="true">*</span>
              </label>
              <input type="text" id="deceased_dni" name="deceased_dni"
                     value="{{ old('deceased_dni') }}" required
                     class="form-control @error('deceased_dni') is-invalid @enderror"
                     placeholder="12345678A">
              @error('deceased_dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row g-4 mb-4">
            <div class="col-md-4">
              <label class="form-label" for="deceased_birth_date">Fecha de nacimiento</label>
              <input type="date" id="deceased_birth_date" name="deceased_birth_date"
                     value="{{ old('deceased_birth_date') }}"
                     class="form-control @error('deceased_birth_date') is-invalid @enderror">
              @error('deceased_birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label" for="deceased_death_date">
                Fecha de fallecimiento <span class="required" aria-hidden="true">*</span>
              </label>
              <input type="date" id="deceased_death_date" name="deceased_death_date"
                     value="{{ old('deceased_death_date') }}" required
                     class="form-control @error('deceased_death_date') is-invalid @enderror">
              @error('deceased_death_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label" for="deceased_province">Provincia de residencia</label>
              <select id="deceased_province" name="deceased_province"
                      class="form-control @error('deceased_province') is-invalid @enderror">
                <option value="">— Selecciona —</option>
                @foreach(['Álava','Albacete','Alicante','Almería','Asturias','Ávila','Badajoz','Barcelona','Burgos','Cáceres','Cádiz','Cantabria','Castellón','Ciudad Real','Córdoba','Cuenca','Girona','Granada','Guadalajara','Guipúzcoa','Huelva','Huesca','Islas Baleares','Jaén','La Coruña','La Rioja','Las Palmas','León','Lleida','Lugo','Madrid','Málaga','Murcia','Navarra','Ourense','Palencia','Pontevedra','Salamanca','Santa Cruz de Tenerife','Segovia','Sevilla','Soria','Tarragona','Teruel','Toledo','Valencia','Valladolid','Vizcaya','Zamora','Zaragoza'] as $prov)
                  <option value="{{ $prov }}" {{ old('deceased_province') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
              </select>
              @error('deceased_province') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <hr style="border-color:var(--border);margin:var(--sp-6) 0">

          {{-- Solicitante --}}
          <h3 style="font-size:var(--t-sm);font-weight:var(--fw-bold);text-transform:uppercase;letter-spacing:.07em;color:var(--text-3);margin:0 0 var(--sp-4)">
            Datos del solicitante (cliente de la asesoría)
          </h3>

          <div class="row g-4 mb-4">
            <div class="col-md-8">
              <label class="form-label" for="applicant_name">
                Nombre completo <span class="required" aria-hidden="true">*</span>
              </label>
              <input type="text" id="applicant_name" name="applicant_name"
                     value="{{ old('applicant_name') }}" required
                     class="form-control @error('applicant_name') is-invalid @enderror"
                     placeholder="Nombre del heredero o beneficiario">
              @error('applicant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label" for="applicant_dni">
                DNI / NIE <span class="required" aria-hidden="true">*</span>
              </label>
              <input type="text" id="applicant_dni" name="applicant_dni"
                     value="{{ old('applicant_dni') }}" required
                     class="form-control @error('applicant_dni') is-invalid @enderror"
                     placeholder="12345678A">
              @error('applicant_dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row g-4 mb-4">
            <div class="col-md-4">
              <label class="form-label" for="applicant_relationship">
                Parentesco / relación <span class="required" aria-hidden="true">*</span>
              </label>
              <select id="applicant_relationship" name="applicant_relationship" required
                      class="form-control @error('applicant_relationship') is-invalid @enderror">
                <option value="">— Selecciona —</option>
                @foreach(['Heredero/a','Cónyuge / pareja de hecho','Hijo/a','Padre / Madre','Hermano/a','Beneficiario designado','Albacea testamentario','Otro'] as $rel)
                  <option value="{{ $rel }}" {{ old('applicant_relationship') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                @endforeach
              </select>
              @error('applicant_relationship') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label" for="applicant_email">Email de contacto</label>
              <input type="email" id="applicant_email" name="applicant_email"
                     value="{{ old('applicant_email') }}"
                     class="form-control @error('applicant_email') is-invalid @enderror"
                     placeholder="correo@ejemplo.com">
              @error('applicant_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label" for="applicant_phone">Teléfono</label>
              <input type="tel" id="applicant_phone" name="applicant_phone"
                     value="{{ old('applicant_phone') }}"
                     class="form-control @error('applicant_phone') is-invalid @enderror"
                     placeholder="600 000 000">
              @error('applicant_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label" for="notes">Notas internas</label>
            <textarea id="notes" name="notes" rows="3"
                      class="form-control @error('notes') is-invalid @enderror"
                      placeholder="Observaciones del gestor, documentación aportada, etc.">{{ old('notes') }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div style="display:flex;gap:var(--sp-3);justify-content:flex-end">
            <a href="{{ route('tools.fallecido.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
              Crear expediente y ver pasos →
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection
