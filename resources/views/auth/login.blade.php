{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}



@extends('layouts.auth')

@section('content')
<div class="login-container">

<!-- Branding / Imagen -->
<div class="login-sidebar">
        <container-fluid>
        <div class="d-flex align-items-center mb-5">
             <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="#">
                <i class="fas fa-egg text-primary-yellow fs-2" style="color: #ffc906ff;"></i>
                <span>Tizzila<span class="text-primary-yellow">App</span></span>
            </a>
        </div>
    </container-fluid>
    <h1 class="display-4 fw-800 mb-4">
        Orquestación <span class="text-gradient-yellow">Avícola Inteligente</span>
    </h1>

    <p class="fs-5 opacity-75">
        Acceda a su plataforma de control avícola y gestione su rentabilidad con datos reales de Colombia y LATAM.
    </p>

       <div class="mt-5">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
            <span>Predicción de precios con IA</span>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
            <span>Control total de distribución masiva</span>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
            <span>Monitoreo en tiempo real</span>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
            <span>Tracking y tiempos de entrega</span>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
            <span>Entrega, validación y novedades</span>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
            <span>Gastos internos optimizados</span>
        </div>
    </div>
</div>

<!-- Formulario -->
<div class="login-form-section">

    <h3 class="fw-800 mb-1">Bienvenido</h3>
    <p class="text-muted mb-4">Ingrese sus credenciales para continuar</p>

    {{-- Errores de autenticación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label fw-bold">Correo electrónico</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Contraseña</label>
            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3 form-check">
            <input
                type="checkbox"
                class="form-check-input"
                name="remember"
                id="remember"
            >
            <label class="form-check-label" for="remember">
                Mantener sesión iniciada
            </label>
        </div>

        <button type="submit" class="btn btn-primary-custom">
            Iniciar Sesión
        </button>
    </form>

    <div class="mt-5 text-center opacity-50 small">
        © 2026 Tizzila App — Seguridad de Grado Empresarial
    </div>

</div>


</div>

@endsection
