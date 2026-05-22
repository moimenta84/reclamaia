@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h4 class="mb-4">Entrar en ReclamaIA</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e){{ $e }}@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <hr>
            <p class="text-center small text-muted mb-0">¿No tienes cuenta? <a href="{{ route('register') }}">Crear cuenta gratuita</a></p>
        </div>
    </div>
</div>
@endsection
