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
    <div class="py-12 bg-zinc-900 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-[3rem] shadow-2xl p-10 border-t-[16px] border-yellow-500 relative overflow-hidden">
            {{-- Fondo decorativo Tizzilla --}}
            <div class="absolute -right-10 -top-10 text-[100px] font-black text-slate-50 opacity-10">TAX</div>
            
            <div class="relative z-10 text-center space-y-6">
                <div class="inline-block bg-yellow-500 text-black px-6 py-2 rounded-full font-black font-mono text-xl">
                    {{ $tax->code }}
                </div>
                
                <h1 class="text-4xl font-black uppercase tracking-tighter text-slate-900">
                    {{ $tax->name }}
                </h1>

                <div class="text-5xl font-black font-mono text-yellow-600">
                    {{ $tax->percentage }}%
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 space-y-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Metadata Técnica</p>
                    <p class="text-xs font-bold text-slate-600 italic">DIAN_TYPE: {{ strtoupper($tax->type) }}</p>
                    <p class="text-xs font-bold text-slate-600 italic">STATUS: {{ $tax->is_active ? 'ENABLED' : 'DISABLED' }}</p>
                </div>

                <a href="{{ route('taxes.index') }}" class="block w-full bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-xs tracking-[0.3em] hover:bg-yellow-500 hover:text-black transition-all">
                    Volver al Panel
                </a>
            </div>
        </div>
    </div>
</x-app-layout>