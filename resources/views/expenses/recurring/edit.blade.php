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
        <div class="flex items-center gap-4 px-4 py-2">
            <div class="relative">
                <div class="absolute -inset-1 bg-[#f3c444]/20 blur-xl rounded-full"></div>
                <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-[#f3c444] border border-[#f3c444]/50">
                    <i class="fas fa-edit text-xl"></i>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                    Editar Gasto <span class="text-[#f3c444]">Recurrente</span>
                </h1>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1 italic italic">ID de Registro: #{{ $recurringExpense->id }}</p>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto">
        
        {{-- Navegación de Regreso --}}
        <div class="mb-8">
            <a href="{{ route('recurring-expenses.index') }}" 
               class="inline-flex items-center gap-2 text-zinc-500 hover:text-[#f3c444] text-[10px] font-black uppercase tracking-widest transition-colors group">
                <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i>
                Cancelar y Volver
            </a>
        </div>

        {{-- Formulario de Actualización --}}
        <form method="POST" action="{{ route('recurring-expenses.update', $recurringExpense) }}" class="relative">
            @method('PUT')
            
            @include('expenses.recurring.form')

            {{-- Nota sutil de auditoría --}}
            <div class="mt-6 px-10 py-4 bg-white/5 border border-white/5 rounded-2xl flex items-center justify-between">
                <span class="text-[9px] font-black text-zinc-600 uppercase tracking-widest italic">
                    Última actualización: {{ $recurringExpense->updated_at->format('d/m/Y H:i') }}
                </span>
                <span class="text-[9px] font-black text-[#f3c444]/40 uppercase tracking-widest">
                    Tizzila Core Engine v2.6
                </span>
            </div>
        </form>
        
    </div>
</x-app-layout>