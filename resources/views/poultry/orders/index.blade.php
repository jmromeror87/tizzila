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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Programación de <span class="text-yellow-500">Despachos</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1 flex items-center gap-2">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_#10b981]"></span>
                    Sistema activo · <span id="header-clock" class="font-mono text-zinc-400">--:--</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('poultry.orders.calendar') }}"
                   class="h-10 px-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-calendar-alt text-xs"></i> Calendario
                </a>
                <a href="{{ route('poultry.dispatches.index') }}"
                   class="h-10 px-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-history text-xs"></i> Historial
                </a>
                <a href="{{ route('poultry.orders.create') }}"
                   class="h-10 px-5 bg-yellow-500 hover:bg-yellow-400 text-black font-black rounded-xl transition-all shadow-[0_8px_20px_rgba(234,179,8,0.2)] flex items-center gap-2 text-[10px] uppercase tracking-widest">
                    <i class="fas fa-plus"></i> Nueva Carga
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        @livewire('poultry-order-index')
    </div>

    <script>
        (function tick() {
            const el = document.getElementById('header-clock');
            if (el) el.textContent = new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', hour12: true });
            setTimeout(tick, 10000);
        })();
    </script>
</x-app-layout>
