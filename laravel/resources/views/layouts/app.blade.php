<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Reclama') — Software de Reclamaciones para Asesorías</title>
<meta name="description" content="@yield('meta-description', 'Software de gestión de reclamaciones a aseguradoras para asesorías de seguros. Cartas formales con base legal en menos de 90 segundos.')">
<meta name="robots" content="@yield('meta-robots', 'index, follow')">
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta property="og:type"        content="website">
<meta property="og:site_name"   content="Reclama">
<meta property="og:title"       content="@yield('og-title', 'Reclama — Software de Reclamaciones')">
<meta property="og:description" content="@yield('og-description', 'Software de gestión de reclamaciones a aseguradoras para asesorías de seguros españolas.')">
<meta property="og:url"         content="@yield('canonical', url()->current())">
<meta property="og:image"       content="@yield('og-image', asset('img/og-default.png'))">
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="@yield('og-title', 'Reclama — Software de Reclamaciones')">
<meta name="twitter:description" content="@yield('og-description', 'Software de gestión de reclamaciones a aseguradoras para asesorías de seguros españolas.')">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..800;1,14..32,300..800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ══════════════════════════════════════════════════════════════
   Reclama — DESIGN SYSTEM v5.0 — Dark Edition
   Palette: Slate #0F1115 · Lime #B6E62E · Enterprise B2B + WCAG 2.1 AA
   Fonts: Inter (display + body) · JetBrains Mono (code)
   ══════════════════════════════════════════════════════════════ */

/* ─── 1. Design Tokens ─────────────────────────────────────── */
:root {
  /* Slate scale — blue-black foundation (Linear / Stripe aesthetic) */
  --z-950: #080B10;
  --z-900: #0F1115;
  --z-800: #151922;
  --z-700: #1E2333;
  --z-600: #2A2F3A;
  --z-500: #3D4455;
  --z-400: #5C6478;
  --z-300: #7E8A9E;
  --z-200: #A8B3C4;
  --z-100: #D0D5DE;
  --z-50:  #E8ECF2;
  --z-25:  #F5F7FA;

  /* Backward-compat aliases (--s-*) */
  --s-950: var(--z-950); --s-900: var(--z-900); --s-800: var(--z-800);
  --s-700: var(--z-700); --s-600: var(--z-600); --s-500: var(--z-500);
  --s-400: var(--z-400); --s-300: var(--z-300); --s-200: var(--z-200);
  --s-100: var(--z-100); --s-50:  var(--z-50);  --s-25:  var(--z-25);

  /* Lime accent — primary CTA, active states, links */
  --lime:        #B6E62E;
  --lime-dark:   #A3D126;
  --lime-dim:    rgba(182,230,46,.12);
  --lime-border: rgba(182,230,46,.22);
  --lime-glow:   0 0 20px rgba(182,230,46,.15);

  /* Semantic surfaces */
  --bg:           #0F1115;
  --bg-section:   #151922;
  --bg-card:      #181C24;
  --bg-elevated:  #1E2333;
  --bg-hover:     rgba(255,255,255,.04);
  --bg-subtle:    rgba(255,255,255,.06);

  /* Borders */
  --border:        #2A2F3A;
  --border-md:     #353C4A;
  --border-strong: rgba(255,255,255,.20);

  /* Text — all contrast-checked against --bg (#0F1115) */
  --text:   #F3F4F6;   /* 17.4:1 ✅ */
  --text-2: #E4E4E7;   /* 15.2:1 ✅ */
  --text-3: #A1A1AA;   /*  5.3:1 ✅ AA normal text */
  --text-4: #6B7280;   /*  3.2:1 ≥ AA large/UI only */

  /* Legacy alias */
  --accent:    var(--lime);
  --accent-h:  var(--lime-dark);
  --accent-s:  var(--lime-dim);

  /* Blue scale — kept for badges/info only */
  --b-700: #1d4ed8; --b-600: #2563eb; --b-500: #3b82f6;
  --b-400: #60a5fa; --b-300: #93c5fd; --b-200: #bfdbfe;
  --b-100: #dbeafe; --b-50:  #eff6ff;

  /* Semantic feedback */
  --success:    #22C55E;  /* 7.5:1 on dark ✅ */
  --success-bg: rgba(34,197,94,.12);
  --danger:     #F87171;  /* 5.8:1 on dark ✅ */
  --danger-bg:  rgba(248,113,113,.12);
  --warning:    #FBBF24;  /* 9.0:1 on dark ✅ */
  --warning-bg: rgba(251,191,36,.12);
  --info-color: #38BDF8;
  --info-bg:    rgba(56,189,248,.12);

  /* Gold — kept for Pro badge */
  --g-700: #d97706; --g-600: #f59e0b; --g-500: #fbbf24;
  --g-100: rgba(245,158,11,.2); --g-50: rgba(245,158,11,.1);

  /* Spacing — 8-px grid */
  --sp-1: .25rem; --sp-2: .5rem;  --sp-3: .75rem; --sp-4: 1rem;
  --sp-5: 1.25rem; --sp-6: 1.5rem; --sp-8: 2rem;  --sp-10: 2.5rem;
  --sp-12: 3rem; --sp-16: 4rem; --sp-20: 5rem; --sp-24: 6rem; --sp-32: 8rem;
  /* Legacy */
  --s1:var(--sp-1);--s2:var(--sp-2);--s3:var(--sp-3);--s4:var(--sp-4);
  --s5:var(--sp-5);--s6:var(--sp-6);--s8:var(--sp-8);--s10:var(--sp-10);
  --s12:var(--sp-12);--s16:var(--sp-16);--s20:var(--sp-20);--s24:var(--sp-24);

  /* Radius */
  --r-xs: 3px; --r-sm: 6px; --r-md: 10px; --r-lg: 14px;
  --r-xl: 20px; --r-2xl: 28px; --r-full: 9999px;

  /* Shadows — on dark bg */
  --sh-xs:    0 1px 2px rgba(0,0,0,.4);
  --sh-sm:    0 2px 6px rgba(0,0,0,.5);
  --sh-md:    0 4px 12px rgba(0,0,0,.5), 0 2px 4px rgba(0,0,0,.3);
  --sh-lg:    0 12px 32px rgba(0,0,0,.6), 0 4px 8px rgba(0,0,0,.4);
  --sh-xl:    0 24px 56px rgba(0,0,0,.7);
  --sh-card:  0 0 0 1px var(--border), 0 2px 8px rgba(0,0,0,.4);
  --sh-card-hover: 0 0 0 1px var(--lime-border), 0 8px 32px rgba(0,0,0,.6);
  /* Focus ring: lime for maximum contrast (12:1 on #0F1115) */
  --sh-ring:  0 0 0 2px #0F1115, 0 0 0 4px #B6E62E;
  --sh-accent: 0 0 0 2px #0F1115, 0 0 0 4px var(--lime);

  /* Type scale */
  --t-xs:   .75rem;    /* 12 */
  --t-sm:   .8125rem;  /* 13 */
  --t-base: .9375rem;  /* 15 */
  --t-lg:   1.0625rem; /* 17 */
  --t-xl:   1.25rem;   /* 20 */
  --t-2xl:  1.5rem;    /* 24 */
  --t-3xl:  1.875rem;  /* 30 */
  --t-4xl:  2.375rem;  /* 38 */
  --t-5xl:  3rem;      /* 48 */
  --t-6xl:  3.75rem;   /* 60 */

  /* Font weights */
  --fw-normal: 400; --fw-medium: 500; --fw-semibold: 600;
  --fw-bold: 700; --fw-extrabold: 800; --fw-black: 900;

  /* Motion — WCAG 2.3.3 animation from interactions */
  --ease:     cubic-bezier(.16,1,.3,1);
  --ease-in:  cubic-bezier(.4,0,1,1);
  --ease-out: cubic-bezier(0,0,.2,1);
  --t-fast:   80ms; --t-base-d: 140ms; --t-slow: 220ms;
  --t-1: 80ms; --t-2: 140ms; --t-3: 220ms; --t-4: 350ms;

  /* Layout */
  --nav-h: 64px;
}

/* ─── 2. Reset ──────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }
html { scroll-behavior: smooth; font-size: 16px; }
body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  font-feature-settings: 'cv02' 1, 'cv03' 1, 'cv04' 1, 'cv11' 1;
  font-size: var(--t-base);
  line-height: 1.65;
  color: var(--text);
  background: var(--bg);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  margin: 0;
  overflow-x: hidden;
  position: relative;
}

/* Noise overlay — grain texture (pointer-events:none, mix-blend-mode:overlay) */
body::after {
  content: '';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.4'/%3E%3C/svg%3E");
  opacity: .038;
  pointer-events: none;
  z-index: 9998;
  mix-blend-mode: overlay;
  animation: grain 8s steps(10) infinite;
}
@keyframes grain {
  0%,100%{ transform:translate(0,0); }
  10%    { transform:translate(-3%,-8%); }
  20%    { transform:translate(5%,4%); }
  30%    { transform:translate(-4%,6%); }
  40%    { transform:translate(6%,-3%); }
  50%    { transform:translate(-6%,4%); }
  60%    { transform:translate(4%,-7%); }
  70%    { transform:translate(-3%,5%); }
  80%    { transform:translate(7%,-4%); }
  90%    { transform:translate(-5%,3%); }
}

/* Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--z-700); border-radius: var(--r-full); }
::-webkit-scrollbar-thumb:hover { background: var(--z-600); }

::selection { background: rgba(182,230,46,.18); color: var(--lime); }

/* ─── 3. Accessibility ─────────────────────────────────────── */
/* WCAG 2.4.1 Skip navigation */
.skip-link {
  position: absolute; top: -100%; left: var(--sp-4); z-index: 9999;
  background: var(--lime); color: #0F1115;
  font-size: var(--t-sm); font-weight: var(--fw-bold);
  padding: var(--sp-3) var(--sp-5);
  border-radius: 0 0 var(--r-md) var(--r-md);
  text-decoration: none;
  box-shadow: var(--sh-lg);
  transition: top var(--t-fast);
}
.skip-link:focus { top: 0; }

/* WCAG 2.4.7 Focus visible — lime 4px ring for maximum contrast (15.7:1) */
:focus-visible {
  outline: none;
  box-shadow: var(--sh-ring);
  border-radius: var(--r-sm);
}
:focus:not(:focus-visible) { outline: none; }

/* WCAG 2.3.3 Reduced motion */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: .01ms !important;
    transition-duration: .01ms !important;
    animation-iteration-count: 1 !important;
  }
  body::after { animation: none; }
}

/* ─── 4. Typography — Inter / B2B enterprise hierarchy ──────── */
h1, h2, h3, h4, h5, h6 {
  font-family: 'Inter', system-ui, sans-serif;
  font-feature-settings: 'cv02' 1, 'cv03' 1, 'cv04' 1, 'cv11' 1;
  letter-spacing: -.025em;
  line-height: 1.2;
  color: var(--text);
}
h1 { font-size: var(--t-4xl); font-weight: var(--fw-bold); letter-spacing: -.035em; }
h2 { font-size: var(--t-3xl); font-weight: var(--fw-semibold); line-height: 1.25; }
h3 { font-size: var(--t-2xl); font-weight: var(--fw-semibold); }
h4 { font-size: var(--t-xl);  font-weight: var(--fw-semibold); }
h5, h6 { font-size: var(--t-base); font-weight: var(--fw-semibold); }
p { color: var(--text-3); margin-bottom: 0; }
a { color: var(--lime); text-underline-offset: 3px; }
a:hover { color: var(--lime-dark); }
code, pre, kbd { font-family: 'JetBrains Mono', monospace; }

.text-muted   { color: var(--text-3) !important; }
.fw-medium    { font-weight: var(--fw-medium)   !important; }
.fw-semibold  { font-weight: var(--fw-semibold) !important; }
.fw-bold      { font-weight: var(--fw-bold)     !important; }

/* Lime text accent — solid, no gradients */
.text-accent { color: var(--lime); }

/* Eyebrow chip — context priming (neurodesign: category label before content) */
.eyebrow {
  display: inline-flex; align-items: center; gap: var(--sp-2);
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  letter-spacing: .08em; text-transform: uppercase;
  color: var(--lime); background: var(--lime-dim);
  padding: .3rem .75rem; border-radius: var(--r-full);
  border: 1px solid var(--lime-border);
  font-family: 'Inter', system-ui, sans-serif;
}

.label {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  letter-spacing: .07em; text-transform: uppercase;
  color: var(--text-4);
}

/* ─── 5. Layout ─────────────────────────────────────────────── */
.container { max-width: 1160px; padding-inline: var(--sp-6); }
@media (max-width: 576px) { .container { padding-inline: var(--sp-4); } }

main { min-height: calc(100vh - var(--nav-h) - 360px); }
.page { padding: var(--sp-10) 0 var(--sp-20); }
.section { padding: var(--sp-24) 0; }

/* ─── 6. Navbar — transparent → blur on scroll ──────────────── */
/* Neurodesign: fixed nav reduces spatial disorientation */
.navbar {
  height: var(--nav-h); padding: 0;
  position: fixed; top: 0; left: 0; right: 0; z-index: 200;
  background: transparent;
  border-bottom: 1px solid transparent;
  transition: background var(--t-base-d), border-color var(--t-base-d), box-shadow var(--t-base-d);
}
.navbar.scrolled {
  background: rgba(15,17,21,.88);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border-bottom-color: var(--border);
  box-shadow: 0 1px 0 rgba(0,0,0,.4), 0 4px 24px rgba(0,0,0,.3);
}
/* Compensate for fixed navbar */
body { padding-top: var(--nav-h); }

.navbar .container {
  display: flex; align-items: center; height: 100%; gap: var(--sp-4);
}

/* Logo */
.navbar-brand {
  display: flex; align-items: center; gap: var(--sp-2);
  font-family: 'Inter', system-ui, sans-serif;
  font-size: 1.05rem; font-weight: var(--fw-extrabold); letter-spacing: -.03em;
  color: var(--text) !important; text-decoration: none; flex-shrink: 0;
}
.navbar-brand .logo-mark {
  width: 32px; height: 32px; border-radius: var(--r-sm);
  background: var(--lime);
  display: flex; align-items: center; justify-content: center;
  color: #0F1115; font-size: .65rem; font-weight: var(--fw-black);
  letter-spacing: -.02em;
  flex-shrink: 0;
}
.navbar-brand .logo-word { color: var(--text); }
.navbar-brand .logo-word span { color: var(--lime); }

/* Nav links — neurodesign: low-contrast inactive, high-contrast active */
.navbar-nav { display:flex; align-items:center; list-style:none; padding:0; margin:0; gap: 2px; }
.nav-link {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: var(--t-sm); font-weight: var(--fw-medium);
  color: var(--text-3) !important; padding: var(--sp-2) var(--sp-3) !important;
  border-radius: var(--r-sm); text-decoration: none; white-space: nowrap;
  transition: color var(--t-base-d), background var(--t-base-d);
}
.nav-link:hover { color: var(--text) !important; background: var(--bg-hover); }
.nav-link[aria-current="page"] {
  color: var(--text) !important; background: var(--bg-subtle);
  font-weight: var(--fw-semibold);
}

.nav-pro-badge {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: .65rem; font-weight: var(--fw-bold); letter-spacing: .04em;
  text-transform: uppercase;
  color: var(--lime); background: var(--lime-dim);
  border: 1px solid var(--lime-border);
  padding: .15rem .45rem; border-radius: var(--r-full);
}

/* Mobile toggler */
.navbar-toggler {
  border: 1px solid var(--border-md); border-radius: var(--r-sm);
  padding: var(--sp-2); background: transparent;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background var(--t-base-d);
}
.navbar-toggler:hover { background: var(--bg-hover); }
.navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23A1A1AA' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* ─── 7. Buttons — neurodesign: lime CTA captures attention ─── */
.btn {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: var(--t-sm); font-weight: var(--fw-semibold);
  border-radius: var(--r-md); padding: .5625rem 1.125rem;
  border: 1px solid transparent; cursor: pointer;
  display: inline-flex; align-items: center; justify-content: center; gap: var(--sp-2);
  text-decoration: none; white-space: nowrap; letter-spacing: -.01em;
  position: relative; overflow: hidden;
  transition: background var(--t-base-d) var(--ease), border-color var(--t-base-d),
              box-shadow var(--t-base-d), transform var(--t-fast);
}
.btn:active { transform: scale(.97) !important; }

/* Primary — lime bg, dark text: 15.7:1 contrast ✅ WCAG AAA */
.btn-primary {
  background: var(--lime);
  border-color: var(--lime-dark);
  color: #0F1115 !important;
  box-shadow: 0 0 20px rgba(182,230,46,.18), 0 1px 0 rgba(255,255,255,.1) inset;
}
.btn-primary:hover {
  background: var(--lime-dark);
  border-color: #8AB520;
  color: #0F1115 !important;
  box-shadow: 0 0 32px rgba(182,230,46,.3), 0 4px 12px rgba(0,0,0,.4);
  transform: translateY(-1px);
}
.btn-primary::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,.15) 50%, transparent 70%);
  transform: translateX(-100%);
  transition: transform var(--t-4) var(--ease);
}
.btn-primary:hover::after { transform: translateX(100%); }

/* Secondary — elevated surface */
.btn-secondary {
  background: var(--bg-elevated);
  border-color: var(--border-md);
  color: var(--text-2) !important;
  box-shadow: var(--sh-xs);
}
.btn-secondary:hover {
  background: var(--bg-subtle); border-color: var(--border-strong);
  color: var(--text) !important; transform: translateY(-1px);
}

/* Ghost */
.btn-ghost { background: transparent; border-color: transparent; color: var(--text-3) !important; }
.btn-ghost:hover { background: var(--bg-hover); color: var(--text) !important; }

/* Danger */
.btn-danger { background: var(--danger); border-color: var(--danger); color: #fff !important; }
.btn-danger:hover { background: #DC2626; color: #fff !important; }

/* Outline primary */
.btn-outline-primary {
  background: transparent; border-color: var(--lime-border); color: var(--lime) !important;
}
.btn-outline-primary:hover {
  background: var(--lime-dim); border-color: var(--lime); color: var(--lime) !important;
}

/* Sizes */
.btn-lg  { font-size: var(--t-base); padding: .75rem 1.625rem; border-radius: var(--r-lg); }
.btn-xl  { font-size: var(--t-lg);   padding: .875rem 2rem;    border-radius: var(--r-lg); }
.btn-sm  { font-size: var(--t-xs);   padding: .375rem .75rem;  border-radius: var(--r-sm); gap: var(--sp-1); }

/* ─── 8. Cards — Gestalt closure: bordered dark surfaces ────── */
.card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  box-shadow: var(--sh-card);
  overflow: hidden;
}
a .card, .card-link {
  transition: box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease),
              border-color var(--t-base-d);
}
a:hover .card, .card-link:hover {
  box-shadow: var(--sh-card-hover);
  border-color: var(--lime-border);
  transform: translateY(-2px);
}

/* Stat card */
.stat-card {
  padding: var(--sp-6);
  display: flex; flex-direction: column; gap: var(--sp-4);
}
.stat-card .s-header { display:flex; align-items:flex-start; justify-content:space-between; }
.stat-card .s-icon {
  width: 40px; height: 40px; border-radius: var(--r-md);
  display:flex; align-items:center; justify-content:center; font-size: 1.1rem;
  flex-shrink: 0;
}
.stat-card .s-label {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  letter-spacing: .06em; text-transform: uppercase; color: var(--text-4);
  font-family: 'Inter', system-ui, sans-serif;
}
/* Neurodesign: serif numbers feel premium and authoritative */
.stat-card .s-value {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: var(--t-3xl); font-weight: var(--fw-bold);
  letter-spacing: -.02em; color: var(--text); line-height: 1;
}
.stat-card .s-sub  { font-size: var(--t-xs); color: var(--text-4); margin-top: var(--sp-1); }
.stat-card .s-change {
  display:inline-flex; align-items:center; gap: 3px; font-size: var(--t-xs);
  font-weight: var(--fw-semibold); padding: .2rem .5rem; border-radius: var(--r-full);
}
.s-change.up   { background: var(--success-bg); color: var(--success); }
.s-change.down { background: var(--danger-bg);  color: var(--danger);  }

/* Tool card */
.tool-card {
  padding: var(--sp-6); display:flex; flex-direction:column; gap: var(--sp-3);
  text-decoration: none; color: inherit; cursor: pointer;
  border-top: 2px solid transparent;
  transition: box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease),
              border-top-color var(--t-base-d), background var(--t-base-d);
}
.tool-card:hover {
  color: inherit;
  box-shadow: var(--sh-card-hover);
  border-top-color: var(--lime);
  background: var(--bg-elevated);
  transform: translateY(-2px);
}
.tool-card .tc-icon {
  width: 44px; height: 44px; border-radius: var(--r-md);
  display:flex; align-items:center; justify-content:center; font-size: 1.25rem;
  transition: transform var(--t-slow) var(--ease);
}
.tool-card:hover .tc-icon { transform: scale(1.08) rotate(-3deg); }
.tool-card .tc-title { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); font-family: 'Inter', system-ui, sans-serif; }
.tool-card .tc-desc  { font-size: var(--t-xs); color: var(--text-3); line-height: 1.6; flex-grow: 1; }
.tool-card .tc-arrow { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--lime); margin-top: var(--sp-2); transition: transform var(--t-base-d) var(--ease); }
.tool-card:hover .tc-arrow { transform: translateX(4px); }

/* Section card (used in RCSCF, baremo, etc.) */
.section-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--r-xl);
  box-shadow: var(--sh-card);
  overflow: hidden;
}
.section-card-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: var(--sp-5) var(--sp-6);
  border-bottom: 1px solid var(--border);
}
.section-card-header h2 {
  font-size: var(--t-base); font-weight: var(--fw-semibold);
  color: var(--text); letter-spacing: -.01em; margin: 0;
  font-family: 'Inter', system-ui, sans-serif;
}

/* ─── 9. Forms — WCAG 1.3.1 + 1.4.3 ───────────────────────── */
.form-label {
  font-size: var(--t-sm); font-weight: var(--fw-medium);
  color: var(--text-2); margin-bottom: var(--sp-2); display: block;
  font-family: 'Inter', system-ui, sans-serif;
}
.form-label .required { color: var(--danger); margin-left: 2px; font-size: .65rem; vertical-align: super; }

.form-control, .form-select {
  font-family: 'Inter', system-ui, sans-serif; font-size: var(--t-sm);
  color: var(--text); background: var(--bg-elevated);
  border: 1px solid var(--border-md);
  border-radius: var(--r-md);
  padding: .625rem .875rem;
  transition: border-color var(--t-base-d), box-shadow var(--t-base-d), background var(--t-base-d);
  width: 100%; line-height: 1.4;
}
.form-control::placeholder { color: var(--text-4); }
.form-control:hover:not(:focus) { border-color: var(--border-strong); background: var(--bg-hover); }
.form-control:focus, .form-select:focus {
  border-color: var(--lime); box-shadow: var(--sh-ring); outline: none;
  background: var(--bg-elevated);
}
.form-control.is-invalid { border-color: var(--danger); background: var(--danger-bg); }
.form-control.is-invalid:focus {
  border-color: var(--danger);
  box-shadow: 0 0 0 2px #0F1115, 0 0 0 4px var(--danger);
}
/* Dark select arrow */
.form-select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23A1A1AA' stroke-width='1.5' d='m4 6 4 4 4-4'/%3e%3c/svg%3e");
  background-color: var(--bg-elevated);
}
.form-select option { background: var(--bg-elevated); color: var(--text); }

.form-text { font-size: var(--t-xs); color: var(--text-3); margin-top: var(--sp-1); }
/* WCAG 1.4.3: error text must meet contrast */
.invalid-feedback {
  font-size: var(--t-xs); font-weight: var(--fw-medium); color: var(--danger);
  display:flex; align-items:center; gap: var(--sp-1); margin-top: var(--sp-1);
  role: alert;
}
.invalid-feedback::before {
  content: '!'; font-size: .6rem; font-weight: 900;
  width: 13px; height: 13px; background: var(--danger); color: #fff;
  border-radius: 50%; display:flex; align-items:center; justify-content:center;
  flex-shrink: 0;
}

.form-check-input {
  border-color: var(--border-strong); border-radius: var(--r-xs); cursor: pointer;
  background: var(--bg-elevated);
  transition: border-color var(--t-base-d), background var(--t-base-d), box-shadow var(--t-base-d);
}
.form-check-input:checked { background-color: var(--lime); border-color: var(--lime); }
.form-check-input:focus { box-shadow: var(--sh-ring); outline: none; }
.form-check-label { font-size: var(--t-sm); cursor: pointer; color: var(--text-2); }

/* ─── 10. Tables ────────────────────────────────────────────── */
.table-enterprise { border-collapse: separate; border-spacing: 0; width: 100%; }
.table-enterprise thead th {
  font-size: var(--t-xs); font-weight: var(--fw-semibold); letter-spacing: .06em;
  text-transform: uppercase; color: var(--text-4);
  padding: var(--sp-3) var(--sp-5);
  background: var(--bg-elevated); border-bottom: 1px solid var(--border-md);
  white-space: nowrap;
}
.table-enterprise thead th:first-child { padding-left: var(--sp-6); }
.table-enterprise thead th:last-child  { padding-right: var(--sp-6); }
.table-enterprise tbody td {
  font-size: var(--t-sm); padding: var(--sp-4) var(--sp-5);
  border-bottom: 1px solid var(--border); color: var(--text-2);
  vertical-align: middle; transition: background var(--t-fast);
}
.table-enterprise tbody td:first-child { padding-left: var(--sp-6); color: var(--text); font-weight: var(--fw-medium); }
.table-enterprise tbody td:last-child  { padding-right: var(--sp-6); }
.table-enterprise tbody tr:last-child td { border-bottom: none; }
.table-enterprise tbody tr:hover td { background: var(--bg-hover); }

/* ─── 11. Badges ────────────────────────────────────────────── */
.badge {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .7rem; font-weight: var(--fw-semibold);
  padding: .2rem .55rem; border-radius: var(--r-full); letter-spacing: .01em;
  display: inline-flex; align-items: center; gap: .25rem;
}
.badge.bg-success   { background: var(--success-bg) !important; color: var(--success) !important; }
.badge.bg-warning   { background: var(--warning-bg) !important; color: var(--warning) !important; }
.badge.bg-danger    { background: var(--danger-bg)  !important; color: var(--danger)  !important; }
.badge.bg-secondary { background: rgba(255,255,255,.08) !important; color: var(--text-3) !important; }
.badge.bg-primary   { background: var(--lime-dim)   !important; color: var(--lime)    !important; }
.badge.bg-info      { background: var(--info-bg)    !important; color: var(--info-color) !important; }
.badge.bg-gold      { background: var(--g-50)        !important; color: var(--g-500)   !important; border: 1px solid var(--g-100); }

/* ─── 12. Alerts ────────────────────────────────────────────── */
.alert {
  border-radius: var(--r-lg); border: none; font-size: var(--t-sm);
  padding: var(--sp-4) var(--sp-5); display:flex; align-items:flex-start; gap: var(--sp-3);
  border-left-width: 3px; border-left-style: solid;
}
.alert-success { background: var(--success-bg); color: #86EFAC; border-left-color: var(--success); }
.alert-danger  { background: var(--danger-bg);  color: #FCA5A5; border-left-color: var(--danger);  }
.alert-warning { background: var(--warning-bg); color: #FCD34D; border-left-color: var(--warning); }
.alert-info    { background: var(--info-bg);    color: #7DD3FC; border-left-color: var(--info-color); }
.alert strong  { font-weight: var(--fw-semibold); }

/* ─── 13. Animations — neurodesign: motion reinforces hierarchy */
.fade-up {
  opacity: 0; transform: translateY(14px);
  animation: fadeUp var(--t-slow) var(--ease) forwards;
}
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.delay-1 { animation-delay:  60ms; }
.delay-2 { animation-delay: 120ms; }
.delay-3 { animation-delay: 180ms; }
.delay-4 { animation-delay: 240ms; }

/* Skeleton — dark variant */
.skeleton {
  background: linear-gradient(90deg, var(--z-800) 25%, var(--z-700) 50%, var(--z-800) 75%);
  background-size: 200% 100%;
  animation: shimmer 1.6s ease-in-out infinite;
  border-radius: var(--r-sm);
}
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* Live dot */
.dot-live {
  display: inline-block; width: 7px; height: 7px; border-radius: 50%;
  background: var(--success); position: relative; vertical-align: middle;
}
.dot-live::before {
  content: ''; position: absolute; inset: -3px; border-radius: 50%;
  border: 2px solid var(--success); opacity: 0;
  animation: livePulse 2.2s ease-out infinite;
}
@keyframes livePulse { 0%{transform:scale(.8);opacity:.7;} 100%{transform:scale(2.2);opacity:0;} }

/* ─── 14. Footer ─────────────────────────────────────────────── */
.site-footer {
  background: var(--bg-card);
  border-top: 1px solid var(--border);
  padding: var(--sp-20) 0 var(--sp-10);
}
.site-footer .f-brand {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: 1.05rem; font-weight: var(--fw-extrabold);
  letter-spacing: -.03em; color: var(--text);
}
.site-footer .f-brand span { color: var(--lime); }
.site-footer .f-desc {
  font-size: var(--t-sm); color: var(--text-3); line-height: 1.7;
  max-width: 280px; margin-top: var(--sp-3);
}
.site-footer h6 {
  font-size: var(--t-xs); font-weight: var(--fw-bold);
  letter-spacing: .08em; text-transform: uppercase;
  color: var(--text-4); margin-bottom: var(--sp-4);
  font-family: 'Inter', system-ui, sans-serif;
}
.site-footer ul { list-style: none; padding: 0; margin: 0; display:flex; flex-direction:column; gap: var(--sp-3); }
.site-footer ul a {
  font-size: var(--t-sm); color: var(--text-3);
  text-decoration: none; transition: color var(--t-base-d);
}
.site-footer ul a:hover { color: var(--lime); }
.site-footer .f-bottom {
  border-top: 1px solid var(--border);
  padding-top: var(--sp-6); margin-top: var(--sp-12);
  display:flex; flex-wrap:wrap; align-items:center;
  justify-content:space-between; gap: var(--sp-3);
}
.site-footer .f-bottom p { font-size: var(--t-xs); color: var(--text-4); margin: 0; }
.site-footer .f-compliance {
  display: flex; flex-wrap: wrap; gap: var(--sp-4); align-items: center;
  margin-top: var(--sp-8);
}
.site-footer .f-compliance-badge {
  display: flex; align-items: center; gap: var(--sp-2);
  font-size: var(--t-xs); color: var(--text-3);
}

/* ─── 15. Misc utilities ─────────────────────────────────────── */
.divider { border: none; border-top: 1px solid var(--border); margin: var(--sp-6) 0; }
.surface {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: var(--sp-6);
}
/* Hero glow — lime radial behind hero content */
.hero-glow {
  position: absolute; inset: 0; pointer-events: none;
  background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(182,230,46,.07) 0%, transparent 70%);
  filter: blur(60px);
}

.page-header { margin-bottom: var(--sp-8); }
.page-header .back {
  font-size: var(--t-xs); font-weight: var(--fw-medium); color: var(--text-3);
  text-decoration: none; display:inline-flex; align-items:center; gap: var(--sp-1);
  transition: color var(--t-base-d);
}
.page-header .back:hover { color: var(--lime); }

/* Empty state */
.empty-state {
  text-align: center; padding: var(--sp-16) var(--sp-8);
}
.empty-state .empty-icon { font-size: 3rem; margin-bottom: var(--sp-4); opacity: .5; }
.empty-state h3 { font-size: var(--t-xl); color: var(--text); margin-bottom: var(--sp-2); }
.empty-state p  { color: var(--text-3); margin-bottom: var(--sp-6); }

/* Pagination */
.pagination-wrap { padding: var(--sp-5) var(--sp-6); border-top: 1px solid var(--border); }
.pagination { gap: var(--sp-1); }
.page-link {
  background: var(--bg-elevated); border-color: var(--border);
  color: var(--text-3); font-size: var(--t-xs); border-radius: var(--r-sm) !important;
  transition: background var(--t-base-d), color var(--t-base-d);
}
.page-link:hover { background: var(--bg-hover); color: var(--text); border-color: var(--border-md); }
.page-item.active .page-link { background: var(--lime); border-color: var(--lime); color: #09090B; }

/* ─── 16. Bootstrap overrides ───────────────────────────────── */
.row > * { padding-inline: calc(var(--sp-4) / 2); }
.row { margin-inline: calc(var(--sp-4) / -2); }
.g-3 { --bs-gutter-x: 1rem;   --bs-gutter-y: 1rem; }
.g-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
.g-5 { --bs-gutter-x: 2rem;   --bs-gutter-y: 2rem; }
.table > :not(caption) > * > * { background-color: transparent; color: var(--text-2); }
.table-responsive { border-radius: var(--r-lg); overflow: hidden; }
fieldset { border: none; padding: 0; margin: 0; }
legend { float: none; font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text-2); margin-bottom: var(--sp-4); padding: 0; }
.btn-close { filter: invert(1); opacity: .5; transition: opacity var(--t-base-d); }
.btn-close:hover { opacity: 1; }
/* Collapse nav dark */
.navbar-collapse { background: transparent; }
@media (max-width: 992px) {
  .navbar-collapse.show, .navbar-collapse.collapsing {
    background: rgba(9,9,11,.96);
    backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: var(--sp-4);
    margin-top: var(--sp-2);
    box-shadow: var(--sh-lg);
  }
  .navbar-nav { flex-direction: column; width: 100%; gap: 0; }
  .nav-link { padding: var(--sp-3) var(--sp-4) !important; width: 100%; }
}

/* ─── 17. Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
  h1 { font-size: var(--t-3xl); }
  h2 { font-size: var(--t-2xl); }
  .page { padding: var(--sp-8) 0 var(--sp-16); }
}
@media (max-width: 576px) {
  h1 { font-size: 1.875rem; }
  .btn-lg { font-size: var(--t-sm); padding: .6875rem 1.25rem; }
}

/* ─── 18. High-contrast mode (WCAG 1.4.11) ──────────────────── */
@media (forced-colors: active) {
  .btn-primary { forced-color-adjust: none; }
  :focus-visible { outline: 3px solid Highlight; box-shadow: none; }
}
</style>
@stack('styles')
@stack('schema')
</head>
<body>

<a class="skip-link" href="#main-content">Ir al contenido principal</a>

<header>
  <nav class="navbar navbar-expand-lg" aria-label="Navegación principal">
    <div class="container">

      <a class="navbar-brand" href="{{ route('home') }}" aria-label="Reclama — inicio">
        <div class="logo-mark" aria-hidden="true">R</div>
        <span class="logo-word">Reclama</span>
      </a>

      <button class="navbar-toggler border-0 ms-auto" type="button"
              data-bs-toggle="collapse" data-bs-target="#main-nav"
              aria-controls="main-nav" aria-expanded="false"
              aria-label="Abrir menú de navegación">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="main-nav">
        <ul class="navbar-nav mx-auto gap-0" role="list">
          <li role="listitem">
            <a class="nav-link" href="{{ route('claim.create') }}"
               @if(request()->routeIs('claim.create')) aria-current="page" @endif>
              Generar reclamación
            </a>
          </li>
          <li role="listitem">
            <a class="nav-link" href="{{ route('subscription.plans') }}"
               @if(request()->routeIs('subscription.plans')) aria-current="page" @endif>
              Precios
            </a>
          </li>
          @auth
          <li role="listitem">
            <a class="nav-link" href="{{ route('dashboard') }}"
               @if(request()->routeIs('dashboard')) aria-current="page" @endif>
              Mi panel
            </a>
          </li>
          @if(auth()->user()->hasActiveSubscription())
          <li role="listitem">
            <a class="nav-link" href="{{ route('tools.index') }}"
               @if(request()->routeIs('tools.*')) aria-current="page" @endif>
              Herramientas
            </a>
          </li>
          @endif
          @endauth
        </ul>

        <ul class="navbar-nav align-items-center gap-2" role="list">
          @auth
            <li role="listitem">
              <span class="nav-link pe-none" style="font-size:var(--t-xs);color:var(--text-4)" aria-hidden="true">
                {{ auth()->user()->name }}
              </span>
            </li>
            @if(auth()->user()->hasActiveSubscription())
              <li role="listitem">
                <span class="nav-pro-badge" aria-label="Plan Pro activo">★ Pro</span>
              </li>
            @else
              <li role="listitem">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('subscription.plans') }}">
                  Hazte Pro →
                </a>
              </li>
            @endif
            <li role="listitem">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary">Salir</button>
              </form>
            </li>
          @else
            <li role="listitem">
              <a class="nav-link" href="{{ route('login') }}"
                 @if(request()->routeIs('login')) aria-current="page" @endif>
                Entrar
              </a>
            </li>
            <li role="listitem">
              <a class="btn btn-sm btn-primary" href="{{ route('claim.create') }}">
                Empezar gratis →
              </a>
            </li>
          @endauth
        </ul>
      </div>

    </div>
  </nav>
</header>

<main id="main-content" tabindex="-1">
  <div class="container page">

    {{-- Flash messages — WCAG 4.1.3 status messages --}}
    <div aria-live="polite" aria-atomic="true">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
          <span aria-hidden="true">✓</span>
          <div><strong>Hecho: </strong>{{ session('success') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar mensaje"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-5" role="alert">
          <span aria-hidden="true" role="img" aria-label="Error">!</span>
          <div><strong>Error: </strong>{{ session('error') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar mensaje"></button>
        </div>
      @endif
      @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-5" role="alert">
          <span aria-hidden="true">i</span>
          <div>{{ session('info') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar mensaje"></button>
        </div>
      @endif
      @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-5" role="alert">
          <span aria-hidden="true" role="img" aria-label="Aviso">⚠</span>
          <div>{{ session('warning') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar mensaje"></button>
        </div>
      @endif
    </div>

    @yield('content')
  </div>
</main>

<footer class="site-footer" aria-label="Pie de página">
  <div class="container">
    <div class="row g-5 mb-5">

      <div class="col-lg-4">
        <div class="f-brand">Reclama</div>
        <p class="f-desc">
          Software de gestión de reclamaciones a aseguradoras para asesorías de seguros españolas.
          Cartas listas para el Defensor del Asegurado o la DGSFP.
        </p>
        <div class="f-compliance">
          <span class="f-compliance-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            RGPD
          </span>
          <span class="f-compliance-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            LSSI-CE
          </span>
          <span class="f-compliance-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            WCAG 2.1 AA
          </span>
        </div>
      </div>

      <div class="col-6 col-md-3 col-lg-2 offset-lg-2">
        <h6>Producto</h6>
        <ul>
          <li><a href="{{ route('claim.create') }}">Generar reclamación</a></li>
          <li><a href="{{ route('subscription.plans') }}">Plan Pro</a></li>
          @auth
          <li><a href="{{ route('dashboard') }}">Mi panel</a></li>
          @endauth
          <li><a href="{{ route('tools.baremo.show') }}">Baremo tráfico</a></li>
          <li><a href="{{ route('blog.index') }}">Blog</a></li>
          <li><a href="{{ route('guias.index') }}">Guías</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3 col-lg-2">
        <h6>Reclamaciones</h6>
        <ul>
          <li><a href="{{ route('seo.hogar') }}">Seguro de hogar</a></li>
          <li><a href="{{ route('seo.coche') }}">Seguro de coche</a></li>
          <li><a href="{{ route('seo.vida') }}">Seguro de vida</a></li>
          <li><a href="{{ route('seo.fallecidos') }}">Seguros fallecidos</a></li>
          <li><a href="{{ route('seo.desastres') }}">Desastres naturales</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3 col-lg-2">
        <h6>Legal</h6>
        <ul>
          <li><a href="{{ route('legal.terminos') }}">Términos de uso</a></li>
          <li><a href="{{ route('legal.privacidad') }}">Privacidad</a></li>
          <li><a href="{{ route('legal.aviso') }}">Aviso legal</a></li>
          <li><a href="{{ route('legal.cookies') }}">Cookies</a></li>
          <li><a href="mailto:hola@reclama.es">hola@reclama.es</a></li>
        </ul>
      </div>

    </div>

    <div class="f-bottom">
      <p>© {{ date('Y') }} Reclama · Todos los derechos reservados</p>
      <p>LOPD-GDD · LSSI-CE · LGDCU · RD 1112/2018</p>
    </div>
  </div>
</footer>

{{-- Cookie banner — LSSI-CE compliant — dark theme --}}
<div id="cookie-banner" role="dialog" aria-modal="true" aria-label="Configuración de cookies"
  style="position:fixed;bottom:0;left:0;right:0;z-index:9000;display:none;
         background:rgba(17,17,19,.95);backdrop-filter:blur(20px) saturate(180%);
         -webkit-backdrop-filter:blur(20px) saturate(180%);
         border-top:1px solid rgba(255,255,255,.12);">
  <div class="container" style="padding-top:1.25rem;padding-bottom:1.25rem">
    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1rem">
      <p style="margin:0;font-size:var(--t-sm);color:var(--text-3);flex:1;min-width:280px">
        <strong style="color:var(--text)">Usamos cookies</strong> técnicas (necesarias) y analíticas opcionales.
        <a href="{{ route('legal.cookies') }}" style="color:var(--lime)">Política de cookies</a>
      </p>
      <div style="display:flex;gap:.5rem;flex-shrink:0;flex-wrap:wrap">
        <button id="cookie-config-btn" class="btn btn-sm btn-secondary" type="button">Configurar</button>
        <button id="cookie-reject-btn" class="btn btn-sm btn-secondary" type="button">Solo necesarias</button>
        <button id="cookie-accept-btn" class="btn btn-sm btn-primary" type="button">Aceptar todas</button>
      </div>
    </div>
    <div id="cookie-config-panel" class="d-none" style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)">
      <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.5rem">
        <input type="checkbox" id="c-necessary" checked disabled class="form-check-input" aria-label="Cookies técnicas, siempre activas">
        <label for="c-necessary" style="font-size:var(--t-xs);color:var(--text-3)">
          <strong style="color:var(--text-2)">Técnicas</strong> — sesión, CSRF, preferencias. Siempre activas.
        </label>
      </div>
      <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.75rem">
        <input type="checkbox" id="c-analytics" class="form-check-input" aria-label="Cookies analíticas opcionales">
        <label for="c-analytics" style="font-size:var(--t-xs);color:var(--text-3)">
          <strong style="color:var(--text-2)">Analíticas</strong> — estadísticas anónimas de uso (opt-in).
        </label>
      </div>
      <button id="cookie-save-btn" class="btn btn-sm btn-primary" type="button">Guardar preferencias</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar — transparent → dark glass on scroll (threshold 40px)
(function() {
  var nav = document.querySelector('.navbar');
  if (!nav) return;
  function updateNav() {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  }
  updateNav();
  window.addEventListener('scroll', updateNav, { passive: true });
})();

// Cookie consent — LSSI + RGPD compliant
(function() {
  var banner = document.getElementById('cookie-banner');
  if (!banner) return;
  var hasConsent = document.cookie.split(';').some(function(c) {
    return c.trim().startsWith('Reclama_consent=');
  });
  if (!hasConsent) {
    banner.style.display = 'block';
    // Focus first button for keyboard users (WCAG 2.4.3)
    setTimeout(function() {
      var btn = banner.querySelector('button');
      if (btn) btn.focus();
    }, 300);
  }

  function save(analytics) {
    var exp = new Date(Date.now() + 365 * 864e5).toUTCString();
    document.cookie = 'Reclama_consent=' + (analytics ? 'all' : 'necessary')
      + '; expires=' + exp + '; path=/; SameSite=Lax';
    banner.style.display = 'none';
    fetch('/legal/consent', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ type: 'cookie_banner', analytics: analytics })
    }).catch(function() {});
  }

  document.getElementById('cookie-accept-btn').onclick = function() { save(true); };
  document.getElementById('cookie-reject-btn').onclick = function() { save(false); };
  document.getElementById('cookie-config-btn').onclick = function() {
    var panel = document.getElementById('cookie-config-panel');
    panel.classList.toggle('d-none');
    this.setAttribute('aria-expanded', !panel.classList.contains('d-none'));
  };
  document.getElementById('cookie-save-btn').onclick = function() {
    save(document.getElementById('c-analytics').checked);
  };
})();
</script>
@stack('scripts')
</body>
</html>
