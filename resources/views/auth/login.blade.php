{{-- TIZZILA APP - © 2026 --}}
@extends('layouts.auth')

@section('content')
<div class="login-container">

    {{-- SIDEBAR --}}
    <div class="login-sidebar">
        <div class="d-flex align-items-center mb-5">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="#">
                <i class="fas fa-egg fs-2" style="color:#eab308;"></i>
                <span>Tizzila<span style="color:#eab308;">App</span></span>
            </a>
        </div>
        <h1 class="display-4 fw-800 mb-4">
            Orquestación <span class="text-gradient-yellow">Avícola Inteligente</span>
        </h1>
        <p class="fs-5 opacity-75">
            Acceda a su plataforma de control avícola y gestione su rentabilidad con datos reales de Colombia y LATAM.
        </p>
        <div class="mt-5" style="display:flex;flex-direction:column;gap:12px;">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-robot" style="color:#eab308;font-size:14px;width:18px;text-align:center;"></i>
                <span>Predicción de precios con IA</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-truck-fast" style="color:#eab308;font-size:14px;width:18px;text-align:center;"></i>
                <span>Control total de distribución masiva</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-chart-line" style="color:#eab308;font-size:14px;width:18px;text-align:center;"></i>
                <span>Monitoreo en tiempo real</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-map-location-dot" style="color:#eab308;font-size:14px;width:18px;text-align:center;"></i>
                <span>Tracking y tiempos de entrega</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-signature" style="color:#eab308;font-size:14px;width:18px;text-align:center;"></i>
                <span>Entrega, validación y novedades</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-coins" style="color:#eab308;font-size:14px;width:18px;text-align:center;"></i>
                <span>Gastos internos optimizados</span>
            </div>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <div class="login-form-section">

        <h3 class="fw-800 mb-1">Bienvenido</h3>
        <p class="text-muted mb-4">Ingrese sus credenciales para continuar</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="form-label fw-bold" style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;">Correo electrónico</label>
                <div style="position:relative;">
                    <i class="fas fa-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#4b5563;font-size:13px;pointer-events:none;"></i>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        style="padding-left:40px;"
                        value="{{ old('email') }}"
                        required autofocus
                        placeholder="usuario@empresa.com"
                    >
                </div>
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label class="form-label fw-bold" style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;">Contraseña</label>
                <div style="position:relative;">
                    <i class="fas fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#4b5563;font-size:13px;pointer-events:none;"></i>
                    <input
                        type="password"
                        name="password"
                        id="password-input"
                        class="form-control"
                        style="padding-left:40px;padding-right:44px;"
                        required
                        placeholder="••••••••"
                    >
                    <button type="button" id="toggle-password"
                        onclick="togglePassword()"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#4b5563;padding:4px;line-height:1;">
                        <i class="fas fa-eye" id="eye-icon" style="font-size:14px;"></i>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Mantener sesión iniciada</label>
            </div>

            <button type="submit" class="btn btn-primary-custom">
                <i class="fas fa-right-to-bracket me-2"></i> Iniciar Sesión
            </button>
        </form>

        <div class="mt-5 text-center opacity-50 small">
            © 2026 Tizzila App — Seguridad de Grado Empresarial
        </div>
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password-input');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        document.getElementById('toggle-password').style.color = '#eab308';
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        document.getElementById('toggle-password').style.color = '#4b5563';
    }
}
</script>
@endsection
