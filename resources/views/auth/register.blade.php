{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-guest-layout>

<div class="login-container">

```
<!-- Sidebar -->
<div class="login-sidebar">
    <div>
        <h1 class="display-4 fw-800 mb-3">
            Tizzila<span class="text-gradient-yellow">App</span>
        </h1>

        <p class="fs-5 opacity-75 mb-4">
            Plataforma de orquestación avícola inteligente
        </p>

        <div class="space-y-3 mt-4">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-check-circle text-warning"></i>
                <span>Programación y forecast de pedidos</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-check-circle text-warning"></i>
                <span>Control financiero y rentabilidad</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-check-circle text-warning"></i>
                <span>BI + IA para toma de decisiones</span>
            </div>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="login-form-section flex items-center justify-center">

    <div class="w-full max-w-md bg-[#111827] border border-yellow-500/10 rounded-2xl shadow-xl p-8">

        <h3 class="fw-800 text-white text-2xl mb-1">
            Crear cuenta
        </h3>
        <p class="text-gray-400 mb-6">
            Comience a usar Tizzila App en minutos
        </p>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Nombre -->
            <div>
                <x-input-label for="name" :value="__('Nombre completo')" class="text-gray-300" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Correo electrónico')" class="text-gray-300" />
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
                    :value="old('email')"
                    required
                    autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Contraseña')" class="text-gray-300" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
                    required
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <!-- Confirm -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="text-gray-300" />
                <x-text-input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
                    required
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('login') }}"
                   class="text-sm font-semibold text-yellow-400 hover:text-yellow-300">
                    ¿Ya tienes cuenta?
                </a>

                <x-primary-button class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-6">
                    Crear cuenta
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} Tizzila App · Seguridad empresarial
        </div>

    </div>

</div>
```

</div>

</x-guest-layout>
