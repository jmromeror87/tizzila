{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<section class="space-y-6">
<!-- Header -->
<header>
    <h2 class="text-lg font-extrabold text-white flex items-center gap-2">
        <i class="fas fa-lock text-yellow-400"></i>
        Seguridad de la cuenta
    </h2>

    <p class="mt-1 text-sm text-gray-400">
        Cambia tu contraseña regularmente para mantener tu cuenta protegida.
    </p>
</header>

<!-- Form -->
<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <!-- Contraseña actual -->
    <div>
        <x-input-label
            for="update_password_current_password"
            :value="__('Contraseña actual')"
            class="text-gray-300"
        />
        <x-text-input
            id="update_password_current_password"
            name="current_password"
            type="password"
            class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
            autocomplete="current-password"
        />
        <x-input-error
            class="mt-2 text-red-400"
            :messages="$errors->updatePassword->get('current_password')"
        />
    </div>

    <!-- Nueva contraseña -->
    <div>
        <x-input-label
            for="update_password_password"
            :value="__('Nueva contraseña')"
            class="text-gray-300"
        />
        <x-text-input
            id="update_password_password"
            name="password"
            type="password"
            class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
            autocomplete="new-password"
        />
        <x-input-error
            class="mt-2 text-red-400"
            :messages="$errors->updatePassword->get('password')"
        />
    </div>

    <!-- Confirmación -->
    <div>
        <x-input-label
            for="update_password_password_confirmation"
            :value="__('Confirmar nueva contraseña')"
            class="text-gray-300"
        />
        <x-text-input
            id="update_password_password_confirmation"
            name="password_confirmation"
            type="password"
            class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
            autocomplete="new-password"
        />
        <x-input-error
            class="mt-2 text-red-400"
            :messages="$errors->updatePassword->get('password_confirmation')"
        />
    </div>

    <!-- Acciones -->
    <div class="flex items-center gap-4">
        <x-primary-button class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold">
            Actualizar contraseña
        </x-primary-button>

        @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-400 font-semibold"
            >
                ✔ Contraseña actualizada correctamente
            </p>
        @endif
    </div>

</form>
</section>
