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
                {{-- Icono de Gastos con Glow Tizzila --}}
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-red-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-red-500 border border-red-500/50 shadow-[0_10px_20px_rgba(239,68,68,0.2)]">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Gestión de <span class="text-[#f3c444]">Gastos</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Control de Egresos y Operaciones
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('expenses.create') }}"
                   class="px-6 py-3 bg-[#f3c444] text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg shadow-[#f3c444]/20 flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nuevo Gasto
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-8 space-y-8">

        {{-- 📊 KPI CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Total Histórico --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-red-500/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block  text-center md:text-left">Total Gastos</p>
                <h2 class="text-3xl font-black text-red-500 tracking-tighter text-center md:text-left">
                    ${{ number_format($total, 0, ',', '.') }}
                </h2>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500/40 w-full"></div>
                </div>
            </div>

            {{-- Gasto Mes --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-orange-500/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block  text-center md:text-left">Gasto del Mes</p>
                <h2 class="text-3xl font-black text-orange-400 tracking-tighter text-center md:text-left">
                    ${{ number_format($thisMonth, 0, ',', '.') }}
                </h2>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-400/40 w-2/3"></div>
                </div>
            </div>

            {{-- Documentos (Referencial) --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#f3c444]/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block  text-center md:text-left">Documentos</p>
                <h2 class="text-3xl font-black text-white tracking-tighter text-center md:text-left">
                    {{ $expenses->total() }} <span class="text-[#f3c444] text-lg font-bold">Docs</span>
                </h2>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full"></div>
            </div>

            {{-- Promedio (Referencial) --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-500/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block  text-center md:text-left">Promedio Gasto</p>
                <h2 class="text-3xl font-black text-blue-400 tracking-tighter text-center md:text-left">
                    ${{ number_format($expenses->total() > 0 ? $total / $expenses->total() : 0, 0, ',', '.') }}
                </h2>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full"></div>
            </div>

        </div>

        {{-- 🔍 FILTROS AVANZADOS --}}
        <div class="group relative bg-[#0a0a0c] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <form method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                
                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block ">Desde</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-[#f3c444] focus:ring-0 transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block ">Hasta</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-[#f3c444] focus:ring-0 transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block ">Categoría</label>
                    <select name="category_id" class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 appearance-none focus:border-[#f3c444] transition-all uppercase">
                        <option value="">TODAS</option>
                        @foreach(\App\Models\Expenses\ExpenseCategory::all() as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block ">Proveedor</label>
                    <select name="provider_id" class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 appearance-none focus:border-[#f3c444] transition-all uppercase">
                        <option value="">TODOS</option>
                        @foreach(\App\Models\Poultry\Provider::where('status','active')->get() as $p)
                            <option value="{{ $p->id }}" @selected(request('provider_id') == $p->id)>{{ $p->business_name }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="h-[46px] px-8 bg-white/5 border border-white/10 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-white/10 hover:border-[#f3c444]/50 transition-all active:scale-95">
                    <i class="fas fa-filter mr-2 text-[#f3c444]"></i> Aplicar Filtros
                </button>
            </form>
        </div>

        {{-- 📋 TABLA DE GASTOS --}}
        <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/40 border-b border-white/5">
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Documento / Ref</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Proveedor</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Categoría</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Fecha Emisión</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Egreso Total</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($expenses as $expense)
                            <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                                
                                {{-- DOCUMENTO --}}
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center border border-red-500/20 group-hover:border-red-500/50 transition-all">
                                            <i class="fas fa-receipt text-[10px] text-red-500"></i>
                                        </div>
                                        <span class="text-sm font-black text-white  uppercase tracking-tight group-hover:text-[#f3c444] transition-colors">
                                            {{ $expense->document_number ?? '#S/R' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- PROVEEDOR --}}
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-zinc-300 uppercase leading-none">
                                            {{ $expense->provider->business_name ?? 'GASTO INTERNO' }}
                                        </span>
                                        <span class="text-[9px] font-bold text-zinc-600 uppercase mt-1">
                                            {{ $expense->provider->tax_id ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- CATEGORÍA --}}
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 text-[9px] font-black rounded-lg border bg-zinc-800 text-zinc-400 border-white/5 tracking-widest uppercase ">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>

                                {{-- FECHA --}}
                                <td class="px-8 py-6">
                                    <span class="text-[11px] font-black text-zinc-400 uppercase ">
                                        {{ $expense->expense_date->format('d M, Y') }}
                                    </span>
                                </td>

                                {{-- TOTAL --}}
                                <td class="px-8 py-6">
                                    <span class="text-base font-black text-red-500 leading-none">
                                        ${{ number_format($expense->total, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- ACCIONES --}}
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{-- Ver --}}
                                        <a href="{{ route('expenses.show', $expense->id) }}" title="Detalles"
                                           class="h-9 w-9 flex items-center justify-center bg-blue-500/10 border border-blue-500/20 rounded-xl text-blue-400 hover:bg-blue-500 hover:text-white transition-all duration-300">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('expenses.edit', $expense->id) }}" title="Editar"
                                           class="h-9 w-9 flex items-center justify-center bg-[#f3c444]/10 border border-[#f3c444]/20 rounded-xl text-[#f3c444] hover:bg-[#f3c444] hover:text-black transition-all duration-300">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>

                                        {{-- Eliminar --}}
                                        <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Borrar"
                                                onclick="return confirm('¿Eliminar este registro de gasto?')"
                                                class="h-9 w-9 flex items-center justify-center bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <i class="fas fa-search text-4xl text-zinc-800 mb-4 block"></i>
                                    <span class="text-zinc-600 font-black uppercase tracking-widest text-xs ">Sin registros de gastos encontrados</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINACIÓN --}}
        @if($expenses->hasPages())
        <div class="px-8 py-6 bg-[#0a0a0c] rounded-[2.5rem] border border-white/5">
            {{ $expenses->links() }}
        </div>
        @endif

    </div>
</x-app-layout>