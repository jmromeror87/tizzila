{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Impuestos <span class="text-yellow-500">DIAN</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Configuración de Tributos Nacionales
                </p>
            </div>
            <a href="{{ route('taxes.create') }}"
               class="h-9 px-5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all active:scale-95 w-fit">
                <i class="fas fa-plus text-xs"></i> Nueva Categoría
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-3 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false"><i class="fas fa-times text-[10px]"></i></button>
            </div>
        @endif

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Código</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Nombre Tributo</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-center">Tasa</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-center">Tipo DIAN</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-center">Estado</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($taxes as $tax)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-5 py-4">
                                    <span class="text-sm font-black text-yellow-500 font-mono">{{ $tax->code }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs font-black text-white uppercase group-hover:text-yellow-400 transition-colors">
                                        {{ $tax->name }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black font-mono bg-black/40 border border-white/5 text-white">
                                        {{ number_format($tax->percent ?? $tax->percentage ?? 0, 2) }}<span class="text-yellow-500">%</span>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase bg-zinc-500/10 border border-zinc-500/20 text-zinc-400">
                                        {{ $tax->dian_tax_code ?? $tax->type ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($tax->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase bg-zinc-500/10 text-zinc-500 border border-zinc-500/20">
                                            <i class="fas fa-ban text-[8px]"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('taxes.edit', $tax->id) }}"
                                           class="h-8 w-8 inline-flex items-center justify-center bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-yellow-500 hover:border-yellow-500/30 transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                        <form action="{{ route('taxes.destroy', $tax->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('¿Eliminar este impuesto permanentemente?')"
                                                    class="h-8 w-8 inline-flex items-center justify-center bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-red-500 hover:border-red-500/30 transition-all">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <i class="fas fa-receipt text-4xl text-gray-800 block mb-3"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin impuestos configurados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($taxes, 'hasPages') && $taxes->hasPages())
            <div class="flex items-center justify-between px-1">
                <a href="{{ $taxes->previousPageUrl() }}"
                   class="h-9 w-9 rounded-xl bg-[#0d121f] border border-white/5 flex items-center justify-center text-gray-500 transition-all {{ $taxes->onFirstPage() ? 'opacity-20 pointer-events-none' : 'hover:border-white/20 hover:text-white' }}">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">
                    Página <span class="text-white">{{ $taxes->currentPage() }}</span> / {{ $taxes->lastPage() }}
                </span>
                <a href="{{ $taxes->nextPageUrl() }}"
                   class="h-9 w-9 rounded-xl bg-[#0d121f] border border-white/5 flex items-center justify-center text-gray-500 transition-all {{ !$taxes->hasMorePages() ? 'opacity-20 pointer-events-none' : 'hover:border-white/20 hover:text-white' }}">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
