@extends('layouts.auth')

@section('title', 'Entrar — Reclama')
@section('meta-description', 'Accede a tu cuenta para gestionar los expedientes de tu asesoría.')

@section('content')

<h1 class="auth-form-title">Acceder a tu asesoría</h1>
<p class="auth-form-subtitle">Panel de gestión de expedientes y reclamaciones.</p>

@if($errors->any())
  <div class="alert alert-danger mb-4" role="alert" aria-live="assertive">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink:0;margin-top:2px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
      <strong>Revisa los siguientes errores:</strong>
      <ul style="margin:.375rem 0 0;padding-left:1.25rem">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

<form method="POST" action="{{ route('login') }}" novalidate>
  @csrf

  <div style="margin-bottom:var(--sp-4)">
    <label class="form-label" for="email">
      Correo electrónico <span class="required" aria-hidden="true">*</span>
    </label>
    <input type="email" id="email" name="email"
           value="{{ old('email') }}"
           class="form-control @error('email') is-invalid @enderror"
           autocomplete="email"
           aria-required="true"
           @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
           autofocus required
           placeholder="tu@asesoria.com">
    @error('email')
      <div id="email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
  </div>

  <div style="margin-bottom:var(--sp-5)">
    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:var(--sp-2)">
      <label class="form-label" for="password" style="margin-bottom:0">
        Contraseña <span class="required" aria-hidden="true">*</span>
      </label>
    </div>
    <input type="password" id="password" name="password"
           class="form-control @error('password') is-invalid @enderror"
           autocomplete="current-password"
           aria-required="true"
           @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
           required
           placeholder="••••••••">
    @error('password')
      <div id="password-error" class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
  </div>

  <div class="form-check" style="margin-bottom:var(--sp-6)">
    <input class="form-check-input" type="checkbox" name="remember" id="remember">
    <label class="form-check-label" for="remember">Recordarme en este dispositivo</label>
  </div>

  <button type="submit" class="btn btn-primary" aria-label="Entrar a tu cuenta">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Entrar
  </button>
</form>

<div class="auth-divider" aria-hidden="true">o</div>

<p style="text-align:center;font-size:var(--t-sm);color:var(--text-3);margin:0">
  ¿No tienes cuenta?
  <a href="{{ route('register') }}" class="fw-semibold" style="color:var(--lime)">Crear cuenta gratuita</a>
</p>

@endsection
