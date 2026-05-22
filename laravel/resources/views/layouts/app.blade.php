<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ReclamaIA') — Reclamaciones a Aseguradoras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #1a1a2e;
            --accent: #e94560;
        }
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: 700; color: var(--brand) !important; font-size: 1.4rem; }
        .btn-primary { background: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background: #c73652; border-color: #c73652; }
        .card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        footer { border-top: 1px solid #dee2e6; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">⚖️ ReclamaIA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Mis reclamaciones</a></li>
                    @if(!auth()->user()->hasActiveSubscription())
                        <li class="nav-item"><a class="nav-link" href="{{ route('subscription.plans') }}">Plan Pro</a></li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="btn btn-sm btn-outline-secondary">Salir</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Entrar</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-primary text-white" href="{{ route('claim.create') }}">Generar reclamación</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @yield('content')
    </div>
</main>

<footer class="py-4 mt-5 bg-white">
    <div class="container text-center text-muted small">
        <p class="mb-1"><strong>ReclamaIA</strong> — Generador de reclamaciones a aseguradoras</p>
        <p class="mb-0">Los documentos generados son orientativos. Consulte con un abogado para casos complejos.
            La información está protegida por la Ley Orgánica 3/2018 de Protección de Datos.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
