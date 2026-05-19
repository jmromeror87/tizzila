{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('poultry.drivers.index') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Editar <span class="text-yellow-500">Conductor</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    {{ $driver->full_name }} · DRV-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}
                </p>
            </div>
            <span @class([
                'ml-auto px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border',
                'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' => $driver->status === 'active',
                'bg-rose-500/10 border-rose-500/20 text-rose-500' => $driver->status === 'inactive',
            ])>
                {{ $driver->status === 'active' ? 'Operativo' : 'Inactivo' }}
            </span>
        </div>
    </x-slot>

    <div class="py-4 max-w-2xl">
        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <form method="POST" action="{{ route('poultry.drivers.update', $driver) }}">
                @method('PUT')
                @include('poultry.drivers._form', ['driver' => $driver])
            </form>
        </div>

        <div class="mt-4 flex justify-between items-center text-[9px] text-gray-700 font-bold uppercase tracking-widest">
            <span>Actualizado: {{ $driver->updated_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>
</x-app-layout>
