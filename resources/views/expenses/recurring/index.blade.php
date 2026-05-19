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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4 py-2">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-[#f3c444]/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-[#f3c444] border border-[#f3c444]/50">
                        <i class="fas fa-redo-alt text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Gastos <span class="text-[#f3c444]">Recurrentes</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1 ">Automatización de Egresos</p>
                </div>
            </div>

            <a href="{{ route('recurring-expenses.create') }}"
               class="px-8 py-4 bg-[#f3c444] text-black text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-[#f3c444]/10 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
               <i class="fas fa-plus text-xs"></i> Programar Nuevo
            </a>
        </div>
    </x-slot>

    <div class="p-8 max-w-7xl mx-auto">

        {{-- 🔥 ALERTAS --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        

        {{-- 📊 CONTENEDOR TABLA --}}
        <div class="bg-[#0a0a0c] border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#f3c444]/20 to-transparent"></div>

            @if($recurrings->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] ">Nombre del Gasto</th>
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] ">Categoría</th>
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]  text-right">Monto Estimado</th>
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]  text-center">Frecuencia</th>
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]  text-center">Próxima Ejecución</th>
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]  text-center">Estado</th>
                                <th class="p-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]  text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/5">
                            @foreach($recurrings as $r)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                
                                <td class="p-6 font-black text-white text-sm uppercase tracking-tight">
                                    {{ $r->name }}
                                </td>

                                <td class="p-6">
                                    <span class="text-[10px] font-black text-zinc-400 border border-white/10 px-3 py-1.5 rounded-lg uppercase bg-black/40">
                                        {{ $r->category->name ?? 'Sin Categoría' }}
                                    </span>
                                </td>

                                <td class="p-6 text-right font-black text-[#f3c444] text-lg">
                                    ${{ number_format($r->amount, 0, ',', '.') }}
                                </td>

                                <td class="p-6 text-center">
                                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ">
                                        <i class="far fa-clock mr-1 text-[#f3c444]/40"></i> {{ $r->frequency }}
                                    </span>
                                </td>

                                <td class="p-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-white font-black text-xs uppercase tracking-tighter">
                                            {{ \Carbon\Carbon::parse($r->next_run_date)->format('d M, Y') }}
                                        </span>
                                        <span class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest">
                                            {{ \Carbon\Carbon::parse($r->next_run_date)->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>

                                <td class="p-6 text-center">
                                    <span class="px-4 py-2 text-[9px] font-black uppercase tracking-[0.15em] rounded-full border 
                                        {{ $r->is_active ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-400' }}">
                                        {{ $r->is_active ? 'Activo' : 'Pausado' }}
                                    </span>
                                </td>

                                <td class="p-6 text-right">
                                    <div class="flex justify-end gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('recurring-expenses.edit', $r) }}"
                                           class="h-9 w-9 bg-white/5 border border-white/10 text-white rounded-xl flex items-center justify-center hover:bg-[#f3c444] hover:text-black transition-all shadow-lg">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>

                                        <form method="POST" action="{{ route('recurring-expenses.destroy', $r) }}"
                                              onsubmit="return confirm('¿Desea eliminar esta recurrencia?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="h-9 w-9 bg-red-500/10 border border-red-500/20 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg">
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
                {{-- 🏜️ ESTADO VACÍO --}}
                <div class="p-20 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-[#f3c444]/5 blur-[100px] rounded-full"></div>
                    <div class="relative z-10">
                        <i class="fas fa-calendar-times text-6xl text-zinc-800 mb-6"></i>
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2">No hay gastos programados</h3>
                        <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest mb-8">Comienza a automatizar tus pagos mensuales aquí.</p>
                        
                        <a href="{{ route('recurring-expenses.create') }}"
                           class="inline-flex items-center gap-3 px-8 py-4 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#f3c444] hover:text-black transition-all">
                            Programar Mi Primer Gasto
                        </a>
                    </div>
                </div>
            @endif

        </div>

        {{-- 📄 PAGINACIÓN --}}
        @if($recurrings->hasPages())
            <div class="mt-8 px-4 font-black">
                {{ $recurrings->links() }}
            </div>
        @endif

    </div>
</x-app-layout>