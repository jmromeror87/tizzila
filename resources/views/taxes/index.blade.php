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
    <div class="py-12 bg-zinc-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 bg-emerald-500/10 border-l-4 border-emerald-500 rounded-r-2xl animate-fade-in-down">
                    <div class="flex-shrink-0 bg-emerald-500 p-2 rounded-lg">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    <div class="ml-4 text-emerald-400 font-bold uppercase text-[10px] tracking-[0.2em]">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <div class="flex items-center gap-5">
                    <div class="bg-yellow-500 p-4 rounded-[2rem] shadow-[0_0_30px_rgba(234,179,8,0.2)]">
                        <i class="fas fa-receipt text-black text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">
                            Impuestos <span class="text-yellow-500 ">DIAN</span>
                        </h2>
                        <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest mt-1">Configuración de tributos nacionales</p>
                    </div>
                </div>
                
                <a href="{{ route('taxes.create') }}" 
                   class="group relative inline-flex items-center justify-center px-8 py-4 font-black text-xs uppercase tracking-widest text-black bg-yellow-500 rounded-2xl transition-all duration-300 hover:bg-white hover:scale-105 active:scale-95 shadow-[0_10px_40px_rgba(234,179,8,0.2)]">
                    <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                    Nueva Categoría
                </a>
            </div>

            <div class="bg-zinc-900 overflow-hidden shadow-3xl rounded-[2.5rem] border border-zinc-800">
                <div class="p-2 sm:p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                                    <th class="px-6 py-4 text-left">Código</th>
                                    <th class="px-6 py-4 text-left">Nombre Tributo</th>
                                    <th class="px-6 py-4 text-center">Tasa</th>
                                    <th class="px-6 py-4 text-center">Tipo</th>
                                    <th class="px-6 py-4 text-center">Estado</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-0">
                                @forelse($taxes as $tax)
                                <tr class="group bg-zinc-800/50 hover:bg-zinc-800 transition-all duration-300">
                                    <td class="px-6 py-5 first:rounded-l-2xl border-y border-l border-transparent group-hover:border-zinc-700">
                                        <span class="font-black text-yellow-500 font-mono text-xl tracking-tighter">{{ $tax->code }}</span>
                                    </td>
                                    <td class="px-6 py-5 border-y border-transparent group-hover:border-zinc-700">
                                        <div class="font-black text-zinc-100 uppercase text-sm tracking-tight">{{ $tax->name }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center border-y border-transparent group-hover:border-zinc-700">
                                        <span class="inline-block bg-zinc-950 text-white border border-zinc-700 px-4 py-1.5 rounded-xl font-black font-mono shadow-inner">
                                            {{ number_format($tax->percent, 2) }}<span class="text-yellow-500 ml-0.5">%</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center border-y border-transparent group-hover:border-zinc-700">
                                        <span class="text-[10px] font-bold py-1 px-3 bg-zinc-900 rounded-lg text-zinc-400 border border-zinc-800 uppercase">
                                            {{ $tax->dian_tax_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center border-y border-transparent group-hover:border-zinc-700">
                                        @if($tax->is_active)
                                            <span class="flex items-center justify-center gap-1.5 text-emerald-500 text-[10px] font-black uppercase tracking-tighter">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="flex items-center justify-center gap-1.5 text-zinc-600 text-[10px] font-black uppercase tracking-tighter">
                                                <span class="h-2 w-2 rounded-full bg-zinc-700"></span>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 last:rounded-r-2xl border-y border-r border-transparent group-hover:border-zinc-700 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('taxes.edit', $tax->id) }}" 
                                               class="h-10 w-10 flex items-center justify-center bg-zinc-900 text-zinc-400 hover:text-yellow-500 hover:border-yellow-500/50 border border-zinc-700 rounded-xl transition-all">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('taxes.destroy', $tax->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('¿Eliminar impuesto permanentemente?')"
                                                        class="h-10 w-10 flex items-center justify-center bg-zinc-900 text-zinc-400 hover:text-red-500 hover:border-red-500/50 border border-zinc-700 rounded-xl transition-all">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-folder-open text-zinc-800 text-6xl mb-4"></i>
                                            <p class="text-zinc-500 font-bold uppercase tracking-widest text-xs">No hay impuestos configurados todavía</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-10 border-t border-zinc-800 pt-6">
                        {{ $taxes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out forwards; }
    </style>
</x-app-layout>