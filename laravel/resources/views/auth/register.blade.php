@extends('layouts.auth')

@section('title', 'Crear cuenta — Reclama')
@section('meta-description', 'Crea tu cuenta y empieza a gestionar reclamaciones para tu asesoría.')

@section('content')

<h1 class="auth-form-title">Crear cuenta para tu asesoría</h1>
<p class="auth-form-subtitle">14 días gratis. Sin tarjeta de crédito. Cancela cuando quieras.</p>

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

<form method="POST" action="{{ route('register') }}" novalidate>
  @csrf

  <div style="margin-bottom:var(--sp-4)">
    <label class="form-label" for="name">
      Nombre completo <span class="required" aria-hidden="true">*</span>
    </label>
    <input type="text" id="name" name="name"
           value="{{ old('name') }}"
           class="form-control @error('name') is-invalid @enderror"
           autocomplete="name"
           aria-required="true"
           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
           autofocus required
           placeholder="Ana García">
    @error('name')
      <div id="name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
  </div>

  <div style="margin-bottom:var(--sp-4)">
    <label class="form-label" for="reg-email">
      Correo electrónico <span class="required" aria-hidden="true">*</span>
    </label>
    <input type="email" id="reg-email" name="email"
           value="{{ old('email') }}"
           class="form-control @error('email') is-invalid @enderror"
           autocomplete="email"
           aria-required="true"
           aria-describedby="email-hint @error('email') email-error @enderror"
           @error('email') aria-invalid="true" @enderror
           required
           placeholder="ana@asesoria.com">
    <span id="email-hint" class="form-text">
      Si ya generaste una reclamación con este email, quedará asociada a tu cuenta.
    </span>
    @error('email')
      <div id="email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
  </div>

  <div style="margin-bottom:var(--sp-4)">
    <label class="form-label" for="reg-password">
      Contraseña <span class="required" aria-hidden="true">*</span>
    </label>
    <input type="password" id="reg-password" name="password"
           class="form-control @error('password') is-invalid @enderror"
           autocomplete="new-password"
           aria-required="true"
           aria-describedby="password-hint @error('password') password-error @enderror"
           @error('password') aria-invalid="true" @enderror
           minlength="8" required
           placeholder="Mínimo 8 caracteres">
    <span id="password-hint" class="form-text">Mínimo 8 caracteres.</span>
    @error('password')
      <div id="password-error" class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror
  </div>

  <div style="margin-bottom:var(--sp-5)">
    <label class="form-label" for="password_confirmation">
      Confirmar contraseña <span class="required" aria-hidden="true">*</span>
    </label>
    <input type="password" id="password_confirmation" name="password_confirmation"
           class="form-control"
           autocomplete="new-password"
           aria-required="true"
           required
           placeholder="Repite tu contraseña">
  </div>

  <p style="font-size:var(--t-xs);color:var(--text-4);margin-bottom:var(--sp-5);line-height:1.65">
    Al crear una cuenta aceptas nuestros
    <a href="{{ route('legal.terminos') }}" style="color:var(--text-3)">Términos de uso</a> y la
    <a href="{{ route('legal.privacidad') }}" style="color:var(--text-3)">Política de privacidad</a>.
  </p>

  <button type="submit" class="btn btn-primary" aria-label="Crear cuenta en Reclama">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
    Crear cuenta
  </button>
</form>

<div class="auth-divider" aria-hidden="true">o</div>

<p style="text-align:center;font-size:var(--t-sm);color:var(--text-3);margin:0">
  ¿Ya tienes cuenta?
  <a href="{{ route('login') }}" class="fw-semibold" style="color:var(--lime)">Entrar</a>
</p>

@endsection
