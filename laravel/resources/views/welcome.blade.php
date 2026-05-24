@extends('layouts.app')
@section('title', 'Reclama — Software de Reclamaciones para Asesorías de Seguros')
@section('meta-description', 'La plataforma que usan las asesorías de seguros para generar reclamaciones formales con base legal española en segundos. Baremo + Jurisprudencia CENDOJ. Prueba gratis.')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   Reclama — WELCOME PAGE STYLES
   ══════════════════════════════════════════════════════════════ */

/* ─ Layout override for full-bleed sections ─────────────────── */
.page { padding: 0 !important; }
.page > .container { display: none; }

/* ─ Hero ─────────────────────────────────────────────────────── */
.hero {
  position: relative; overflow: hidden;
  background: var(--s-900);
  padding: var(--sp-24) 0 var(--sp-20);
}
.hero-bg {
  position: absolute; inset: 0; pointer-events: none;
  background:
    radial-gradient(ellipse 90% 60% at 50% 0%, rgba(182,230,46,.07) 0%, transparent 65%),
    radial-gradient(ellipse 55% 45% at 5% 85%, rgba(182,230,46,.04) 0%, transparent 60%),
    radial-gradient(ellipse 40% 40% at 95% 95%, rgba(182,230,46,.03) 0%, transparent 60%);
}
.hero-grid {
  position: absolute; inset: 0; pointer-events: none; opacity: .03;
  background-image:
    linear-gradient(rgba(255,255,255,.9) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.9) 1px, transparent 1px);
  background-size: 48px 48px;
}
.hero-rel { position: relative; z-index: 1; }

.hero-badge {
  display: inline-flex; align-items: center; gap: var(--sp-2);
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  letter-spacing: .07em; text-transform: uppercase;
  color: var(--lime); background: var(--lime-dim);
  border: 1px solid var(--lime-border);
  padding: .3rem .9rem; border-radius: var(--r-full);
  margin-bottom: var(--sp-6);
}
.hero-badge-dot {
  width: 6px; height: 6px; border-radius: 50%; background: var(--lime);
}

.hero h1 {
  font-size: clamp(2.1rem, 5.5vw, 3.875rem);
  font-weight: var(--fw-black);
  letter-spacing: -.04em; line-height: 1.07;
  color: #fff; margin-bottom: var(--sp-6);
}
.hero h1 em { color: var(--lime); font-style: italic; }

.hero-sub {
  font-size: clamp(var(--t-base), 2vw, var(--t-lg));
  color: var(--s-400); line-height: 1.7;
  margin-bottom: var(--sp-8); max-width: 520px;
}

.hero-actions {
  display: flex; flex-wrap: wrap; align-items: center;
  gap: var(--sp-3); margin-bottom: var(--sp-10);
}

.btn-hero-ghost {
  background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.14);
  color: rgba(255,255,255,.82) !important;
  font-size: var(--t-base); padding: .875rem 2rem; border-radius: var(--r-lg);
  font-weight: var(--fw-semibold); text-decoration: none;
  display: inline-flex; align-items: center; gap: var(--sp-2);
  transition: background var(--t-base-d), color var(--t-base-d), border-color var(--t-base-d);
}
.btn-hero-ghost:hover {
  background: rgba(255,255,255,.13); color: #fff !important;
  border-color: rgba(255,255,255,.22);
}

/* Trust row */
.hero-trust {
  display: flex; flex-wrap: wrap; align-items: center; gap: var(--sp-5);
  padding-top: var(--sp-8); border-top: 1px solid rgba(255,255,255,.07);
}
.trust-item { display: flex; align-items: center; gap: var(--sp-2); }
.trust-item svg { color: var(--lime); flex-shrink: 0; }
.trust-item strong { font-size: var(--t-xs); color: var(--text-2); font-weight: var(--fw-semibold); }
.trust-item span   { font-size: var(--t-xs); color: var(--text-4); }
.trust-sep { width: 1px; height: 18px; background: rgba(255,255,255,.08); }

/* Preview card (right column) */
.hero-preview {
  background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
  border-radius: var(--r-xl); padding: var(--sp-6);
  backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
}
.preview-header {
  display: flex; align-items: center; gap: var(--sp-3);
  margin-bottom: var(--sp-5); padding-bottom: var(--sp-5);
  border-bottom: 1px solid rgba(255,255,255,.08);
}
.preview-status-dot {
  width: 32px; height: 32px; border-radius: var(--r-sm);
  background: rgba(34,197,94,.18); border: 1px solid rgba(34,197,94,.3);
  display: flex; align-items: center; justify-content: center;
  font-size: .9rem; flex-shrink: 0;
}
.preview-title { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: #fff; }
.preview-sub   { font-size: var(--t-xs); color: var(--s-600); }
.preview-badge-ok {
  margin-left: auto; font-size: .62rem; font-weight: 700; letter-spacing: .03em;
  background: rgba(22,163,74,.2); color: #4ade80; border: 1px solid rgba(22,163,74,.3);
  padding: .2rem .55rem; border-radius: var(--r-full);
}
.preview-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: var(--sp-2) 0; border-bottom: 1px solid rgba(255,255,255,.05);
}
.preview-row:last-of-type { border-bottom: none; }
.preview-row .pk { font-size: var(--t-xs); color: var(--s-600); }
.preview-row .pv { font-size: var(--t-xs); font-weight: var(--fw-medium); color: rgba(255,255,255,.78); }
.preview-actions {
  display: flex; gap: var(--sp-2); margin-top: var(--sp-5);
}
.preview-btn {
  flex: 1; background: var(--lime-dim);
  border: 1px solid var(--lime-border); border-radius: var(--r-sm);
  padding: var(--sp-2) var(--sp-3); text-align: center;
  font-size: var(--t-xs); font-weight: var(--fw-semibold); color: var(--lime);
}

/* ─ Stats bar ────────────────────────────────────────────────── */
.stats-bar {
  background: var(--s-900);
  border-bottom: 1px solid rgba(255,255,255,.06);
}
.stat-col {
  padding: var(--sp-8) var(--sp-6); text-align: center;
  border-right: 1px solid rgba(255,255,255,.06);
}
.stat-col:last-child { border-right: none; }
.stat-col .sv {
  display: block;
  font-family: 'Inter', system-ui, sans-serif;
  font-size: var(--t-2xl); font-weight: var(--fw-bold);
  letter-spacing: -.03em; color: var(--text);
}
.stat-col .sl {
  display: block; font-size: var(--t-xs); color: var(--text-4); margin-top: 3px;
}

/* ─ Section base ─────────────────────────────────────────────── */
.sec        { padding: var(--sp-24) 0; }
.sec-light  { background: var(--bg); }
.sec-alt    { background: var(--bg-card); }
.sec-dark   {
  background: var(--bg-elevated); position: relative; overflow: hidden;
  color: var(--text-3);
}
.sec-dark-overlay {
  position: absolute; inset: 0; pointer-events: none;
  background: radial-gradient(ellipse 70% 70% at 50% 50%, rgba(182,230,46,.05) 0%, transparent 70%);
}

.sec-head { text-align: center; margin-bottom: var(--sp-12); }
.sec-head .eyebrow { margin-bottom: var(--sp-4); }
.sec-head h2 { max-width: 540px; margin: 0 auto var(--sp-4); }
.sec-head p  { max-width: 460px; margin: 0 auto; color: var(--text-3); font-size: var(--t-base); }
.sec-head.dark-head h2 { color: #fff; }
.sec-head.dark-head p  { color: var(--s-400); }

/* ─ Insurance types grid ─────────────────────────────────────── */
.ins-card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: var(--sp-5) var(--sp-5);
  display: flex; flex-direction: column; align-items: flex-start; gap: var(--sp-2);
  box-shadow: var(--sh-card);
  transition: box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease);
  height: 100%;
}
.ins-card:hover { box-shadow: var(--sh-card-hover); transform: translateY(-2px); }
.ins-icon {
  width: 44px; height: 44px; border-radius: var(--r-md);
  display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
  flex-shrink: 0;
}
.ins-card h3 { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); margin: 0; }
.ins-card p  { font-size: var(--t-xs); color: var(--text-3); margin: 0; line-height: 1.5; }

/* ─ Steps ────────────────────────────────────────────────────── */
.step { display: flex; gap: var(--sp-5); }
.step-line { display: flex; flex-direction: column; align-items: center; gap: 0; flex-shrink: 0; }
.step-num {
  width: 38px; height: 38px; border-radius: var(--r-full);
  background: var(--lime-dim); border: 1px solid var(--lime-border);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Inter', system-ui, sans-serif;
  font-size: var(--t-base); font-weight: var(--fw-semibold); font-style: normal; color: var(--lime);
  box-shadow: 0 0 0 4px rgba(182,230,46,.06);
  flex-shrink: 0; position: relative; z-index: 1;
}
.step-connector {
  width: 1px; flex-grow: 1; min-height: 40px;
  background: linear-gradient(to bottom, var(--lime-border) 0%, transparent 100%);
  margin: var(--sp-2) 0;
}
.step-body { padding-bottom: var(--sp-10); flex: 1; }
.step-body h3 {
  font-size: var(--t-lg); font-weight: var(--fw-semibold);
  color: var(--text); margin-bottom: var(--sp-2);
}
.step-body p { font-size: var(--t-sm); color: var(--text-3); line-height: 1.75; margin: 0; }
.step-tag {
  display: inline-flex; align-items: center; gap: var(--sp-1);
  font-size: var(--t-xs); font-weight: var(--fw-semibold); color: var(--lime);
  background: var(--lime-dim); border: 1px solid var(--lime-border);
  padding: .2rem .55rem; border-radius: var(--r-full); margin-bottom: var(--sp-3);
}

/* ─ Results section ──────────────────────────────────────────── */
.result-card {
  background: var(--bg-elevated);
  border: 1px solid var(--border-md);
  border-top: 3px solid var(--lime);
  border-radius: var(--r-xl);
  padding: var(--sp-7) var(--sp-7) var(--sp-6);
  display: flex; flex-direction: column;
  transition: border-color var(--t-base-d), box-shadow var(--t-base-d), transform var(--t-base-d);
}
.result-card:hover {
  border-color: var(--lime);
  box-shadow: 0 0 0 1px var(--lime-border), var(--sh-lg);
  transform: translateY(-3px);
}
.result-label {
  font-size: var(--t-xs); font-weight: var(--fw-bold);
  letter-spacing: .09em; text-transform: uppercase;
  color: var(--lime); margin-bottom: var(--sp-3);
}
.result-amount {
  font-size: clamp(2.25rem, 4.5vw, var(--t-5xl));
  font-weight: var(--fw-black);
  color: #fff; letter-spacing: -.045em; line-height: 1;
  margin-bottom: var(--sp-5);
}
.result-divider {
  border: none; border-top: 1px solid var(--border);
  margin: 0 0 var(--sp-5);
}
.result-meta  { display: flex; flex-direction: column; gap: var(--sp-2); margin-top: auto; }
.result-meta-company { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text-2); }
.result-meta-detail  { font-size: var(--t-xs); color: var(--text-4); }
.result-counter {
  text-align: center; margin-top: var(--sp-12); padding-top: var(--sp-8);
  border-top: 1px solid rgba(255,255,255,.07);
}
.result-counter-val {
  font-size: clamp(2rem, 5vw, 3.5rem); font-weight: var(--fw-black);
  color: #fff; letter-spacing: -.04em;
}
.result-counter-label { font-size: var(--t-sm); color: var(--s-500); margin-top: var(--sp-2); }

/* ─ Features grid ────────────────────────────────────────────── */
.fcard {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: var(--sp-6); height: 100%;
  transition: box-shadow var(--t-slow) var(--ease), transform var(--t-slow) var(--ease);
}
.fcard:hover { box-shadow: var(--sh-lg); transform: translateY(-3px); }
.fcard-icon {
  width: 46px; height: 46px; border-radius: var(--r-md);
  display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
  margin-bottom: var(--sp-4);
}
.fcard h3 { font-size: var(--t-base); font-weight: var(--fw-semibold); color: var(--text); margin-bottom: var(--sp-2); }
.fcard p  { font-size: var(--t-sm); color: var(--text-3); line-height: 1.65; margin: 0; }

/* ─ Testimonials ─────────────────────────────────────────────── */
.tcard {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: var(--sp-6); height: 100%;
  box-shadow: var(--sh-card);
  transition: box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease);
}
.tcard:hover { box-shadow: var(--sh-card-hover); transform: translateY(-2px); }
.tcard-stars { color: #f59e0b; font-size: .9rem; letter-spacing: 2px; margin-bottom: var(--sp-4); }
.tcard blockquote {
  font-size: var(--t-sm); color: var(--text-2); line-height: 1.8;
  margin: 0 0 var(--sp-5); font-style: normal;
}
.tcard-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--sp-3); }
.tcard-author { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); line-height: 1.2; }
.tcard-role   { font-size: var(--t-xs); color: var(--text-3); }
.tcard-amount {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  color: var(--success); background: var(--success-bg);
  padding: .2rem .55rem; border-radius: var(--r-full);
}

/* ─ Pricing ──────────────────────────────────────────────────── */
.pricing-card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-xl); padding: var(--sp-8); height: 100%;
  position: relative; box-shadow: var(--sh-card);
}
.pricing-card.featured {
  border-color: var(--lime-border); background: var(--bg-card);
  box-shadow: var(--sh-card-hover), 0 0 0 1px var(--lime-border);
}
.pricing-popular {
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: var(--lime); border-radius: var(--r-xl) var(--r-xl) 0 0;
}
.pricing-popular-label {
  position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
  font-size: .65rem; font-weight: var(--fw-bold); letter-spacing: .06em;
  text-transform: uppercase;
  background: var(--lime); color: #0F1115;
  padding: .25rem .8rem; border-radius: var(--r-full);
  white-space: nowrap;
}
.pricing-plan { font-size: var(--t-xs); font-weight: var(--fw-semibold); letter-spacing: .07em; text-transform: uppercase; color: var(--text-3); margin-bottom: var(--sp-4); }
.pricing-amount {
  font-size: var(--t-5xl); font-weight: var(--fw-black); letter-spacing: -.04em;
  color: var(--text); line-height: 1;
}
.pricing-amount sup { font-size: var(--t-2xl); font-weight: var(--fw-bold); vertical-align: top; margin-top: .4rem; }
.pricing-amount sub { font-size: var(--t-sm); font-weight: var(--fw-medium); color: var(--text-3); vertical-align: baseline; }
.pricing-desc { font-size: var(--t-sm); color: var(--text-3); margin: var(--sp-3) 0 var(--sp-6); }
.pricing-divider { border: none; border-top: 1px solid var(--border); margin: var(--sp-6) 0; }
.pricing-features { list-style: none; padding: 0; margin: 0 0 var(--sp-8); display: flex; flex-direction: column; gap: var(--sp-3); }
.pricing-features li {
  display: flex; align-items: flex-start; gap: var(--sp-3);
  font-size: var(--t-sm); color: var(--text-2);
}
.pricing-features li::before {
  content: '✓';
  flex-shrink: 0; width: 18px; height: 18px;
  background: var(--success-bg); color: var(--success);
  border-radius: 50%; font-size: .65rem; font-weight: 900;
  display: flex; align-items: center; justify-content: center;
  margin-top: 1px;
}

/* ─ FAQ ──────────────────────────────────────────────────────── */
.faq-item { border-bottom: 1px solid var(--border); }
.faq-trigger {
  width: 100%; display: flex; align-items: center;
  justify-content: space-between; gap: var(--sp-4);
  padding: var(--sp-5) 0; background: none; border: none;
  cursor: pointer; text-align: left; transition: color var(--t-base-d);
}
.faq-trigger .fq {
  font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text);
}
.faq-trigger .fi {
  width: 22px; height: 22px; border-radius: 50%;
  background: var(--bg-subtle); border: 1px solid var(--border-md);
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem; color: var(--text-3); flex-shrink: 0;
  transition: transform var(--t-base-d), background var(--t-base-d);
}
.faq-trigger[aria-expanded="true"] .fi {
  transform: rotate(45deg); background: var(--lime-dim); color: var(--lime);
}
.faq-trigger:hover .fq { color: var(--accent); }
.faq-ans {
  font-size: var(--t-sm); color: var(--text-3); line-height: 1.8;
  padding-bottom: var(--sp-5); display: none;
}
.faq-trigger[aria-expanded="true"] + .faq-ans { display: block; }

/* ─ Trust badges ─────────────────────────────────────────────── */
.trust-badge {
  display: flex; flex-direction: column; align-items: center; gap: var(--sp-3);
  padding: var(--sp-6);
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); box-shadow: var(--sh-card);
  text-align: center;
}
.trust-badge-icon {
  width: 48px; height: 48px; border-radius: var(--r-md);
  display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
}
.trust-badge-label {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  color: var(--text); line-height: 1.3;
}
.trust-badge-sub { font-size: var(--t-xs); color: var(--text-3); }

/* ─ Insurer pills ────────────────────────────────────────────── */
.ip {
  display: inline-flex; align-items: center;
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-full); padding: var(--sp-2) var(--sp-4);
  font-size: var(--t-sm); font-weight: var(--fw-medium); color: var(--text-2);
  box-shadow: var(--sh-xs);
  transition: box-shadow var(--t-base-d), transform var(--t-base-d);
}
.ip:hover { box-shadow: var(--sh-md); transform: translateY(-1px); }

/* ─ Scroll-triggered fade-up ─────────────────────────────────── */
.will-animate {
  opacity: 0; transform: translateY(18px);
  transition: opacity var(--t-slow) var(--ease), transform var(--t-slow) var(--ease);
}
.will-animate.in-view { opacity: 1; transform: none; }

/* ─ Responsive ───────────────────────────────────────────────── */
@media (max-width: 768px) {
  .hero { padding: var(--sp-16) 0 var(--sp-12); }
  .sec  { padding: var(--sp-16) 0; }
  .stat-col { border-right: none; border-bottom: 1px solid rgba(255,255,255,.06); }
  .stat-col:last-child { border-bottom: none; }
  .step-body { padding-bottom: var(--sp-6); }
  .pricing-card { padding: var(--sp-6); }
}

@media (max-width: 480px) {
  .hero-trust { gap: var(--sp-3); }
  .trust-sep  { display: none; }
  .hero-actions { flex-direction: column; align-items: flex-start; }
  .btn-hero-ghost { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════════ --}}
<section class="hero" aria-label="Encabezado principal">
  <div class="hero-bg"   aria-hidden="true"></div>
  <div class="hero-grid" aria-hidden="true"></div>
  <div class="hero-rel">
    <div class="container">
      <div class="row align-items-center g-5">

        {{-- Left column --}}
        <div class="col-lg-6">
          <div class="hero-badge fade-up">
            <span class="hero-badge-dot" aria-hidden="true"></span>
            Software para asesorías · España · Análisis jurídico
          </div>

          <h1 class="fade-up delay-1">
            Tu asesoría tramita más casos.<br>
            <em>El sistema redacta, tú lo firmas.</em>
          </h1>

          <p class="hero-sub fade-up delay-2">
            Genera reclamaciones formales con base legal española en segundos.
            Baremo 2024, jurisprudencia CENDOJ y firma eIDAS integrados.
            Sin horas de redacción, sin errores de normativa.
          </p>

          <div class="hero-actions fade-up delay-3">
            <a href="{{ route('register') }}" class="btn btn-primary btn-xl">
              Probar gratis 14 días →
            </a>
            <a href="#como-funciona" class="btn-hero-ghost">
              Ver cómo funciona
            </a>
          </div>

          <div class="hero-trust fade-up delay-4" aria-label="Garantías y certificaciones">
            <div class="trust-item">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <strong>Ley 35/2015</strong>
              <span>actualizada 2024</span>
            </div>
            <div class="trust-sep" aria-hidden="true"></div>
            <div class="trust-item">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              <strong>RGPD</strong>
              <span>compliant</span>
            </div>
            <div class="trust-sep" aria-hidden="true"></div>
            <div class="trust-item">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
              <strong>1.200+</strong>
              <span>asesorías activas</span>
            </div>
            <div class="trust-sep" aria-hidden="true"></div>
            <div class="trust-item">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <strong>6h/semana</strong>
              <span>ahorradas por asesoría</span>
            </div>
          </div>
        </div>

        {{-- Right column — preview card --}}
        <div class="col-lg-6 d-none d-lg-block fade-up delay-2" aria-hidden="true">
          <div class="hero-preview">
            <div class="preview-header">
              <div class="preview-status-dot">✓</div>
              <div>
                <div class="preview-title">Expediente generado</div>
                <div class="preview-sub">Hace 2 min · Asesoría López & Cía</div>
              </div>
              <span class="preview-badge-ok">COMPLETADO</span>
            </div>
            <div class="preview-row"><span class="pk">Cliente / Asegurado</span><span class="pv">Hernández, P.</span></div>
            <div class="preview-row"><span class="pk">Aseguradora</span><span class="pv">MAPFRE Hogar</span></div>
            <div class="preview-row"><span class="pk">Base legal</span><span class="pv">Art. 18 LCS · Circ. 1/2017 DGSFP</span></div>
            <div class="preview-row"><span class="pk">Importe reclamado</span><span class="pv">3.240 €</span></div>
            <div class="preview-row"><span class="pk">Estado</span><span class="pv" style="color:#4ade80">Enviada · Baremo aplicado</span></div>
            <div class="preview-actions">
              <div class="preview-btn">Word ↓</div>
              <div class="preview-btn">PDF ↓</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     STATS BAR
     ═══════════════════════════════════════════════════════════ --}}
<section class="stats-bar" aria-label="Estadísticas del servicio">
  <div class="container">
    <div class="row g-0">
      <div class="col-6 col-md-3"><div class="stat-col"><span class="sv">1.200+</span><span class="sl">Asesorías de seguros activas</span></div></div>
      <div class="col-6 col-md-3"><div class="stat-col"><span class="sv">6h/sem</span><span class="sl">Ahorradas por asesoría</span></div></div>
      <div class="col-6 col-md-3"><div class="stat-col"><span class="sv">&lt; 90s</span><span class="sl">Por expediente generado</span></div></div>
      <div class="col-6 col-md-3"><div class="stat-col"><span class="sv">94%</span><span class="sl">Tasa de respuesta aseguradora</span></div></div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     INSURANCE TYPES
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-light" aria-labelledby="tipos-h">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Coberturas</span>
      <h2 id="tipos-h">Todos los seguros que tramita tu asesoría</h2>
      <p>El sistema identifica automáticamente la normativa aplicable a cada tipo de póliza y genera la reclamación con los artículos exactos.</p>
    </div>
    <div class="row g-4">
      @foreach([
        ['🏠', 'rgba(59,130,246,.15)',  'Hogar',               'Daños por agua, incendio, robo, responsabilidad civil del propietario.'],
        ['🚗', 'rgba(59,130,246,.15)',  'Auto',                 'Accidentes, daños propios, robo, defensa jurídica vial.'],
        ['🏥', 'rgba(34,197,94,.15)',   'Salud',                'Retrasos en atención, cobertura denegada, reembolsos pendientes.'],
        ['❤️', 'rgba(248,113,113,.15)', 'Vida',                 'Reclamación de capital asegurado a beneficiarios.'],
        ['✈️', 'rgba(59,130,246,.15)',  'Viaje',                'Cancelación, retraso, pérdida de equipaje, asistencia médica.'],
        ['⚖️', 'rgba(34,197,94,.15)',   'Responsabilidad Civil', 'Reclamaciones por daños a terceros cubiertas por la póliza.'],
        ['🏪', 'rgba(251,191,36,.15)',  'Comercio',             'Negocio parado, robo, daños a instalaciones y mercancías.'],
        ['⚰️', 'rgba(113,113,122,.12)', 'Decesos',              'Incumplimiento de prestaciones funerarias pactadas.'],
      ] as [$icon, $bg, $name, $desc])
      <div class="col-6 col-md-4 col-lg-3 will-animate">
        <div class="ins-card">
          <div class="ins-icon" style="background:{{ $bg }}" aria-hidden="true">{{ $icon }}</div>
          <h3>{{ $name }}</h3>
          <p>{{ $desc }}</p>
        </div>
      </div>
      @endforeach
    </div>
    <p class="text-center mt-5" style="font-size:var(--t-sm);color:var(--text-3)">
      ¿No ves tu tipo de seguro? <a href="mailto:hola@Reclama.es">Contáctanos</a> — lo añadimos.
    </p>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     HOW IT WORKS
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-alt" id="como-funciona" aria-labelledby="how-h">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Cómo funciona</span>
      <h2 id="how-h">Tres pasos. Tu asesoría cierra el caso hoy.</h2>
      <p>Un flujo pensado para el equipo de una asesoría: rápido, preciso, con base legal impecable.</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-7 col-xl-6">

        <div class="step will-animate">
          <div class="step-line">
            <div class="step-num" aria-hidden="true">1</div>
            <div class="step-connector" aria-hidden="true"></div>
          </div>
          <div class="step-body">
            <div class="step-tag">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Formulario
            </div>
            <h3>Introduce los datos del expediente</h3>
            <p>
              El gestor de tu asesoría rellena el formulario con los datos del cliente: aseguradora,
              tipo de póliza, fecha del siniestro e importes. En 2 minutos, sin formación adicional.
            </p>
          </div>
        </div>

        <div class="step will-animate">
          <div class="step-line">
            <div class="step-num" aria-hidden="true">2</div>
            <div class="step-connector" aria-hidden="true"></div>
          </div>
          <div class="step-body">
            <div class="step-tag">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              Redacción automática
            </div>
            <h3>La carta se genera en segundos</h3>
            <p>
              Claude analiza el caso, aplica el baremo 2024, consulta jurisprudencia CENDOJ y redacta
              con citas exactas: LCS, Ley 35/2015, LOPD-GDD y resoluciones DGSFP vigentes.
              Tono jurídico profesional. Personalizado por aseguradora.
            </p>
          </div>
        </div>

        <div class="step will-animate">
          <div class="step-line">
            <div class="step-num" aria-hidden="true">3</div>
          </div>
          <div class="step-body">
            <div class="step-tag">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Descarga
            </div>
            <h3>Revisa, firma y entrega al cliente</h3>
            <p>
              Descarga en Word (editable) o PDF sellado. Firma digitalmente con eIDAS si el cliente
              lo precisa. El expediente queda archivado en el panel de tu asesoría para seguimiento.
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     RESULTS
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-dark" aria-labelledby="results-h">
  <div class="sec-dark-overlay" aria-hidden="true"></div>
  <div class="container" style="position:relative;z-index:1">
    <div class="sec-head dark-head will-animate">
      <span class="eyebrow" style="color:var(--b-300);background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.2)">
        Resultados reales
      </span>
      <h2 id="results-h" style="color:#fff">Lo que ganan las asesorías que usan Reclama</h2>
      <p>Datos reales de asesorías de seguros que han integrado la plataforma en su día a día.</p>
    </div>
    <div class="row g-4 mb-5">
      <div class="col-md-4 will-animate">
        <div class="result-card">
          <div class="result-label">Tiempo ahorrado</div>
          <div class="result-amount">6h/sem</div>
          <hr class="result-divider" aria-hidden="true">
          <div class="result-meta">
            <span class="result-meta-company">⚖️ Asesoría Martínez Seguros</span>
            <span class="result-meta-detail">Plan Pro · 32 expedientes/mes</span>
          </div>
        </div>
      </div>
      <div class="col-md-4 will-animate">
        <div class="result-card">
          <div class="result-label">Expedientes/mes</div>
          <div class="result-amount">+40%</div>
          <hr class="result-divider" aria-hidden="true">
          <div class="result-meta">
            <span class="result-meta-company">📋 Corredoría López & Cía</span>
            <span class="result-meta-detail">Mismo equipo · Más casos</span>
          </div>
        </div>
      </div>
      <div class="col-md-4 will-animate">
        <div class="result-card">
          <div class="result-label">ROI primer mes</div>
          <div class="result-amount">8,3×</div>
          <hr class="result-divider" aria-hidden="true">
          <div class="result-meta">
            <span class="result-meta-company">🚀 Gestiones Ibérica SL</span>
            <span class="result-meta-detail">vs. redacción manual</span>
          </div>
        </div>
      </div>
    </div>
    <div class="result-counter will-animate">
      <div class="result-counter-val">12.480.000 €</div>
      <div class="result-counter-label">Total recuperado para los clientes de asesorías que usan Reclama</div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FEATURES GRID
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-light" aria-labelledby="feat-h">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Funcionalidades</span>
      <h2 id="feat-h">Todo lo que necesita tu asesoría en un solo panel</h2>
      <p>Del baremo de tráfico a la firma digital eIDAS. Sin cambiar de herramienta, sin perder tiempo.</p>
    </div>
    <div class="row g-4">
      @foreach([
        ['🤖', 'rgba(59,130,246,.15)',  'rgba(59,130,246,.3)',  'Redacción profesional', 'Genera expedientes con tono jurídico profesional y normativa exacta. Cada carta personalizada por aseguradora y tipo de póliza.'],
        ['⚖️', 'rgba(59,130,246,.15)',  'rgba(59,130,246,.3)',  'Baremo 2024 integrado',    'Cálculo automático de indemnizaciones por la Ley 35/2015 y Resolución DGS 2024. Resultado con desglose para el cliente.'],
        ['📄', 'rgba(251,191,36,.15)',  'rgba(251,191,36,.3)',  'OCR de pólizas y partes',  'Sube el PDF del asegurado y extrae los datos clave: coberturas, franquicia, cláusulas, fechas. Sin teclear nada.'],
        ['📚', 'rgba(34,197,94,.15)',   'rgba(34,197,94,.3)',   'Jurisprudencia CENDOJ',    'Sentencias del TS y AP aplicables al caso, listas para incluir en la carta. Argumento legal reforzado en segundos.'],
        ['🖋️', 'rgba(34,197,94,.15)',   'rgba(34,197,94,.3)',   'Firma digital eIDAS',      'Tu cliente firma poderes y autorizaciones con validez legal plena en toda la UE. Sin desplazamientos, sin papel.'],
        ['🚗', 'rgba(168,85,247,.15)',  'rgba(168,85,247,.3)',  'Tasación vehicular',       'Valoración de daños DAT Ibérica, Audatex o GT Estimate integrada. Importe objetivo para la carta de reclamación.'],
      ] as [$icon, $bg, $border, $title, $desc])
      <div class="col-md-6 col-lg-4 will-animate">
        <div class="fcard">
          <div class="fcard-icon" style="background:{{ $bg }};border:1px solid {{ $border }}" aria-hidden="true">
            {{ $icon }}
          </div>
          <h3>{{ $title }}</h3>
          <p>{{ $desc }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     TESTIMONIALS
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-alt" aria-labelledby="test-h">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Testimonios</span>
      <h2 id="test-h">Lo que dicen las asesorías que ya usan Reclama</h2>
    </div>
    <div class="row g-4">
      @foreach([
        ['★★★★★', '"Antes tardábamos 45 minutos en redactar una carta con la normativa correcta. Ahora lo hacemos en 90 segundos. Hemos doblado el número de expedientes mensuales sin contratar a nadie."', 'Carlos R.', 'Director · Asesoría Martínez Seguros', '6h/semana ahorradas'],
        ['★★★★★', '"El baremo 2024 integrado y la consulta de sentencias CENDOJ nos dan una ventaja enorme frente a las aseguradoras. Nuestros clientes notan la diferencia en la calidad de las cartas."',   'Elena P.',  'Gestora sénior · Corredoría NorteSegur', '+40% expedientes/mes'],
        ['★★★★★', '"Probamos otras herramientas similares y ninguna citaba la normativa española correctamente. Reclama es la única que funciona con la LCS y las circulares de la DGSFP actualizadas."',     'Javier M.', 'Socio fundador · López & Asociados Correduría', 'ROI 8× en 30 días'],
      ] as [$stars, $quote, $author, $role, $amount])
      <div class="col-md-4 will-animate">
        <div class="tcard">
          <div class="tcard-stars" aria-label="Valoración: 5 de 5 estrellas">{{ $stars }}</div>
          <blockquote>{{ $quote }}</blockquote>
          <div class="tcard-footer">
            <div>
              <div class="tcard-author">{{ $author }}</div>
              <div class="tcard-role">{{ $role }}</div>
            </div>
            <div class="tcard-amount">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 12 2 2 4-4"/></svg>
              {{ $amount }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     PRICING
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-light" id="precios" aria-labelledby="pricing-h">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Precios</span>
      <h2 id="pricing-h">Precios para asesorías de todos los tamaños</h2>
      <p>Sin permanencia, sin sorpresas. Cancela cuando quieras. Prueba gratis 14 días.</p>
    </div>
    <div class="row g-4 justify-content-center">

      {{-- Basic --}}
      <div class="col-md-5 col-lg-4 will-animate">
        <div class="pricing-card">
          <div class="pricing-plan">Por expediente</div>
          <div class="pricing-amount">
            <sup>€</sup>9,99<sub>/exp.</sub>
          </div>
          <p class="pricing-desc">Ideal para asesorías con volumen bajo o para empezar sin compromiso.</p>
          <hr class="pricing-divider">
          <ul class="pricing-features">
            <li>Carta formal con base legal</li>
            <li>Descarga en Word y PDF</li>
            <li>Normativa LCS + Ley 35/2015</li>
            <li>Baremo de tráfico público</li>
            <li>Soporte por email</li>
          </ul>
          <a href="{{ route('claim.create') }}" class="btn btn-secondary w-100 btn-lg">
            Generar expediente
          </a>
        </div>
      </div>

      {{-- Pro --}}
      <div class="col-md-5 col-lg-4 will-animate">
        <div class="pricing-card featured">
          <div class="pricing-popular">Recomendado para asesorías</div>
          <div class="pricing-plan">Asesoría Pro</div>
          <div class="pricing-amount">
            <sup>€</sup>29,99<sub>/mes</sub>
          </div>
          <p class="pricing-desc">Expedientes ilimitados + todas las herramientas profesionales.</p>
          <hr class="pricing-divider">
          <ul class="pricing-features">
            <li>Expedientes ilimitados</li>
            <li>OCR de pólizas y partes médicos</li>
            <li>Jurisprudencia CENDOJ automática</li>
            <li>Tasación vehicular (DAT, Audatex)</li>
            <li>Firma digital eIDAS para clientes</li>
            <li>Baremo 2024 con desglose</li>
            <li>Escalada a DGSFP en 1 clic</li>
            <li>Soporte prioritario por Slack</li>
          </ul>
          <a href="{{ route('subscription.plans') }}" class="btn btn-primary w-100 btn-lg">
            Probar 14 días gratis →
          </a>
          <p style="text-align:center;font-size:var(--t-xs);color:var(--text-3);margin-top:var(--sp-3)">
            Sin permanencia · Cancela cuando quieras
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FAQ
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-alt" id="faq" aria-labelledby="faq-h">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="sec-head will-animate">
          <span class="eyebrow">FAQ</span>
          <h2 id="faq-h">Preguntas frecuentes</h2>
        </div>
        @foreach([
          ['¿Las cartas tienen validez legal para presentar ante la DGSFP?',
           'Sí. Citamos artículos exactos de la LCS, LOPD-GDD, Ley 35/2015, LGDCU y resoluciones DGSFP vigentes. El tono jurídico profesional es apto para el Defensor del Asegurado, la DGSFP y vía judicial.'],
          ['¿Cuántos gestores de mi asesoría pueden usar la cuenta?',
           'El Plan Asesoría Pro permite acceso a todo el equipo bajo una sola suscripción. Cada expediente queda asociado al gestor que lo creó para un seguimiento interno correcto.'],
          ['¿Se integra con nuestro software de gestión actual?',
           'Los expedientes se exportan en Word y PDF. Estamos desarrollando integración con los principales CRMs de corredurías. Escríbenos y añadimos tu herramienta a la hoja de ruta.'],
          ['¿Cómo protegéis los datos de nuestros clientes?',
           'Conforme al RGPD y LOPD-GDD española. La asesoría actúa como responsable del tratamiento de sus clientes. Los documentos están cifrados en tránsito y en reposo. Podemos firmar DPA.'],
          ['¿Funciona para todos los ramos?',
           'Hogar, auto, salud, vida, viaje, decesos, responsabilidad civil, comercio y más. El sistema identifica la normativa aplicable a cada ramo y personaliza la carta según la aseguradora.'],
          ['¿Qué pasa si la aseguradora no responde en plazo?',
           'El Plan Pro incluye carta de escalada a la DGSFP a los 30 días, con base en el art. 104 LCS. El silencio de la aseguradora tiene consecuencias legales y la DGSFP tiene poder sancionador.'],
        ] as [$q, $a])
        <div class="faq-item will-animate">
          <button class="faq-trigger" aria-expanded="false"
            onclick="(function(b){var e=b.getAttribute('aria-expanded')==='true';b.setAttribute('aria-expanded',String(!e));b.nextElementSibling.style.display=e?'none':'block';})(this)">
            <span class="fq">{{ $q }}</span>
            <span class="fi" aria-hidden="true">+</span>
          </button>
          <div class="faq-ans">{{ $a }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     TRUST SECTION
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-light" aria-labelledby="trust-h">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Seguridad &amp; cumplimiento</span>
      <h2 id="trust-h" style="font-size:var(--t-2xl)">Tu información está protegida</h2>
    </div>
    <div class="row g-4 justify-content-center">
      @foreach([
        ['🔒', 'rgba(59,130,246,.15)',  'RGPD compliant',     'Tus datos tratados conforme al Reglamento Europeo de Protección de Datos.'],
        ['📋', 'rgba(113,113,122,.12)', 'LSSI-CE',            'Cumplimiento de la Ley de Servicios de la Sociedad de la Información.'],
        ['🔐', 'rgba(34,197,94,.15)',   'Cifrado SSL/TLS',    'Comunicación cifrada de extremo a extremo con certificado SSL de 256 bits.'],
        ['💳', 'rgba(182,230,46,.12)',  'Pago seguro Stripe', 'Pagos procesados por Stripe. No almacenamos datos de tarjeta.'],
      ] as [$icon, $iconbg, $label, $sub])
      <div class="col-6 col-md-3 will-animate">
        <div class="trust-badge">
          <div class="trust-badge-icon" style="background:{{ $iconbg }}" aria-hidden="true">{{ $icon }}</div>
          <div class="trust-badge-label">{{ $label }}</div>
          <div class="trust-badge-sub">{{ $sub }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     SEO INTERLINKING — tipos de reclamación
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec" aria-labelledby="reclamaciones-h" style="background:var(--s-900)">
  <div class="container">
    <div class="sec-head will-animate">
      <span class="eyebrow">Guías de reclamación</span>
      <h2 id="reclamaciones-h">Reclamaciones contra aseguradoras sin complicaciones</h2>
      <p class="sec-sub">
        Guías completas con base legal para reclamar por <strong>seguros de hogar</strong>, <strong>coche</strong>, <strong>vida</strong>, <strong>salud</strong>,
        <strong>desastres naturales</strong> y para encontrar <strong>seguros de fallecidos no reclamados</strong>.
        Normativa LCS actualizada, plazos y casos reales.
      </p>
    </div>
    <div class="row g-3 will-animate">
      @foreach([
        ['🏠','Reclamar seguro de hogar',route('seo.hogar'),'Daños por agua, incendio, robo, goteras y cobertura denegada'],
        ['🚗','Reclamar seguro de coche',route('seo.coche'),'Accidentes, peritación insuficiente y pérdida total'],
        ['❤️','Reclamar seguro de vida',route('seo.vida'),'Fallecimiento, invalidez y beneficiarios que no cobran'],
        ['🔍','Seguros de fallecidos',route('seo.fallecidos'),'Seguros de vida no reclamados: cómo encontrarlos'],
        ['🌪️','Daños por DANA e inundaciones',route('seo.desastres'),'Seguro privado y Consorcio de Compensación de Seguros'],
        ['⚖️','Ver todas las reclamaciones',route('seo.reclamaciones'),'Hub completo con todos los tipos de siniestro'],
      ] as [$icon, $title, $route, $desc])
      <div class="col-sm-6 col-lg-4">
        <a href="{{ $route }}" style="display:flex;gap:var(--sp-4);padding:var(--sp-5);background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-lg);text-decoration:none;transition:border-color var(--t-base-d),transform var(--t-base-d)" onmouseover="this.style.borderColor='var(--lime-border)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
          <span style="font-size:1.25rem;flex-shrink:0;margin-top:.1rem">{{ $icon }}</span>
          <div>
            <div style="font-size:var(--t-sm);font-weight:var(--fw-semibold);color:var(--text);margin-bottom:var(--sp-1)">{{ $title }}</div>
            <div style="font-size:var(--t-xs);color:var(--text-3)">{{ $desc }}</div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
    <div class="text-center mt-4 will-animate">
      <a href="{{ route('blog.index') }}" style="font-size:var(--t-sm);color:var(--text-3);text-decoration:none">
        Ver también: <span style="color:var(--lime)">Blog de reclamaciones →</span>
      </a>
      <span style="color:var(--text-4);margin:0 var(--sp-4)">·</span>
      <a href="{{ route('guias.index') }}" style="font-size:var(--t-sm);color:var(--text-3);text-decoration:none">
        <span style="color:var(--lime)">Guías completas →</span>
      </a>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FINAL CTA
     ═══════════════════════════════════════════════════════════ --}}
<section class="sec sec-dark" aria-label="Llamada a la acción final">
  <div class="sec-dark-overlay" aria-hidden="true"></div>
  <div class="container text-center" style="position:relative;z-index:1">
    <div class="will-animate">
      <span class="eyebrow" style="color:var(--b-300);background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.2);margin-bottom:var(--sp-6);display:inline-flex">
        14 días gratis · Sin tarjeta
      </span>
      <h2 style="color:#fff;font-size:clamp(1.75rem,4vw,2.875rem);margin-bottom:var(--sp-4);max-width:600px;margin-inline:auto;letter-spacing:-.03em">
        Tu asesoría, más eficiente desde el primer día
      </h2>
      <p style="color:var(--s-400);font-size:var(--t-base);margin-bottom:var(--sp-8);max-width:460px;margin-inline:auto">
        Prueba gratis 14 días · Sin permanencia · Expedientes ilimitados en el plan Pro
      </p>
      <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:var(--sp-3)">
        <a href="{{ route('register') }}" class="btn btn-primary btn-xl">
          Empezar gratis 14 días →
        </a>
        <a href="#precios" class="btn-hero-ghost">
          Ver precios
        </a>
      </div>
      <p style="font-size:var(--t-xs);color:var(--s-600);margin-top:var(--sp-6)">
        Garantía de satisfacción · Reembolso si hay error técnico ·
        <a href="{{ route('legal.reembolso') }}" style="color:var(--s-500)">Política de reembolso</a>
      </p>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
// Scroll-triggered fade-up animations
(function() {
  if (!('IntersectionObserver' in window)) {
    document.querySelectorAll('.will-animate').forEach(function(el) {
      el.classList.add('in-view');
    });
    return;
  }
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.will-animate').forEach(function(el) {
    observer.observe(el);
  });
})();
</script>
@endpush
