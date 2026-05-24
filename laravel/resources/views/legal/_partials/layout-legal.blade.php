{{-- Reusable layout fragment for legal pages --}}
@push('styles')
<style>
    .legal-doc { max-width: 780px; margin: 0 auto; }
    .legal-doc h1 { font-size: 2rem; font-weight: 800; letter-spacing: -.5px; }
    .legal-doc h2 { font-size: 1.3rem; font-weight: 700; margin-top: 2.5rem; }
    .legal-doc h3 { font-size: 1.05rem; font-weight: 600; margin-top: 1.5rem; }
    .legal-doc p, .legal-doc li { font-size: .94rem; line-height: 1.75; color: #334155; }
    .legal-doc ul, .legal-doc ol { padding-left: 1.5rem; }
    .legal-doc .meta { color: #64748b; font-size: .85rem; margin-bottom: 2rem; }
    .legal-doc .toc {
        background: #f1f5f9;
        border-left: 4px solid var(--color-accent);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: .9rem;
    }
    .legal-doc .toc a { color: var(--color-accent); text-decoration: none; }
    .legal-doc .toc a:hover { text-decoration: underline; }
    .legal-doc .highlight {
        background: #eff6ff;
        border-left: 3px solid var(--color-accent);
        padding: .75rem 1rem;
        margin: 1rem 0;
        border-radius: 4px;
    }
</style>
@endpush
