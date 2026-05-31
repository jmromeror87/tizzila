{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-redo-alt text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Gastos <span class="text-yellow-500">Recurrentes</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">Automatización de Egresos</p>
                </div>
            </div>
            <a href="{{ route('recurring-expenses.create') }}"
               class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                <i class="fas fa-plus"></i> Programar Nuevo
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            @if($recurrings->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Nombre del Gasto</th>
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Categoría</th>
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Monto Estimado</th>
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-center">Frecuencia</th>
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-center">Próxima Ejecución</th>
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-center">Estado</th>
                                <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($recurrings as $r)
                                <tr class="group hover:bg-white/[0.02] transition-all">
                                    <td class="px-6 py-4 text-sm font-black text-white uppercase group-hover:text-yellow-500 transition-colors">
                                        {{ $r->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-[9px] font-black text-zinc-400 border border-white/5 rounded-lg uppercase bg-black/40 tracking-widest">
                                            {{ $r->category->name ?? 'Sin Categoría' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-yellow-500 text-sm font-mono">
                                        ${{ number_format($r->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                                            <i class="far fa-clock mr-1 text-yellow-500/40"></i>{{ $r->frequency }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <p class="text-xs font-black text-white uppercase">{{ \Carbon\Carbon::parse($r->next_run_date)->translatedFormat('d M, Y') }}</p>
                                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($r->next_run_date)->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-full border
                                            {{ $r->is_active ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-400' }}">
                                            {{ $r->is_active ? 'Activo' : 'Pausado' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('recurring-expenses.edit', $r) }}"
                                               class="h-8 w-8 flex items-center justify-center bg-yellow-500/10 border border-yellow-500/20 rounded-lg text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <form method="POST" action="{{ route('recurring-expenses.destroy', $r) }}"
                                                  onsubmit="return confirm('¿Desea eliminar esta recurrencia?')">
                                                @csrf @method('DELETE')
                                                <button class="h-8 w-8 flex items-center justify-center bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-24 text-center">
                    <i class="fas fa-calendar-times text-5xl text-zinc-800 block mb-3"></i>
                    <p class="text-zinc-600 font-black uppercase tracking-[0.3em] text-xs mb-6">No hay gastos programados</p>
                    <a href="{{ route('recurring-expenses.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-500 hover:bg-yellow-400 text-black text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-plus"></i> Programar Mi Primer Gasto
                    </a>
                </div>
            @endif
        </div>

        @if($recurrings->hasPages())
            <div>{{ $recurrings->links() }}</div>
        @endif

    </div>
</x-app-layout>
