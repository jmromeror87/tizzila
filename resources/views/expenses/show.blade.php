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
                        <i class="fas fa-search-dollar text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Detalle <span class="text-[#f3c444]">Gasto #{{ $expense->id }}</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1 ">Consulta de Registro Contable</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('expenses.index') }}"
                   class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left mr-2 text-zinc-500"></i> Volver
                </a>
                <a href="{{ route('expenses.edit', $expense->id) }}"
                   class="px-6 py-3 bg-[#f3c444] text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg shadow-[#f3c444]/10">
                    <i class="fas fa-edit mr-2"></i> Editar Gasto
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-6xl mx-auto space-y-8">

        {{-- 💳 HEADER SUMMARY --}}
        <div class="relative bg-[#0a0a0c] border border-white/5 rounded-[2.5rem] p-10 overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#f3c444]/5 blur-[80px] rounded-full -mr-20 -mt-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-[#f3c444] uppercase tracking-[0.3em] ">Documento Referencia</span>
                    <h2 class="text-4xl font-black text-white tracking-tighter uppercase">
                        {{ $expense->document_number ?? 'S/N' }}
                    </h2>
                    <p class="text-zinc-500 font-bold text-sm uppercase tracking-widest">
                        Registrado el {{ \Carbon\Carbon::parse($expense->created_at)->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="flex flex-col items-end gap-3">
                    @php
                        $badgeStyles = [
                            'invoice' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'equivalent' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                            'support_doc' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'
                        ];
                        $labels = [
                            'invoice' => 'Factura Electrónica DIAN',
                            'equivalent' => 'Documento Equivalente',
                            'support_doc' => 'Documento Soporte'
                        ];
                    @endphp
                    <span class="px-6 py-2 rounded-full border text-[10px] font-black uppercase tracking-widest {{ $badgeStyles[$expense->document_type] ?? $badgeStyles['support_doc'] }}">
                        {{ $labels[$expense->document_type] ?? 'Documento' }}
                    </span>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest block ">Método de Pago</span>
                        <span class="text-white font-black uppercase text-sm">{{ $expense->payment_method }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🏦 PROVEEDOR Y CATEGORÍA --}}
        <div class="grid md:grid-cols-2 gap-8">
            <div class="card-premium">
                <label class="label-style"><i class="fas fa-address-card text-[#f3c444] mr-2"></i> Proveedor Responsable</label>
                <div class="mt-4">
                    <p class="text-xl font-black text-white uppercase">{{ $expense->provider->business_name ?? 'SIN PROVEEDOR' }}</p>
                    <p class="text-zinc-500 font-bold tracking-widest mt-1 ">{{ $expense->provider->tax_id ?? 'NIT N/A' }}</p>
                </div>
            </div>

            <div class="card-premium">
                <label class="label-style"><i class="fas fa-tag text-[#f3c444] mr-2"></i> Clasificación Contable</label>
                <div class="mt-4">
                    <p class="text-xl font-black text-white uppercase">{{ $expense->category->name }}</p>
                    <p class="text-zinc-500 font-bold tracking-widest mt-1 ">PUC: {{ $expense->category->puc_code }}</p>
                </div>
            </div>
        </div>

        {{-- 💰 PANEL FINANCIERO --}}
        <div class="bg-black rounded-[3rem] p-1 border border-white/5 shadow-inner">
            <div class="grid grid-cols-2 md:grid-cols-4">
                <div class="p-10 border-r border-white/5">
                    <label class="label-style ">Base Gravable</label>
                    <p class="text-2xl font-black text-white mt-2">${{ number_format($expense->tax_base, 2) }}</p>
                </div>
                <div class="p-10 border-r border-white/5">
                    <label class="label-style  text-emerald-500/50">IVA (+)</label>
                    <p class="text-2xl font-black text-emerald-500 mt-2">${{ number_format($expense->iva, 2) }}</p>
                </div>
                <div class="p-10 border-r border-white/5">
                    <label class="label-style  text-red-500/50">Retefuente (-)</label>
                    <p class="text-2xl font-black text-red-500 mt-2">-${{ number_format($expense->retefuente, 2) }}</p>
                </div>
                <div class="p-10 bg-[#f3c444]/5 rounded-r-[3rem]">
                    <label class="label-style  text-[#f3c444]">Total Neto</label>
                    <p class="text-3xl font-black text-white mt-2">${{ number_format($expense->total, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- 📝 DESCRIPCIÓN Y LOGS --}}
        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2 card-premium space-y-4">
                <label class="label-style">Justificación del Egreso</label>
                <p class="text-zinc-400 text-sm leading-relaxed  font-medium">
                    {{ $expense->description ?? 'Sin descripción detallada para este movimiento.' }}
                </p>
            </div>
            <div class="card-premium flex flex-col justify-center items-center text-center space-y-2 border-dashed">
                <label class="label-style">Operador</label>
                <div class="h-10 w-10 rounded-full bg-white/5 flex items-center justify-center text-[#f3c444] border border-white/10">
                    <i class="fas fa-user-check text-xs"></i>
                </div>
                <p class="text-white font-black uppercase text-xs">{{ $expense->user->name ?? 'Sistema' }}</p>
                <p class="text-[9px] text-zinc-600 font-black tracking-widest uppercase">ID Registro: {{ $expense->id }}</p>
            </div>
        </div>

        {{-- 📎 SOPORTE DIGITAL --}}
        <div class="card-premium">
            <div class="flex items-center justify-between mb-8">
                <label class="label-style "><i class="fas fa-paperclip text-[#f3c444] mr-2"></i> Evidencia Digital</label>
                @if($expense->support_document)
                    <div class="flex gap-2">
                        <a href="{{ asset('storage/'.$expense->support_document) }}" target="_blank"
                           class="px-4 py-2 bg-white/5 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-white/10 transition-all">
                            Visualizar
                        </a>
                        <a href="{{ asset('storage/'.$expense->support_document) }}" download
                           class="px-4 py-2 bg-[#f3c444] text-black text-[10px] font-black uppercase tracking-widest rounded-lg hover:opacity-90 transition-all">
                            Descargar
                        </a>
                    </div>
                @endif
            </div>

            @if($expense->support_document)
                @if(Str::endsWith($expense->support_document, ['jpg','jpeg','png']))
                    <div class="relative group overflow-hidden rounded-[2rem] border border-white/10 max-w-2xl mx-auto">
                        <img src="{{ asset('storage/'.$expense->support_document) }}" 
                             class="w-full grayscale group-hover:grayscale-0 transition-all duration-700 object-cover max-h-[500px]">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-8">
                            <p class="text-white font-black text-[10px] uppercase tracking-widest">Vista previa del archivo: {{ basename($expense->support_document) }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-12 border-2 border-dashed border-white/5 rounded-[2rem] flex flex-col items-center justify-center gap-4 text-zinc-600">
                        <i class="fas fa-file-pdf text-4xl"></i>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] ">{{ basename($expense->support_document) }}</p>
                    </div>
                @endif
            @else
                <div class="p-12 border-2 border-dashed border-white/5 rounded-[2rem] flex flex-col items-center justify-center gap-4 text-zinc-700">
                    <i class="fas fa-folder-open text-3xl"></i>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] ">No se adjuntó soporte digital para este gasto</p>
                </div>
            @endif
        </div>

    </div>

    <style>
        .card-premium { @apply bg-[#0a0a0c] border border-white/5 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden; }
        .label-style { @apply text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] block; }
    </style>
</x-app-layout>