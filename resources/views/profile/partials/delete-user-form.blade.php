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
    <h2 class="text-lg font-extrabold text-red-400 flex items-center gap-2">
        <i class="fas fa-triangle-exclamation"></i>
        Zona crítica
    </h2>

    <p class="mt-1 text-sm text-gray-400">
        Eliminar tu cuenta es una acción <strong>permanente e irreversible</strong>.
        Todos tus datos serán eliminados del sistema.
    </p>
</header>

<!-- Botón principal -->
<x-danger-button
    x-data
    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    class="bg-red-600 hover:bg-red-700 text-white font-bold"
>
    Eliminar cuenta definitivamente
</x-danger-button>

<!-- Modal de confirmación -->
<x-modal
    name="confirm-user-deletion"
    :show="$errors->userDeletion->isNotEmpty()"
    focusable
>
    <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-xl font-extrabold text-red-400 flex items-center gap-2">
            <i class="fas fa-triangle-exclamation"></i>
            Confirmar eliminación de cuenta
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            Esta acción <strong>no se puede deshacer</strong>.
            Para continuar, confirma tu contraseña.
        </p>

        <!-- Password -->
        <div class="mt-6">
            <x-input-label
                for="password"
                :value="__('Contraseña')"
                class="text-gray-300"
            />

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-full bg-[#070a13] border border-red-500/30 text-white focus:border-red-400 focus:ring-red-400"
                placeholder="••••••••"
            />

            <x-input-error
                :messages="$errors->userDeletion->get('password')"
                class="mt-2 text-red-400"
            />
        </div>

        <!-- Acciones -->
        <div class="mt-8 flex justify-end gap-3">
            <x-secondary-button
                x-on:click="$dispatch('close')"
                class="border-gray-600 text-gray-300 hover:text-white"
            >
                Cancelar
            </x-secondary-button>

            <x-danger-button class="bg-red-600 hover:bg-red-700 font-bold">
                Sí, eliminar cuenta
            </x-danger-button>
        </div>
    </form>
</x-modal>
</section>
