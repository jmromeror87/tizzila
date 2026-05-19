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
        <i class="fas fa-id-card text-yellow-400"></i>
        Información del perfil
    </h2>

    <p class="mt-1 text-sm text-gray-400">
        Actualiza tu nombre y correo electrónico asociados a tu cuenta.
    </p>
</header>

<!-- Verificación email -->
<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>

<!-- Form -->
<form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <!-- Nombre -->
    <div>
        <x-input-label for="name" :value="__('Nombre')" class="text-gray-300" />
        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
            :value="old('name', $user->name)"
            required
            autofocus
            autocomplete="name"
        />
        <x-input-error class="mt-2 text-red-400" :messages="$errors->get('name')" />
    </div>

    <!-- Email -->
    <div>
        <x-input-label for="email" :value="__('Correo electrónico')" class="text-gray-300" />
        <x-text-input
            id="email"
            name="email"
            type="email"
            class="mt-1 block w-full bg-[#070a13] border border-yellow-500/20 text-white focus:border-yellow-400 focus:ring-yellow-400"
            :value="old('email', $user->email)"
            required
            autocomplete="username"
        />
        <x-input-error class="mt-2 text-red-400" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                <p class="text-sm text-yellow-400">
                    Tu correo electrónico no está verificado.
                </p>

                <button
                    form="send-verification"
                    class="mt-2 text-sm font-semibold text-yellow-300 hover:text-yellow-200 underline"
                >
                    Reenviar correo de verificación
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-green-400 font-semibold">
                        ✔ Enlace de verificación enviado correctamente.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <!-- Acciones -->
    <div class="flex items-center gap-4">
        <x-primary-button class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold">
            Guardar cambios
        </x-primary-button>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-400 font-semibold"
            >
                ✔ Perfil actualizado
            </p>
        @endif
    </div>

</form>
</section>
