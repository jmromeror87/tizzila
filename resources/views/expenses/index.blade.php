{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400">
                    <i class="fas fa-wallet text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Gestión de <span class="text-yellow-500">Gastos</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">Control de Egresos y Operaciones</p>
                </div>
            </div>
            <a href="{{ route('expenses.create') }}"
               class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                <i class="fas fa-plus"></i> Nuevo Gasto
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[#0d121f] border border-red-500/20 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Gastos</p>
                <h2 class="text-2xl font-black text-red-400">${{ number_format($total, 0, ',', '.') }}</h2>
                <div class="mt-3 h-0.5 w-full bg-red-500/20 rounded-full"></div>
            </div>
            <div class="bg-[#0d121f] border border-orange-500/20 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Gasto del Mes</p>
                <h2 class="text-2xl font-black text-orange-400">${{ number_format($thisMonth, 0, ',', '.') }}</h2>
                <div class="mt-3 h-0.5 w-full bg-orange-500/20 rounded-full"></div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Documentos</p>
                <h2 class="text-2xl font-black text-white">{{ $expenses->total() }} <span class="text-yellow-500 text-lg font-bold">Docs</span></h2>
                <div class="mt-3 h-0.5 w-full bg-white/5 rounded-full"></div>
            </div>
            <div class="bg-[#0d121f] border border-blue-500/20 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Promedio Gasto</p>
                <h2 class="text-2xl font-black text-blue-400">${{ number_format($expenses->total() > 0 ? $total / $expenses->total() : 0, 0, ',', '.') }}</h2>
                <div class="mt-3 h-0.5 w-full bg-blue-500/20 rounded-full"></div>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Desde</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Hasta</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Categoría</label>
                    <select name="category_id" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                        <option value="">TODAS</option>
                        @foreach(\App\Models\Expenses\ExpenseCategory::all() as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Proveedor</label>
                    <select name="provider_id" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                        <option value="">TODOS</option>
                        @foreach(\App\Models\Poultry\Provider::where('status','active')->get() as $p)
                            <option value="{{ $p->id }}" @selected(request('provider_id') == $p->id)>{{ $p->business_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="h-[46px] bg-white/5 hover:bg-white/10 border border-white/10 hover:border-yellow-500/30 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-yellow-500"></i> Aplicar Filtros
                </button>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Documento / Ref</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Proveedor</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Categoría</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Fecha Emisión</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Egreso Total</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($expenses as $expense)
                            <tr class="group hover:bg-white/[0.02] transition-all">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                                            <i class="fas fa-receipt text-red-400 text-[10px]"></i>
                                        </div>
                                        <span class="text-sm font-black text-white uppercase group-hover:text-yellow-500 transition-colors">
                                            {{ $expense->document_number ?? '#S/R' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[11px] font-black text-zinc-300 uppercase leading-none">{{ $expense->provider->business_name ?? 'GASTO INTERNO' }}</p>
                                    <p class="text-[8px] font-bold text-zinc-600 uppercase mt-0.5">{{ $expense->provider->tax_id ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[9px] font-black rounded-lg border bg-zinc-800/60 text-zinc-400 border-white/5 uppercase tracking-widest">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[11px] font-black text-zinc-400 uppercase">
                                    {{ $expense->expense_date->translatedFormat('d M, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-red-400 font-mono">${{ number_format($expense->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('expenses.show', $expense->id) }}"
                                           class="h-8 w-8 flex items-center justify-center bg-blue-500/10 border border-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500 hover:text-white transition-all">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense->id) }}"
                                           class="h-8 w-8 flex items-center justify-center bg-yellow-500/10 border border-yellow-500/20 rounded-lg text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('¿Eliminar este registro de gasto?')"
                                                class="h-8 w-8 flex items-center justify-center bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <i class="fas fa-search text-5xl text-zinc-800 block mb-3"></i>
                                    <p class="text-zinc-600 font-black uppercase tracking-[0.3em] text-xs">Sin registros de gastos encontrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINACIÓN --}}
        @if($expenses->hasPages())
            <div>{{ $expenses->links() }}</div>
        @endif

    </div>
</x-app-layout>
