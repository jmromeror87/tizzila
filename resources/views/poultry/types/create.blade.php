{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('poultry.types.index') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Nuevo <span class="text-yellow-500">Tipo de Ave</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Módulo de Configuración
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-2xl">

            @if ($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-5 py-3 rounded-2xl">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2">Errores de Validación</p>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs font-bold">· {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
                <form method="POST" action="{{ route('poultry.types.store') }}">
                    @include('poultry.types.form')
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
