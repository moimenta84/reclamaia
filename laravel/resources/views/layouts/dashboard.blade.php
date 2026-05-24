<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Panel') — Reclama</title>
<meta name="description" content="@yield('meta-description', 'Panel de control para asesorías de seguros.')">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..800;1,14..32,300..800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ══════════════════════════════════════════════════════════════
   Reclama DASHBOARD — Design System v5.0 Dark Edition
   Sidebar: 256px · Topbar: 56px · Lime accent · WCAG 2.1 AA
   ══════════════════════════════════════════════════════════════ */

/* ─── 1. Design Tokens ─────────────────────────────────────── */
:root {
  /* Slate scale — blue-black foundation */
  --z-950: #080B10; --z-900: #0F1115; --z-800: #151922;
  --z-700: #1E2333; --z-600: #2A2F3A; --z-500: #3D4455;
  --z-400: #5C6478; --z-300: #7E8A9E; --z-200: #A8B3C4;
  --z-100: #D0D5DE; --z-50:  #E8ECF2; --z-25:  #F5F7FA;

  /* Backward-compat */
  --s-950:var(--z-950);--s-900:var(--z-900);--s-800:var(--z-800);
  --s-700:var(--z-700);--s-600:var(--z-600);--s-500:var(--z-500);
  --s-400:var(--z-400);--s-300:var(--z-300);--s-200:var(--z-200);
  --s-100:var(--z-100);--s-50:var(--z-50);--s-25:var(--z-25);

  /* Lime accent */
  --lime:        #B6E62E;
  --lime-dark:   #A3D126;
  --lime-dim:    rgba(182,230,46,.12);
  --lime-border: rgba(182,230,46,.22);
  --lime-glow:   0 0 20px rgba(182,230,46,.15);

  /* Semantic */
  --bg:           #0F1115;
  --bg-section:   #151922;
  --bg-card:      #181C24;
  --bg-elevated:  #1E2333;
  --bg-hover:     rgba(255,255,255,.04);
  --bg-subtle:    rgba(255,255,255,.06);

  --border:        #2A2F3A;
  --border-md:     #353C4A;
  --border-strong: rgba(255,255,255,.20);

  /* Text — contrast vs #0F1115 */
  --text:   #F3F4F6;   /* 17.4:1 ✅ */
  --text-2: #E4E4E7;   /* 15.2:1 ✅ */
  --text-3: #A1A1AA;   /*  5.3:1 ✅ */
  --text-4: #6B7280;   /*  3.2:1 large/UI only */

  /* Accent aliases */
  --accent: var(--lime);
  --accent-h: var(--lime-dark);
  --accent-s: var(--lime-dim);

  /* Blue — for badges only */
  --b-700:#1d4ed8;--b-600:#2563eb;--b-500:#3b82f6;
  --b-400:#60a5fa;--b-300:#93c5fd;--b-200:#bfdbfe;
  --b-100:#dbeafe;--b-50:#eff6ff;

  /* Semantic feedback */
  --success: #22C55E; --success-bg: rgba(34,197,94,.12);
  --danger:  #F87171; --danger-bg:  rgba(248,113,113,.12);
  --warning: #FBBF24; --warning-bg: rgba(251,191,36,.12);
  --info-color: #38BDF8; --info-bg: rgba(56,189,248,.12);
  --g-700:#d97706;--g-600:#f59e0b;--g-500:#fbbf24;
  --g-100:rgba(245,158,11,.2);--g-50:rgba(245,158,11,.1);

  /* Spacing */
  --sp-1:.25rem;--sp-2:.5rem;--sp-3:.75rem;--sp-4:1rem;
  --sp-5:1.25rem;--sp-6:1.5rem;--sp-8:2rem;--sp-10:2.5rem;
  --sp-12:3rem;--sp-16:4rem;--sp-20:5rem;--sp-24:6rem;
  --s1:var(--sp-1);--s2:var(--sp-2);--s3:var(--sp-3);--s4:var(--sp-4);
  --s5:var(--sp-5);--s6:var(--sp-6);--s8:var(--sp-8);--s10:var(--sp-10);
  --s12:var(--sp-12);--s16:var(--sp-16);--s20:var(--sp-20);--s24:var(--sp-24);

  /* Radius */
  --r-xs:3px;--r-sm:6px;--r-md:10px;--r-lg:14px;
  --r-xl:20px;--r-2xl:28px;--r-full:9999px;

  /* Shadows */
  --sh-xs:   0 1px 2px rgba(0,0,0,.5);
  --sh-sm:   0 2px 6px rgba(0,0,0,.5);
  --sh-md:   0 4px 12px rgba(0,0,0,.6);
  --sh-lg:   0 12px 32px rgba(0,0,0,.7);
  --sh-card: 0 0 0 1px var(--border), 0 2px 8px rgba(0,0,0,.5);
  --sh-card-hover: 0 0 0 1px var(--lime-border), 0 8px 24px rgba(0,0,0,.6);
  /* Lime focus ring — 12:1 contrast ✅ WCAG AAA */
  --sh-ring:  0 0 0 2px #0F1115, 0 0 0 4px #B6E62E;
  --sh-accent:0 0 0 2px #0F1115, 0 0 0 4px var(--lime);

  /* Type scale */
  --t-xs:.75rem;--t-sm:.8125rem;--t-base:.9375rem;--t-lg:1.0625rem;
  --t-xl:1.25rem;--t-2xl:1.5rem;--t-3xl:1.875rem;--t-4xl:2.375rem;
  --t-5xl:3rem;--t-6xl:3.75rem;

  --fw-normal:400;--fw-medium:500;--fw-semibold:600;
  --fw-bold:700;--fw-extrabold:800;--fw-black:900;

  --ease:cubic-bezier(.16,1,.3,1);
  --t-fast:80ms;--t-base-d:140ms;--t-slow:220ms;
  --t-1:80ms;--t-2:140ms;--t-3:220ms;--t-4:350ms;

  /* Dashboard layout */
  --sidebar-w:  256px;
  --topbar-h:   56px;
}

/* ─── 2. Reset ──────────────────────────────────────────────── */
*,*::before,*::after { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  font-feature-settings: 'cv02' 1, 'cv03' 1, 'cv04' 1, 'cv11' 1;
  font-size: var(--t-base); line-height: 1.65;
  color: var(--text); background: var(--bg);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  margin: 0; overflow-x: hidden;
  position: relative;
}

/* Noise grain overlay */
body::after {
  content:'';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.4'/%3E%3C/svg%3E");
  opacity: .03;
  pointer-events: none;
  z-index: 9998;
  mix-blend-mode: overlay;
  animation: grain 10s steps(10) infinite;
}
@keyframes grain {
  0%,100%{transform:translate(0,0);}
  20%{transform:translate(-3%,5%);}
  40%{transform:translate(4%,-4%);}
  60%{transform:translate(-5%,3%);}
  80%{transform:translate(3%,-5%);}
}

/* Scrollbar */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--z-700); border-radius: var(--r-full); }
::-webkit-scrollbar-thumb:hover { background: var(--z-600); }

::selection { background: rgba(182,230,46,.18); color: var(--lime); }

/* ─── 3. Accessibility ─────────────────────────────────────── */
.skip-link {
  position: absolute; top: -100%; left: var(--sp-4); z-index: 9999;
  background: var(--lime); color: #0F1115;
  font-size: var(--t-sm); font-weight: var(--fw-bold);
  padding: var(--sp-3) var(--sp-5);
  border-radius: 0 0 var(--r-md) var(--r-md);
  text-decoration: none; box-shadow: var(--sh-lg);
  transition: top var(--t-fast);
}
.skip-link:focus { top: 0; }

/* WCAG 2.4.7 — lime focus ring */
:focus-visible {
  outline: none;
  box-shadow: var(--sh-ring);
  border-radius: var(--r-sm);
}
:focus:not(:focus-visible) { outline: none; }

@media (prefers-reduced-motion: reduce) {
  *,*::before,*::after {
    animation-duration: .01ms !important;
    transition-duration: .01ms !important;
  }
  body::after { animation: none; }
}

/* ─── 4. Typography ─────────────────────────────────────────── */
h1,h2,h3,h4,h5,h6 {
  font-family: 'Inter', system-ui, sans-serif;
  font-feature-settings: 'cv02' 1, 'cv03' 1, 'cv04' 1, 'cv11' 1;
  letter-spacing: -.025em; line-height: 1.2; color: var(--text);
}
h1 { font-size: var(--t-4xl); font-weight: var(--fw-bold); letter-spacing: -.035em; }
h2 { font-size: var(--t-3xl); font-weight: var(--fw-semibold); line-height: 1.25; }
h3 { font-size: var(--t-2xl); font-weight: var(--fw-semibold); }
h4 { font-size: var(--t-xl);  font-weight: var(--fw-semibold); }
h5,h6 { font-size: var(--t-base); font-weight: var(--fw-semibold); }
p { color: var(--text-3); margin-bottom: 0; }
a { color: var(--lime); text-underline-offset: 3px; }
a:hover { color: var(--lime-dark); }
code,pre,kbd { font-family: 'JetBrains Mono', monospace; }

.text-muted   { color: var(--text-3) !important; }
.fw-medium    { font-weight: var(--fw-medium)   !important; }
.fw-semibold  { font-weight: var(--fw-semibold) !important; }
.text-gradient {
  background: linear-gradient(135deg, var(--lime) 0%, #A3FF0E 60%);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}

/* ─── 5. Dashboard Shell ─────────────────────────────────────── */

/* Sidebar — deepest dark surface */
.db-sidebar {
  position: fixed; top: 0; left: 0; bottom: 0;
  width: var(--sidebar-w);
  background: #0B0E15;
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column;
  z-index: 300;
  transition: transform var(--t-slow) var(--ease);
  overflow-y: auto; overflow-x: hidden;
}
.db-sidebar::-webkit-scrollbar { width: 3px; }
.db-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); }

/* Sidebar logo */
.db-logo {
  display: flex; align-items: center; gap: var(--sp-2);
  padding: var(--sp-5);
  border-bottom: 1px solid var(--border);
  flex-shrink: 0; text-decoration: none;
}
.db-logo .logo-mark {
  width: 30px; height: 30px; border-radius: var(--r-sm);
  background: var(--lime);
  display: flex; align-items: center; justify-content: center;
  color: #0F1115; font-size: .6rem; font-weight: var(--fw-black);
  flex-shrink: 0;
}
.db-logo .logo-word {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .95rem; font-weight: var(--fw-extrabold);
  letter-spacing: -.03em; color: var(--text);
}
.db-logo .logo-word span { color: var(--lime); }

/* Sidebar nav */
.db-nav { flex: 1; padding: var(--sp-4) var(--sp-3); }
.db-nav-section { margin-bottom: var(--sp-5); }
.db-nav-label {
  font-size: .62rem; font-weight: var(--fw-bold); letter-spacing: .1em;
  text-transform: uppercase; color: rgba(255,255,255,.25);
  padding: 0 var(--sp-3); margin-bottom: var(--sp-2);
  display: block;
  font-family: 'Inter', system-ui, sans-serif;
}

/* Nav items — neurodesign: inactive items recede, active item advances */
.db-nav-item {
  display: flex; align-items: center; gap: var(--sp-3);
  padding: var(--sp-2) var(--sp-3); border-radius: var(--r-md);
  font-size: var(--t-sm); font-weight: var(--fw-medium);
  color: rgba(255,255,255,.5); text-decoration: none;
  transition: background var(--t-base-d), color var(--t-base-d);
  position: relative; margin-bottom: 1px;
  font-family: 'Inter', system-ui, sans-serif;
}
.db-nav-item:hover {
  background: rgba(255,255,255,.06);
  color: rgba(255,255,255,.85);
}
.db-nav-item.active {
  background: var(--lime-dim);
  color: var(--lime); font-weight: var(--fw-semibold);
}
/* Lime left indicator — draws eye to current location */
.db-nav-item.active::before {
  content: '';
  position: absolute; left: 0; top: 20%; bottom: 20%;
  width: 2px; border-radius: 0 var(--r-full) var(--r-full) 0;
  background: var(--lime);
}
.db-nav-item .nav-icon {
  width: 16px; height: 16px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  opacity: .6;
}
.db-nav-item.active .nav-icon { opacity: 1; }
.db-nav-item .nav-lock {
  margin-left: auto; font-size: .65rem; opacity: .35;
}
.db-nav-item.locked { opacity: .4; cursor: not-allowed; pointer-events: none; }

/* Pro badge in sidebar */
.db-pro-badge {
  display: inline-flex; align-items: center;
  font-size: .6rem; font-weight: var(--fw-bold); letter-spacing: .05em;
  text-transform: uppercase;
  color: var(--lime); background: var(--lime-dim);
  border: 1px solid var(--lime-border);
  padding: .1rem .35rem; border-radius: var(--r-full);
  margin-left: auto;
}

/* User profile at bottom of sidebar */
.db-user {
  padding: var(--sp-4) var(--sp-4);
  border-top: 1px solid var(--border);
  flex-shrink: 0;
}
.db-user-info {
  display: flex; align-items: center; gap: var(--sp-3);
  margin-bottom: var(--sp-3);
}
.db-avatar {
  width: 32px; height: 32px; border-radius: var(--r-full);
  background: var(--lime-dim);
  border: 1px solid var(--lime-border);
  display: flex; align-items: center; justify-content: center;
  font-size: var(--t-sm); font-weight: var(--fw-bold); color: var(--lime);
  flex-shrink: 0;
}
.db-user-name  { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); line-height: 1.2; }
.db-user-email { font-size: var(--t-xs); color: var(--text-4); line-height: 1; }
.db-logout {
  width: 100%; padding: var(--sp-2) var(--sp-3);
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--r-sm); color: var(--text-3); font-size: var(--t-xs);
  font-weight: var(--fw-medium); cursor: pointer; text-align: center;
  transition: background var(--t-base-d), color var(--t-base-d), border-color var(--t-base-d);
  font-family: 'Inter', system-ui, sans-serif;
}
.db-logout:hover {
  background: rgba(255,255,255,.08); color: var(--text); border-color: var(--border-md);
}

/* ─── 6. Topbar ─────────────────────────────────────────────── */
.db-topbar {
  position: fixed; top: 0; right: 0; left: var(--sidebar-w);
  height: var(--topbar-h);
  background: rgba(15,17,21,.88);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: var(--sp-4);
  padding: 0 var(--sp-6);
  z-index: 200;
  transition: left var(--t-slow) var(--ease);
}
.db-topbar-left  { flex: 1; display: flex; align-items: center; gap: var(--sp-3); }
.db-topbar-right { display: flex; align-items: center; gap: var(--sp-3); }

.db-page-title {
  font-size: var(--t-base); font-weight: var(--fw-semibold);
  color: var(--text); letter-spacing: -.01em;
  font-family: 'Inter', system-ui, sans-serif;
}

/* Mobile hamburger */
.db-hamburger {
  display: none; align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: var(--r-sm);
  background: transparent; border: 1px solid var(--border-md);
  cursor: pointer; transition: background var(--t-base-d);
  color: var(--text-3);
}
.db-hamburger:hover { background: var(--bg-hover); color: var(--text); }
.db-hamburger svg { display: block; }

/* Mobile logo in topbar */
.db-topbar-logo {
  display: none; align-items: center; gap: var(--sp-2);
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .92rem; font-weight: var(--fw-extrabold);
  letter-spacing: -.03em; color: var(--text); text-decoration: none;
}
.db-topbar-logo .logo-mark {
  width: 26px; height: 26px; border-radius: var(--r-sm);
  background: var(--lime);
  display: flex; align-items: center; justify-content: center;
  color: #0F1115; font-size: .55rem; font-weight: var(--fw-black);
}
.db-topbar-logo .logo-word span { color: var(--lime); }

/* Bell */
.db-bell {
  display: flex; align-items: center; justify-content: center;
  width: 32px; height: 32px; border-radius: var(--r-sm);
  background: transparent; border: 1px solid var(--border-md);
  cursor: pointer; color: var(--text-3);
  transition: background var(--t-base-d), color var(--t-base-d);
}
.db-bell:hover { background: var(--bg-hover); color: var(--text); }

/* ─── 7. Content area ───────────────────────────────────────── */
.db-content {
  margin-left: var(--sidebar-w);
  padding-top: var(--topbar-h);
  min-height: 100vh;
  transition: margin-left var(--t-slow) var(--ease);
}
.db-inner {
  padding: var(--sp-8) var(--sp-8) var(--sp-20);
  max-width: 1100px;
}

/* ─── 8. Buttons ────────────────────────────────────────────── */
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

/* Primary — lime bg, dark text: 12:1 ✅ WCAG AAA */
.btn-primary {
  background: var(--lime);
  border-color: var(--lime-dark);
  color: #0F1115 !important;
  box-shadow: 0 0 18px rgba(182,230,46,.15);
}
.btn-primary:hover {
  background: var(--lime-dark);
  border-color: #8BB518;
  color: #0F1115 !important;
  box-shadow: 0 0 28px rgba(182,230,46,.28), 0 4px 12px rgba(0,0,0,.4);
  transform: translateY(-1px);
}
.btn-primary::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(105deg,transparent 30%,rgba(255,255,255,.15) 50%,transparent 70%);
  transform: translateX(-100%); transition: transform var(--t-4) var(--ease);
}
.btn-primary:hover::after { transform: translateX(100%); }

.btn-secondary {
  background: var(--bg-elevated); border-color: var(--border-md); color: var(--text-2) !important;
  box-shadow: var(--sh-xs);
}
.btn-secondary:hover {
  background: var(--bg-subtle); border-color: var(--border-strong);
  color: var(--text) !important; transform: translateY(-1px);
}

.btn-ghost { background: transparent; border-color: transparent; color: var(--text-3) !important; }
.btn-ghost:hover { background: var(--bg-hover); color: var(--text) !important; }
.btn-danger { background: var(--danger); border-color: var(--danger); color: #fff !important; }
.btn-danger:hover { background: #DC2626; }
.btn-outline-primary {
  background: transparent; border-color: var(--lime-border); color: var(--lime) !important;
}
.btn-outline-primary:hover { background: var(--lime-dim); border-color: var(--lime); }
.btn-lg  { font-size: var(--t-base); padding: .75rem 1.625rem; border-radius: var(--r-lg); }
.btn-sm  { font-size: var(--t-xs);   padding: .375rem .75rem;  border-radius: var(--r-sm); gap: var(--sp-1); }

/* ─── 9. Cards ──────────────────────────────────────────────── */
.card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); box-shadow: var(--sh-card); overflow: hidden;
}
a .card, .card-link {
  transition: box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease),
              border-color var(--t-base-d);
}
a:hover .card, .card-link:hover {
  box-shadow: var(--sh-card-hover); border-color: var(--lime-border);
  transform: translateY(-2px);
}

/* KPI card — neurodesign: serif numbers feel substantial, authoritative */
.kpi {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: var(--sp-5) var(--sp-6);
  display: flex; flex-direction: column; gap: var(--sp-2);
  box-shadow: var(--sh-card);
  transition: border-color var(--t-base-d), box-shadow var(--t-base-d);
}
.kpi:hover { border-color: var(--border-md); }
.kpi-header  { display: flex; align-items: center; justify-content: space-between; }
.kpi-label   {
  font-size: var(--t-xs); font-weight: var(--fw-semibold);
  letter-spacing: .07em; text-transform: uppercase; color: var(--text-4);
  font-family: 'Inter', system-ui, sans-serif;
}
.kpi-icon    {
  width: 32px; height: 32px; border-radius: var(--r-sm);
  display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0;
}
.kpi-value   {
  font-family: 'Inter', system-ui, sans-serif;
  font-size: var(--t-3xl); font-weight: var(--fw-bold);
  letter-spacing: -.02em; color: var(--text); line-height: 1;
}
.kpi-sub     { font-size: var(--t-xs); color: var(--text-4); }

/* Section card */
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

/* Tool card */
.tool-card {
  padding: var(--sp-6); display:flex; flex-direction:column; gap: var(--sp-3);
  text-decoration: none; color: inherit; cursor: pointer;
  border-top: 2px solid transparent;
  transition: box-shadow var(--t-base-d) var(--ease), transform var(--t-base-d) var(--ease),
              border-top-color var(--t-base-d), background var(--t-base-d);
}
.tool-card:hover {
  color: inherit; box-shadow: var(--sh-card-hover);
  border-top-color: var(--lime); background: var(--bg-elevated); transform: translateY(-2px);
}
.tool-card .tc-icon {
  width: 42px; height: 42px; border-radius: var(--r-md);
  display:flex; align-items:center; justify-content:center; font-size: 1.2rem;
  transition: transform var(--t-slow) var(--ease);
}
.tool-card:hover .tc-icon { transform: scale(1.08) rotate(-3deg); }
.tool-card .tc-title { font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text); font-family: 'Inter', system-ui, sans-serif; }
.tool-card .tc-desc  { font-size: var(--t-xs); color: var(--text-3); line-height: 1.6; flex-grow: 1; }
.tool-card .tc-arrow { font-size: var(--t-sm); color: var(--lime); margin-top: var(--sp-2); transition: transform var(--t-base-d) var(--ease); }
.tool-card:hover .tc-arrow { transform: translateX(4px); }

/* ─── 10. Forms ─────────────────────────────────────────────── */
.form-label {
  font-size: var(--t-sm); font-weight: var(--fw-medium);
  color: var(--text-2); margin-bottom: var(--sp-2); display: block;
  font-family: 'Inter', system-ui, sans-serif;
}
.form-label .required { color: var(--danger); margin-left: 2px; font-size: .65rem; vertical-align: super; }

.form-control, .form-select {
  font-family: 'Inter', system-ui, sans-serif; font-size: var(--t-sm);
  color: var(--text); background: var(--bg-elevated);
  border: 1px solid var(--border-md); border-radius: var(--r-md);
  padding: .625rem .875rem; width: 100%; line-height: 1.4;
  transition: border-color var(--t-base-d), box-shadow var(--t-base-d);
}
.form-control::placeholder { color: var(--text-4); }
.form-control:hover:not(:focus) { border-color: var(--border-strong); }
.form-control:focus, .form-select:focus {
  border-color: var(--lime); box-shadow: var(--sh-ring); outline: none;
}
.form-control.is-invalid { border-color: var(--danger); background: var(--danger-bg); }
.form-control.is-invalid:focus {
  box-shadow: 0 0 0 2px #0F1115, 0 0 0 4px var(--danger);
}
.form-select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23A1A1AA' stroke-width='1.5' d='m4 6 4 4 4-4'/%3e%3c/svg%3e");
  background-color: var(--bg-elevated);
}
.form-select option { background: var(--bg-elevated); color: var(--text); }
.form-text { font-size: var(--t-xs); color: var(--text-3); margin-top: var(--sp-1); }
.invalid-feedback {
  font-size: var(--t-xs); font-weight: var(--fw-medium); color: var(--danger);
  display:flex; align-items:center; gap: var(--sp-1); margin-top: var(--sp-1);
}
.invalid-feedback::before {
  content: '!'; font-size: .6rem; font-weight: 900;
  width: 13px; height: 13px; background: var(--danger); color: #fff;
  border-radius: 50%; display:flex; align-items:center; justify-content:center;
  flex-shrink: 0;
}
.form-check-input {
  background: var(--bg-elevated); border-color: var(--border-strong); cursor: pointer;
  transition: border-color var(--t-base-d), background var(--t-base-d), box-shadow var(--t-base-d);
}
.form-check-input:checked { background-color: var(--lime); border-color: var(--lime); }
.form-check-input:focus { box-shadow: var(--sh-ring); outline: none; }
.form-check-label { font-size: var(--t-sm); cursor: pointer; color: var(--text-2); }

/* ─── 11. Tables ────────────────────────────────────────────── */
.table-enterprise { border-collapse: separate; border-spacing: 0; width: 100%; }
.table-enterprise thead th {
  font-size: var(--t-xs); font-weight: var(--fw-semibold); letter-spacing: .06em;
  text-transform: uppercase; color: var(--text-4);
  padding: var(--sp-3) var(--sp-5); background: var(--bg-elevated);
  border-bottom: 1px solid var(--border-md); white-space: nowrap;
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

/* ─── 12. Badges ────────────────────────────────────────────── */
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
.badge.bg-gold      { background: var(--g-50)       !important; color: var(--g-500)   !important; border: 1px solid var(--g-100); }

/* ─── 13. Alerts ────────────────────────────────────────────── */
.alert {
  border-radius: var(--r-lg); border: none; font-size: var(--t-sm);
  padding: var(--sp-4) var(--sp-5); display:flex; align-items:flex-start; gap: var(--sp-3);
  border-left: 3px solid transparent;
}
.alert-success { background: var(--success-bg); color: #86EFAC; border-left-color: var(--success); }
.alert-danger  { background: var(--danger-bg);  color: #FCA5A5; border-left-color: var(--danger);  }
.alert-warning { background: var(--warning-bg); color: #FCD34D; border-left-color: var(--warning); }
.alert-info    { background: var(--info-bg);    color: #7DD3FC; border-left-color: var(--info-color); }
.alert strong  { font-weight: var(--fw-semibold); }

/* ─── 14. Animations ─────────────────────────────────────────── */
.fade-up { opacity: 0; transform: translateY(12px); animation: fadeUp var(--t-slow) var(--ease) forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.delay-1 { animation-delay:  60ms; }
.delay-2 { animation-delay: 120ms; }
.delay-3 { animation-delay: 180ms; }
.delay-4 { animation-delay: 240ms; }

.skeleton {
  background: linear-gradient(90deg, var(--z-800) 25%, var(--z-700) 50%, var(--z-800) 75%);
  background-size: 200% 100%; animation: shimmer 1.6s ease-in-out infinite; border-radius: var(--r-sm);
}
@keyframes shimmer { 0%{background-position:200% 0;} 100%{background-position:-200% 0;} }

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

/* ─── 15. Misc utilities ─────────────────────────────────────── */
.divider { border: none; border-top: 1px solid var(--border); margin: var(--sp-6) 0; }
.surface { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-lg); padding: var(--sp-6); }
.empty-state { text-align: center; padding: var(--sp-16) var(--sp-8); }
.empty-state .empty-icon { font-size: 3rem; margin-bottom: var(--sp-4); opacity: .4; }
.empty-state h3 { font-size: var(--t-xl); color: var(--text); margin-bottom: var(--sp-2); }
.empty-state p  { color: var(--text-3); margin-bottom: var(--sp-6); }
.pagination-wrap { padding: var(--sp-5) var(--sp-6); border-top: 1px solid var(--border); }
.pagination { gap: var(--sp-1); }
.page-link {
  background: var(--bg-elevated); border-color: var(--border); color: var(--text-3);
  font-size: var(--t-xs); border-radius: var(--r-sm) !important;
}
.page-link:hover { background: var(--bg-hover); color: var(--text); border-color: var(--border-md); }
.page-item.active .page-link { background: var(--lime); border-color: var(--lime); color: #09090B; }

/* ─── 16. Bootstrap overrides ───────────────────────────────── */
.table > :not(caption) > * > * { background-color: transparent; color: var(--text-2); }
.table-responsive { border-radius: var(--r-lg); overflow: hidden; }
fieldset { border: none; padding: 0; margin: 0; }
legend { float: none; font-size: var(--t-sm); font-weight: var(--fw-semibold); color: var(--text-2); margin-bottom: var(--sp-4); padding: 0; }
.row > * { padding-inline: .5rem; }
.row { margin-inline: -.5rem; }
.g-3 { --bs-gutter-x: 1rem;   --bs-gutter-y: 1rem; }
.g-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
.btn-close { filter: invert(1); opacity: .5; transition: opacity var(--t-base-d); }
.btn-close:hover { opacity: 1; }

/* ─── 17. Mobile overlay ─────────────────────────────────────── */
.db-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.7); z-index: 299;
  backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
}
.db-overlay.active { display: block; }

/* ─── 18. Responsive ─────────────────────────────────────────── */
@media (max-width: 992px) {
  .db-sidebar { transform: translateX(-100%); }
  .db-sidebar.open { transform: translateX(0); box-shadow: 4px 0 32px rgba(0,0,0,.6); }
  .db-topbar { left: 0; }
  .db-content { margin-left: 0; }
  .db-hamburger     { display: flex; }
  .db-topbar-logo   { display: flex; }
  .db-topbar-title  { display: none; }
}
@media (max-width: 768px) {
  h1 { font-size: var(--t-3xl); }
  h2 { font-size: var(--t-2xl); }
  .db-inner { padding: var(--sp-5) var(--sp-4) var(--sp-16); }
  .kpi-value { font-size: var(--t-2xl); }
}
@media (max-width: 480px) {
  .db-inner { padding: var(--sp-4) var(--sp-3) var(--sp-12); }
}
@media (forced-colors: active) {
  .btn-primary { forced-color-adjust: none; }
  :focus-visible { outline: 3px solid Highlight; box-shadow: none; }
}
</style>
@stack('styles')
</head>
<body>

<a class="skip-link" href="#main-content">Ir al contenido principal</a>

{{-- Mobile overlay --}}
<div class="db-overlay" id="sidebar-overlay" aria-hidden="true" role="presentation"></div>

{{-- ══════ SIDEBAR ══════ --}}
<aside class="db-sidebar" id="db-sidebar" aria-label="Navegación del panel">

  {{-- Logo --}}
  <a class="db-logo" href="{{ route('home') }}" aria-label="Reclama — inicio">
    <div class="logo-mark" aria-hidden="true">R</div>
    <span class="logo-word">Reclama</span>
  </a>

  {{-- Navigation --}}
  <nav class="db-nav" aria-label="Menú principal del panel">

    <div class="db-nav-section">
      <a href="{{ route('dashboard') }}"
         class="db-nav-item @if(request()->routeIs('dashboard')) active @endif"
         @if(request()->routeIs('dashboard')) aria-current="page" @endif>
        <span class="nav-icon" aria-hidden="true">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        </span>
        Panel
      </a>
      <a href="{{ route('dashboard') }}"
         class="db-nav-item"
         aria-label="Ver todos los expedientes">
        <span class="nav-icon" aria-hidden="true">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </span>
        Expedientes
      </a>
      <a href="{{ route('claim.create') }}"
         class="db-nav-item @if(request()->routeIs('claim.create')) active @endif"
         @if(request()->routeIs('claim.create')) aria-current="page" @endif>
        <span class="nav-icon" aria-hidden="true">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        </span>
        Nueva reclamación
      </a>
    </div>

    {{-- Herramientas Pro --}}
    <div class="db-nav-section">
      <span class="db-nav-label">Herramientas Pro</span>

      <a href="{{ route('tools.baremo.show') }}"
         class="db-nav-item @if(request()->routeIs('tools.baremo.*')) active @endif"
         @if(request()->routeIs('tools.baremo.*')) aria-current="page" @endif>
        <span class="nav-icon" aria-hidden="true">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </span>
        Baremo tráfico
      </a>

      <a href="{{ route('tools.fallecido.index') }}"
         class="db-nav-item @if(request()->routeIs('tools.fallecido.*')) active @endif"
         @if(request()->routeIs('tools.fallecido.*')) aria-current="page" @endif>
        <span class="nav-icon" aria-hidden="true">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </span>
        Seguros fallecido
      </a>

      @if(auth()->user()->hasActiveSubscription())
        <a href="{{ route('tools.ocr.show') }}"
           class="db-nav-item @if(request()->routeIs('tools.ocr.*')) active @endif"
           @if(request()->routeIs('tools.ocr.*')) aria-current="page" @endif>
          <span class="nav-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
          </span>
          OCR Documental
        </a>
        <a href="{{ route('tools.valoracion.show') }}"
           class="db-nav-item @if(request()->routeIs('tools.valoracion.*')) active @endif"
           @if(request()->routeIs('tools.valoracion.*')) aria-current="page" @endif>
          <span class="nav-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </span>
          Valoración daños
        </a>
        <a href="{{ route('tools.jurisprudencia.show') }}"
           class="db-nav-item @if(request()->routeIs('tools.jurisprudencia.*')) active @endif"
           @if(request()->routeIs('tools.jurisprudencia.*')) aria-current="page" @endif>
          <span class="nav-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          </span>
          Jurisprudencia
        </a>
      @else
        <span class="db-nav-item locked" aria-label="OCR Documental — requiere Plan Pro">
          <span class="nav-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
          </span>
          OCR Documental
          <span class="nav-lock" aria-hidden="true">🔒</span>
        </span>
        <span class="db-nav-item locked" aria-label="Valoración — requiere Plan Pro">
          <span class="nav-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/></svg>
          </span>
          Valoración daños
          <span class="nav-lock" aria-hidden="true">🔒</span>
        </span>
        <span class="db-nav-item locked" aria-label="Jurisprudencia — requiere Plan Pro">
          <span class="nav-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          </span>
          Jurisprudencia
          <span class="nav-lock" aria-hidden="true">🔒</span>
        </span>
      @endif
    </div>

    {{-- Cuenta --}}
    <div class="db-nav-section">
      <span class="db-nav-label">Cuenta</span>
      <a href="{{ route('subscription.plans') }}"
         class="db-nav-item @if(request()->routeIs('subscription.*')) active @endif"
         @if(request()->routeIs('subscription.*')) aria-current="page" @endif>
        <span class="nav-icon" aria-hidden="true">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </span>
        Plan &amp; Facturación
        @if(auth()->user()->hasActiveSubscription())
          <span class="db-pro-badge" aria-label="Plan Pro activo">Pro</span>
        @endif
      </a>
    </div>

  </nav>

  {{-- User profile --}}
  <div class="db-user">
    <div class="db-user-info">
      <div class="db-avatar" aria-hidden="true">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      </div>
      <div>
        <div class="db-user-name">{{ auth()->user()->name }}</div>
        <div class="db-user-email">{{ auth()->user()->email }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="db-logout">Cerrar sesión</button>
    </form>
  </div>

</aside>

{{-- ══════ TOPBAR ══════ --}}
<header class="db-topbar" role="banner">
  <div class="db-topbar-left">
    <button class="db-hamburger" id="sidebar-toggle"
            aria-label="Abrir menú lateral" aria-expanded="false" aria-controls="db-sidebar">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <line x1="3" y1="6"  x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>
    <a class="db-topbar-logo" href="{{ route('dashboard') }}" aria-label="Reclama — Panel">
      <div class="logo-mark" aria-hidden="true">R</div>
      <span class="logo-word">Reclama</span>
    </a>
    <span class="db-page-title db-topbar-title" aria-label="Página actual">
      @yield('page-title', 'Panel')
    </span>
  </div>
  <div class="db-topbar-right">
    <button class="db-bell" aria-label="Notificaciones" title="Notificaciones">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
    </button>
    <a href="{{ route('claim.create') }}" class="btn btn-primary btn-sm">
      + Nueva reclamación
    </a>
  </div>
</header>

{{-- ══════ MAIN CONTENT ══════ --}}
<main class="db-content" id="main-content" tabindex="-1">
  <div class="db-inner">

    {{-- Flash messages — WCAG 4.1.3 --}}
    <div aria-live="polite" aria-atomic="true">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
          <span aria-hidden="true">✓</span>
          <div><strong>Hecho: </strong>{{ session('success') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-6" role="alert">
          <span aria-hidden="true">!</span>
          <div><strong>Error: </strong>{{ session('error') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif
      @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-6" role="alert">
          <span aria-hidden="true">i</span>
          <div>{{ session('info') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif
      @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-6" role="alert">
          <span aria-hidden="true">⚠</span>
          <div>{{ session('warning') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif
    </div>

    @yield('content')
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar mobile toggle
(function() {
  var toggle  = document.getElementById('sidebar-toggle');
  var sidebar = document.getElementById('db-sidebar');
  var overlay = document.getElementById('sidebar-overlay');
  if (!toggle || !sidebar || !overlay) return;

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    overlay.setAttribute('aria-hidden', 'false');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    overlay.setAttribute('aria-hidden', 'true');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', function() {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });
  overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && sidebar.classList.contains('open')) { closeSidebar(); }
  });
})();
</script>
@stack('scripts')
</body>
</html>
