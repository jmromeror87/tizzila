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
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.providers.show', $provider) }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Editar <span class="text-yellow-500">Tercero</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                        {{ $provider->business_name }} · #{{ str_pad($provider->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>
            <div class="text-[9px] text-zinc-600 font-bold uppercase tracking-widest">
                Modificado {{ $provider->updated_at->diffForHumans() }}
            </div>
        </div>
    </x-slot>

    <div class="py-4">

        @if($errors->any())
            <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-black px-5 py-4 rounded-2xl">
                <p class="font-black uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i> Errores de Validación
                </p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>· {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('poultry.providers.update', $provider) }}">
            @csrf @method('PUT')
            <div class="space-y-5">

                {{-- DATOS --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">01</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Datos del Tercero</h3>
                    </div>
                    <div class="p-6">
                        @include('poultry.providers.partials.form', ['provider' => $provider])
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="flex items-center justify-between pt-2">
                    <div>
                        <button type="button"
                            onclick="document.getElementById('delete-provider-form').submit()"
                            class="text-[9px] font-black uppercase tracking-widest text-red-400/50 hover:text-red-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-trash text-[9px]"></i> Eliminar Tercero
                        </button>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('poultry.providers.show', $provider) }}"
                           class="text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-400 text-black px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>

            </div>
        </form>

        {{-- Form de eliminar separado del form de edición --}}
        <form id="delete-provider-form" method="POST" action="{{ route('poultry.providers.destroy', $provider) }}"
              onsubmit="return confirm('¿Eliminar a {{ $provider->business_name }}? Esta acción no se puede deshacer.')">
            @csrf @method('DELETE')
        </form>
    </div>
</x-app-layout>
