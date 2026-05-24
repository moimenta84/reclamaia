<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Reclama')</title>
<meta name="description" content="@yield('meta-description', 'Accede a Reclama — Software de gestión de reclamaciones de seguros para asesorías.')">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..800;1,14..32,300..800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ══════════════════════════════════════════════════════════════
   AUTH LAYOUT — Reclama Premium Split Screen
   ══════════════════════════════════════════════════════════════ */
*,*::before,*::after { box-sizing: border-box; }
html { scroll-behavior: smooth; height: 100%; }

:root {
  --lime:        #B6E62E;
  --lime-dark:   #A3D126;
  --lime-dim:    rgba(182,230,46,.12);
  --lime-border: rgba(182,230,46,.22);

  --bg:          #0F1115;
  --bg-card:     #181C24;
  --bg-elevated: #1E2333;
  --panel-bg:    #0B0E15;

  --border:      #2A2F3A;
  --border-md:   #353C4A;

  --text:   #F3F4F6;
  --text-2: #E4E4E7;
  --text-3: #A1A1AA;
  --text-4: #6B7280;

  --danger:     #F87171;
  --danger-bg:  rgba(248,113,113,.12);
  --success:    #22C55E;
  --success-bg: rgba(34,197,94,.12);

  --r-sm:   6px;
  --r-md:   10px;
  --r-lg:   14px;
  --r-xl:   20px;
  --r-full: 9999px;

  --sh-ring: 0 0 0 2px #0F1115, 0 0 0 4px #B6E62E;

  --sp-1:.25rem;--sp-2:.5rem;--sp-3:.75rem;--sp-4:1rem;
  --sp-5:1.25rem;--sp-6:1.5rem;--sp-8:2rem;--sp-10:2.5rem;--sp-12:3rem;

  --t-xs:.75rem;--t-sm:.8125rem;--t-base:.9375rem;--t-lg:1.0625rem;
  --t-xl:1.25rem;--t-2xl:1.5rem;--t-3xl:1.875rem;

  --fw-normal:400;--fw-medium:500;--fw-semibold:600;--fw-bold:700;--fw-extrabold:800;
}

body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  font-feature-settings: 'cv02' 1, 'cv03' 1, 'cv04' 1, 'cv11' 1;
  background: var(--bg);
  color: var(--text);
  margin: 0;
  min-height: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Skip link — WCAG 2.4.1 */
.skip-link {
  position: absolute; top: -100%; left: var(--sp-4); z-index: 9999;
  background: var(--lime); color: #0F1115;
  font-size: var(--t-sm); font-weight: var(--fw-bold);
  padding: var(--sp-3) var(--sp-5);
  border-radius: 0 0 var(--r-md) var(--r-md);
  text-decoration: none;
  transition: top 80ms;
}
.skip-link:focus { top: 0; }

/* WCAG 2.4.7 visible focus */
:focus-visible {
  outline: none;
  box-shadow: var(--sh-ring);
  border-radius: var(--r-sm);
}
:focus:not(:focus-visible) { outline: none; }

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  *,*::before,*::after {
    animation-duration: .01ms !important;
    transition-duration: .01ms !important;
  }
}

/* ─── Shell layout ──────────────────────────────────────────── */
.auth-shell {
  display: flex;
  min-height: 100vh;
}

/* ─── Left brand panel ──────────────────────────────────────── */
.auth-panel {
  flex: 0 0 480px;
  background: var(--panel-bg);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  padding: var(--sp-10) var(--sp-10) var(--sp-8);
  position: relative;
  overflow: hidden;
}

/* Subtle lime glow in top-right of panel */
.auth-panel::before {
  content: '';
  position: absolute; top: -120px; right: -80px;
  width: 400px; height: 400px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(182,230,46,.07) 0%, transparent 70%);
  pointer-events: none;
}

.auth-panel-logo {
  display: flex; align-items: center; gap: var(--sp-3);
  margin-bottom: var(--sp-12);
  position: relative;
}
.auth-logo-mark {
  width: 38px; height: 38px; border-radius: var(--r-md);
  background: var(--lime);
  display: flex; align-items: center; justify-content: center;
  color: #0F1115; font-size: .72rem; font-weight: var(--fw-extrabold);
  letter-spacing: -.03em; flex-shrink: 0;
}
.auth-logo-word {
  font-size: 1.05rem; font-weight: var(--fw-extrabold);
  letter-spacing: -.03em; color: var(--text);
}
.auth-logo-word span { color: var(--lime); }

.auth-panel-body { flex: 1; display: flex; flex-direction: column; justify-content: center; }

.auth-panel-headline {
  font-size: var(--t-2xl); font-weight: var(--fw-bold);
  letter-spacing: -.03em; line-height: 1.2;
  color: var(--text); margin: 0 0 var(--sp-3);
}
.auth-panel-sub {
  font-size: var(--t-sm); color: var(--text-3); line-height: 1.65;
  margin: 0 0 var(--sp-8); max-width: 340px;
}

.auth-features {
  list-style: none; padding: 0; margin: 0 0 var(--sp-10);
  display: flex; flex-direction: column; gap: var(--sp-4);
}
.auth-feature {
  display: flex; align-items: flex-start; gap: var(--sp-3);
}
.auth-feature-check {
  width: 20px; height: 20px; border-radius: var(--r-full);
  background: var(--lime-dim); border: 1px solid var(--lime-border);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 1px;
}
.auth-feature-check svg { color: var(--lime); }
.auth-feature-text strong {
  display: block; font-size: var(--t-sm); font-weight: var(--fw-semibold);
  color: var(--text-2); line-height: 1.3;
}
.auth-feature-text span {
  font-size: var(--t-xs); color: var(--text-4); line-height: 1.5;
}

/* Social proof strip */
.auth-social-proof {
  border-top: 1px solid var(--border);
  padding-top: var(--sp-5);
  margin-top: auto;
}
.auth-testimonial-text {
  font-size: var(--t-xs); font-style: italic;
  color: var(--text-3); line-height: 1.65;
  margin: 0 0 var(--sp-3);
}
.auth-testimonial-author {
  display: flex; align-items: center; gap: var(--sp-2);
}
.auth-testimonial-avatar {
  width: 28px; height: 28px; border-radius: var(--r-full);
  background: var(--lime-dim); border: 1px solid var(--lime-border);
  display: flex; align-items: center; justify-content: center;
  font-size: .65rem; font-weight: var(--fw-bold); color: var(--lime);
  flex-shrink: 0;
}
.auth-testimonial-name {
  font-size: var(--t-xs); font-weight: var(--fw-semibold); color: var(--text-2);
}
.auth-testimonial-role {
  font-size: var(--t-xs); color: var(--text-4);
}

/* Compliance logos */
.auth-compliance {
  display: flex; gap: var(--sp-3); flex-wrap: wrap;
  margin-top: var(--sp-5);
}
.auth-compliance-badge {
  font-size: .65rem; font-weight: var(--fw-semibold); letter-spacing: .06em;
  text-transform: uppercase; color: var(--text-4);
  display: flex; align-items: center; gap: 4px;
}

/* ─── Right form panel ──────────────────────────────────────── */
.auth-form-panel {
  flex: 1;
  display: flex; align-items: center; justify-content: center;
  padding: var(--sp-10) var(--sp-8);
}

.auth-form-inner {
  width: 100%; max-width: 400px;
}

/* Mobile logo (shown when left panel hidden) */
.auth-mobile-logo {
  display: none;
  align-items: center; gap: var(--sp-2);
  margin-bottom: var(--sp-8);
  text-decoration: none;
}
.auth-mobile-logo .logo-mark {
  width: 34px; height: 34px; border-radius: var(--r-md);
  background: var(--lime);
  display: flex; align-items: center; justify-content: center;
  color: #0F1115; font-size: .65rem; font-weight: var(--fw-extrabold);
}
.auth-mobile-logo .logo-word {
  font-size: 1rem; font-weight: var(--fw-extrabold); letter-spacing: -.03em;
  color: var(--text);
}
.auth-mobile-logo .logo-word span { color: var(--lime); }

.auth-form-title {
  font-size: var(--t-2xl); font-weight: var(--fw-bold);
  letter-spacing: -.03em; color: var(--text);
  margin: 0 0 var(--sp-2);
}
.auth-form-subtitle {
  font-size: var(--t-sm); color: var(--text-3);
  margin: 0 0 var(--sp-8); line-height: 1.6;
}

/* Form elements */
.form-label {
  font-size: var(--t-sm); font-weight: var(--fw-medium);
  color: var(--text-2); margin-bottom: var(--sp-2); display: block;
}
.required { color: var(--danger); margin-left: 2px; font-size: .65rem; vertical-align: super; }

.form-control {
  width: 100%; font-family: inherit; font-size: var(--t-sm);
  color: var(--text); background: var(--bg-elevated);
  border: 1px solid var(--border-md); border-radius: var(--r-md);
  padding: .625rem .875rem; line-height: 1.4;
  transition: border-color 140ms, box-shadow 140ms, background 140ms;
}
.form-control::placeholder { color: var(--text-4); }
.form-control:hover:not(:focus) { border-color: #475060; background: rgba(255,255,255,.03); }
.form-control:focus { border-color: var(--lime); box-shadow: var(--sh-ring); outline: none; background: var(--bg-elevated); }
.form-control.is-invalid { border-color: var(--danger); background: var(--danger-bg); }
.form-control.is-invalid:focus { box-shadow: 0 0 0 2px #0F1115, 0 0 0 4px var(--danger); }

.form-text {
  font-size: var(--t-xs); color: var(--text-4); margin-top: var(--sp-1); display: block;
}
.invalid-feedback {
  font-size: var(--t-xs); font-weight: var(--fw-medium); color: var(--danger);
  display: flex; align-items: center; gap: var(--sp-1); margin-top: var(--sp-1);
}
.invalid-feedback::before {
  content: '!'; font-size: .6rem; font-weight: 900;
  width: 13px; height: 13px; background: var(--danger); color: #fff;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

.form-check { display: flex; align-items: center; gap: var(--sp-2); }
.form-check-input {
  width: 16px; height: 16px; border-radius: 4px; cursor: pointer;
  background: var(--bg-elevated); border: 1px solid var(--border-md);
  appearance: none; -webkit-appearance: none;
  transition: border-color 140ms, background 140ms, box-shadow 140ms;
  flex-shrink: 0;
}
.form-check-input:checked {
  background-color: var(--lime); border-color: var(--lime);
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='%230F1115' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='m3 8 3.5 3.5 6.5-6.5'/%3e%3c/svg%3e");
  background-repeat: no-repeat; background-position: center;
}
.form-check-input:focus { box-shadow: var(--sh-ring); outline: none; }
.form-check-label { font-size: var(--t-sm); color: var(--text-3); cursor: pointer; }

/* Divider */
.auth-divider {
  display: flex; align-items: center; gap: var(--sp-3);
  color: var(--text-4); font-size: var(--t-xs);
  margin: var(--sp-6) 0;
}
.auth-divider::before, .auth-divider::after {
  content: ''; flex: 1; border-top: 1px solid var(--border);
}

/* Buttons */
.btn {
  display: inline-flex; align-items: center; justify-content: center;
  gap: var(--sp-2); font-family: inherit; font-size: var(--t-sm);
  font-weight: var(--fw-semibold); letter-spacing: -.01em;
  border-radius: var(--r-md); padding: .625rem 1.125rem;
  border: 1px solid transparent; cursor: pointer;
  text-decoration: none; white-space: nowrap;
  position: relative; overflow: hidden;
  transition: background 140ms ease, border-color 140ms, box-shadow 140ms, transform 80ms;
}
.btn:active { transform: scale(.97); }

.btn-primary {
  width: 100%; background: var(--lime); border-color: var(--lime-dark);
  color: #0F1115 !important;
  font-size: var(--t-base); padding: .75rem 1.625rem;
  box-shadow: 0 0 20px rgba(182,230,46,.15), 0 1px 0 rgba(255,255,255,.1) inset;
}
.btn-primary:hover {
  background: var(--lime-dark); border-color: #8BB518; color: #0F1115 !important;
  box-shadow: 0 0 32px rgba(182,230,46,.25), 0 4px 12px rgba(0,0,0,.4);
  transform: translateY(-1px);
}
.btn-primary::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(105deg,transparent 30%,rgba(255,255,255,.12) 50%,transparent 70%);
  transform: translateX(-100%); transition: transform 350ms cubic-bezier(.16,1,.3,1);
}
.btn-primary:hover::after { transform: translateX(100%); }

/* Alerts */
.alert {
  border-radius: var(--r-lg); font-size: var(--t-sm);
  padding: var(--sp-4) var(--sp-5);
  display: flex; align-items: flex-start; gap: var(--sp-3);
  border-left: 3px solid transparent; border: none; border-left-width: 3px;
  border-left-style: solid;
}
.alert-danger { background: var(--danger-bg); color: #FCA5A5; border-left-color: var(--danger); }
.alert strong { font-weight: var(--fw-semibold); }

/* Link */
a { color: var(--lime); text-underline-offset: 3px; }
a:hover { color: var(--lime-dark); }
.text-muted { color: var(--text-3) !important; }
.fw-semibold { font-weight: var(--fw-semibold) !important; }

/* Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #2A2F3A; border-radius: 999px; }
::-webkit-scrollbar-thumb:hover { background: #353C4A; }

/* ─── High-contrast & forced-colors ─────────────────────────── */
@media (forced-colors: active) {
  .btn-primary { forced-color-adjust: none; }
  :focus-visible { outline: 3px solid Highlight; box-shadow: none; }
}

/* ─── Mobile responsive ─────────────────────────────────────── */
@media (max-width: 900px) {
  .auth-panel { display: none; }
  .auth-form-panel { padding: var(--sp-8) var(--sp-6); align-items: flex-start; padding-top: var(--sp-10); }
  .auth-mobile-logo { display: flex; }
}
@media (max-width: 480px) {
  .auth-form-panel { padding: var(--sp-6) var(--sp-4); }
  .auth-form-inner { max-width: 100%; }
}
</style>
</head>
<body>

<a class="skip-link" href="#auth-form">Ir al formulario</a>

<div class="auth-shell">

  {{-- ─── Left brand panel ──────────────────────────────────── --}}
  <aside class="auth-panel" aria-label="Acerca de Reclama">

    <div class="auth-panel-logo">
      <div class="auth-logo-mark" aria-hidden="true">R</div>
      <span class="auth-logo-word">Reclama</span>
    </div>

    <div class="auth-panel-body">
      <h1 class="auth-panel-headline">Gestión inteligente<br>de reclamaciones de seguros</h1>
      <p class="auth-panel-sub">
        Software para asesorías que genera cartas formales con base legal en menos de 90 segundos.
        Conforme a la LCS y lista para el Defensor del Asegurado.
      </p>

      <ul class="auth-features" aria-label="Características principales">
        <li class="auth-feature">
          <div class="auth-feature-check" aria-hidden="true">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <div class="auth-feature-text">
            <strong>Cartas listas en 90 segundos</strong>
            <span>Normativa vigente aplicada: LCS, art. 18, DGSFP.</span>
          </div>
        </li>
        <li class="auth-feature">
          <div class="auth-feature-check" aria-hidden="true">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <div class="auth-feature-text">
            <strong>Análisis de viabilidad y póliza</strong>
            <span>OCR de pólizas, cláusulas clave y jurisprudencia CENDOJ.</span>
          </div>
        </li>
        <li class="auth-feature">
          <div class="auth-feature-check" aria-hidden="true">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <div class="auth-feature-text">
            <strong>Firma eIDAS integrada</strong>
            <span>Reclamaciones firmadas digitalmente sin salir de la plataforma.</span>
          </div>
        </li>
        <li class="auth-feature">
          <div class="auth-feature-check" aria-hidden="true">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <div class="auth-feature-text">
            <strong>Baremo 2024 y valoración de daños</strong>
            <span>Cálculo automático con tablas actualizadas. Word y PDF incluidos.</span>
          </div>
        </li>
      </ul>

      <div class="auth-social-proof">
        <p class="auth-testimonial-text" aria-label="Testimonio de cliente">
          "Antes tardábamos un día entero en redactar cada reclamación. Con Reclama lo hacemos en dos minutos y la carta ya lleva la jurisprudencia correcta."
        </p>
        <div class="auth-testimonial-author">
          <div class="auth-testimonial-avatar" aria-hidden="true">MR</div>
          <div>
            <div class="auth-testimonial-name">Marta Rodríguez</div>
            <div class="auth-testimonial-role">Directora — Asesoría Seguros Levante</div>
          </div>
        </div>

        <div class="auth-compliance" role="list" aria-label="Certificaciones y cumplimiento normativo">
          <span class="auth-compliance-badge" role="listitem">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            RGPD
          </span>
          <span class="auth-compliance-badge" role="listitem">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            LSSI-CE
          </span>
          <span class="auth-compliance-badge" role="listitem">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            WCAG 2.1 AA
          </span>
        </div>
      </div>
    </div>

  </aside>

  {{-- ─── Right form panel ───────────────────────────────────── --}}
  <main class="auth-form-panel" id="auth-form" tabindex="-1">
    <div class="auth-form-inner">

      {{-- Mobile logo (only visible on small screens) --}}
      <a class="auth-mobile-logo" href="{{ route('home') }}" aria-label="Reclama — inicio">
        <div class="logo-mark" aria-hidden="true">R</div>
        <span class="logo-word">Reclama</span>
      </a>

      @yield('content')

    </div>
  </main>

</div>

</body>
</html>
