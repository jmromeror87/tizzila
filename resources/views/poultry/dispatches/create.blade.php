{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.dispatches.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Nuevo <span class="text-yellow-500">Viaje</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        Paso 1: Repartir la Carga · Pedido #{{ $order->id }}
                    </p>
                </div>
            </div>
            <a href="{{ route('poultry.dispatches.index') }}"
               class="h-9 px-4 rounded-xl bg-white/5 border border-white/10 hover:bg-rose-500/10 hover:border-rose-500/20 hover:text-rose-400 text-zinc-400 flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest w-fit">
                <i class="fas fa-times text-xs"></i> Cancelar
            </a>
        </div>
    </x-slot>

    <div class="py-4">

        @if($errors->has('credit_block'))
        <div class="mb-4 bg-red-500/10 border border-red-500/30 rounded-2xl px-5 py-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-red-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-ban text-red-400 text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-red-400 mb-1">Despacho Bloqueado — Alerta de Crédito</p>
                <p class="text-xs text-red-300/80">{{ $errors->first('credit_block') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any() && !$errors->has('credit_block'))
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-black px-5 py-4 rounded-2xl">
            <ul class="space-y-1">@foreach($errors->all() as $e)<li>· {{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form id="dispatchForm" action="{{ route('poultry.dispatches.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="poultry_order_schedule_id" value="{{ $order->id }}">

            {{-- KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2">Pollitos Totales</p>
                    <p class="text-3xl font-black text-white tracking-tighter">{{ number_format($order->quantity) }}</p>
                </div>

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 relative overflow-hidden">
                    <div id="remainingBg" class="absolute inset-0 bg-yellow-500/5 transition-all"></div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-yellow-500 mb-2 relative">Faltan por Asignar</p>
                    <p id="remainingQty" class="text-3xl font-black text-yellow-500 tracking-tighter relative">
                        {{ number_format($order->quantity) }}
                    </p>
                </div>

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500 mb-2">Precio Recomendado IA</p>
                    <p class="text-3xl font-black text-white tracking-tighter">
                        <span class="text-emerald-500">$</span>{{ number_format($aiSuggestedPrice ?? 3400) }}
                    </p>
                </div>
            </div>

            {{-- Lista de entregas --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Lista de Entregas</h3>
                    <button type="button" onclick="addItem()"
                            class="h-9 px-5 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-plus text-xs"></i> Agregar Cliente
                    </button>
                </div>

                <div id="itemsWrapper" class="divide-y divide-white/[0.04]"></div>

                <div id="emptyState" class="py-16 flex flex-col items-center gap-3 text-gray-700">
                    <i class="fas fa-box-open text-4xl opacity-30"></i>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em]">Agrega un cliente para comenzar</p>
                </div>
            </div>

            {{-- Footer de acción --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl px-5 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div id="statusDot" class="h-2.5 w-2.5 rounded-full bg-rose-500 shadow-[0_0_10px_#f43f5e] animate-pulse"></div>
                    <p id="helperText" class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-500">
                        Reparte todos los pollitos para continuar
                    </p>
                </div>

                <button type="submit" id="submitBtn" disabled
                        class="w-full md:w-auto h-10 px-10 bg-white/5 border border-white/10 text-gray-700 rounded-xl font-black uppercase tracking-widest text-[11px] transition-all cursor-not-allowed">
                    Finalizar Despacho
                </button>
            </div>
        </form>
    </div>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>

<script>
let index = 0;
const maxQty = {{ $order->quantity }};
const aiSuggestedPrice = {{ $aiSuggestedPrice ?? 3400 }};

function addItem() {
    document.getElementById('emptyState').classList.add('hidden');
    const html = `
    <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 px-5 py-4 items-end hover:bg-white/[0.01] transition-colors group">
        <div class="md:col-span-5">
            <label class="text-[9px] font-black text-gray-500 mb-2 block uppercase tracking-widest">Cliente</label>
            <div class="relative">
                <select name="items[${index}][customer_id]" required
                        class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white font-bold focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer">
                    <option value="">Elegir cliente...</option>
                    @foreach(\App\Models\Customer\Customer::orderBy('name')->get() as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 text-[10px] pointer-events-none"></i>
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="text-[9px] font-black text-gray-500 mb-2 block uppercase tracking-widest">Cantidad</label>
            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="items[${index}][quantity]" min="1" required
                   placeholder="0" oninput="handleQty(this)"
                   class="qty-field w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white font-black text-center focus:outline-none focus:border-yellow-500/50 transition-colors">
        </div>
        <div class="md:col-span-3">
            <label class="text-[9px] font-black text-emerald-500/70 mb-2 block uppercase tracking-widest">Precio ($)</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500/60 text-sm font-black pointer-events-none">$</span>
                <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="items[${index}][price_applied]"
                       value="${aiSuggestedPrice}" required
                       class="w-full bg-black/30 border border-emerald-500/20 rounded-xl pl-8 pr-4 py-2.5 text-sm text-emerald-400 font-black text-center focus:outline-none focus:border-emerald-500/50 transition-colors">
            </div>
        </div>
        <div class="md:col-span-1 flex justify-center">
            <button type="button" onclick="removeItem(this)"
                    class="h-9 w-9 rounded-xl bg-white/5 hover:bg-rose-500/20 border border-white/5 text-gray-600 hover:text-rose-500 flex items-center justify-center transition-all">
                <i class="fas fa-trash-alt text-[10px]"></i>
            </button>
        </div>
    </div>`;
    document.getElementById('itemsWrapper').insertAdjacentHTML('beforeend', html);
    index++;
    updateTotals();
}

function handleQty(input) {
    let total = 0;
    document.querySelectorAll('.qty-field').forEach(f => total += parseInt(f.value) || 0);
    if (total > maxQty) {
        let others = 0;
        document.querySelectorAll('.qty-field').forEach(f => { if (f !== input) others += parseInt(f.value) || 0; });
        input.value = maxQty - others;
    }
    updateTotals();
}

function updateTotals() {
    let assigned = 0;
    document.querySelectorAll('.qty-field').forEach(f => assigned += parseInt(f.value) || 0);
    const remaining = maxQty - assigned;
    document.getElementById('remainingQty').textContent = remaining.toLocaleString();

    const btn    = document.getElementById('submitBtn');
    const helper = document.getElementById('helperText');
    const dot    = document.getElementById('statusDot');
    const bg     = document.getElementById('remainingBg');

    if (remaining === 0 && assigned > 0) {
        btn.disabled = false;
        btn.className = 'w-full md:w-auto h-10 px-10 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl font-black uppercase tracking-widest text-[11px] transition-all active:scale-95 cursor-pointer';
        helper.textContent = '¡Listo! Todos los pollitos asignados';
        helper.className = 'text-[11px] font-black uppercase tracking-[0.2em] text-emerald-500';
        dot.className = 'h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]';
        bg.className = 'absolute inset-0 bg-emerald-500/5 transition-all';
    } else {
        btn.disabled = true;
        btn.className = 'w-full md:w-auto h-10 px-10 bg-white/5 border border-white/10 text-gray-700 rounded-xl font-black uppercase tracking-widest text-[11px] transition-all cursor-not-allowed';
        helper.textContent = remaining > 0 ? `Faltan ${remaining.toLocaleString()} por asignar` : 'Reparte todos los pollitos para continuar';
        helper.className = 'text-[11px] font-black uppercase tracking-[0.2em] text-gray-500';
        dot.className = 'h-2.5 w-2.5 rounded-full bg-rose-500 shadow-[0_0_10px_#f43f5e] animate-pulse';
        bg.className = 'absolute inset-0 bg-yellow-500/5 transition-all';
    }
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
    updateTotals();
    if (!document.getElementById('itemsWrapper').children.length) {
        document.getElementById('emptyState').classList.remove('hidden');
    }
}
</script>
</x-app-layout>
