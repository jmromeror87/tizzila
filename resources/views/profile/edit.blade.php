{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-extrabold text-white">
                Perfil de Usuario
            </h2>
        <span class="px-4 py-1 rounded-full text-sm font-bold bg-yellow-500 text-black">
            {{ auth()->user()->role->label }}
        </span>
    </div>
</x-slot>

<div class="py-10 bg-[#070a13] min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

        <!-- Información del Perfil -->
        <section class="bg-[#111827] border border-yellow-500/10 rounded-xl shadow-lg">
            <div class="border-b border-yellow-500/10 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user text-yellow-400"></i>
                    Información del perfil
                </h3>
                <p class="text-sm text-gray-400 mt-1">
                    Actualiza tu nombre, correo electrónico y datos básicos.
                </p>
            </div>

            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <!-- Seguridad -->
        <section class="bg-[#111827] border border-yellow-500/10 rounded-xl shadow-lg">
            <div class="border-b border-yellow-500/10 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-lock text-yellow-400"></i>
                    Seguridad
                </h3>
                <p class="text-sm text-gray-400 mt-1">
                    Cambia tu contraseña y mantén segura tu cuenta.
                </p>
            </div>

            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <!-- Zona Crítica -->
        <section class="bg-[#111827] border border-red-500/20 rounded-xl shadow-lg">
            <div class="border-b border-red-500/20 px-6 py-4">
                <h3 class="text-lg font-bold text-red-400 flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i>
                    Zona crítica
                </h3>
                <p class="text-sm text-gray-400 mt-1">
                    Acciones irreversibles sobre tu cuenta.
                </p>
            </div>

            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </section>

    </div>
</div>
</x-app-layout>
