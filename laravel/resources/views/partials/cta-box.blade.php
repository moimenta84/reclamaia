{{--
  Reusable CTA box for SEO landing pages.
  Variables: $headline (optional), $sub (optional), $size ('sm'|'lg', default 'lg')
--}}
@php $size = $size ?? 'lg'; @endphp
<div class="cta-box cta-box--{{ $size }}" style="
  background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-elevated) 100%);
  border: 1px solid var(--lime-border);
  border-radius: var(--r-xl);
  padding: {{ $size === 'lg' ? 'var(--sp-12) var(--sp-10)' : 'var(--sp-8) var(--sp-8)' }};
  text-align: center;
  position: relative;
  overflow: hidden;
">
  <div style="
    position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(ellipse 70% 60% at 50% 0%, var(--lime-dim) 0%, transparent 70%);
  " aria-hidden="true"></div>
  <div style="position: relative; z-index: 1;">
    <h2 style="font-size: {{ $size === 'lg' ? 'var(--t-3xl)' : 'var(--t-2xl)' }}; font-weight: var(--fw-bold); margin-bottom: var(--sp-3); color: var(--text);">
      {{ $headline ?? 'Genera tu reclamación ahora' }}
    </h2>
    <p style="color: var(--text-3); margin-bottom: var(--sp-6); max-width: 520px; margin-inline: auto;">
      {{ $sub ?? 'Carta formal con base legal española en menos de 90 segundos. Sin abogado, sin esperas.' }}
    </p>
    <div style="display: flex; flex-wrap: wrap; gap: var(--sp-3); justify-content: center;">
      <a href="{{ route('claim.create') }}" class="btn btn-primary btn-lg">
        Reclamar ahora →
      </a>
      <a href="{{ route('seo.reclamaciones') }}" class="btn btn-secondary btn-lg">
        Ver todos los tipos
      </a>
    </div>
  </div>
</div>
