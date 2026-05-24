{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<div class="space-y-5">

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
            <i class="fas fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- KPIs --}}
    @php
        $totalOrders   = $orders->count();
        $totalBirds    = $orders->sum('quantity');
        $plannedCount  = $orders->where('status', 'planned')->count();
        $dispatchedCount = $orders->where('status', 'dispatched')->count();
        $paidCount     = $orders->where('status', 'paid')->count();
        $cancelledCount = $orders->where('status', 'cancelled')->count();
        $todayBirds    = $orders->filter(fn($o) => $o->dispatch_date->isToday())->sum('quantity');
        $pendingApproval = $orders->where('approval_status', 'pending')->count();
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Total Pedidos</p>
            <p class="text-2xl font-black text-white">{{ $totalOrders }}</p>
            <p class="text-[9px] text-zinc-600 mt-1 font-bold uppercase">
                {{ ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'][$month - 1] }} {{ $year }}
            </p>
        </div>
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Total Aves</p>
            <p class="text-2xl font-black text-white">{{ number_format($totalBirds) }}</p>
            <p class="text-[9px] text-zinc-600 mt-1 font-bold uppercase flex items-center gap-1">
                <i class="fas fa-sun text-yellow-500/50 text-[8px]"></i>
                Hoy: {{ number_format($todayBirds) }}
            </p>
        </div>
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-2">Operación</p>
            <div class="flex items-center gap-3">
                <div class="text-center">
                    <p class="text-lg font-black text-blue-400">{{ $plannedCount }}</p>
                    <p class="text-[8px] text-zinc-700 font-black uppercase">Plan.</p>
                </div>
                <div class="w-px h-8 bg-white/5"></div>
                <div class="text-center">
                    <p class="text-lg font-black text-purple-400">{{ $dispatchedCount }}</p>
                    <p class="text-[8px] text-zinc-700 font-black uppercase">Desp.</p>
                </div>
                <div class="w-px h-8 bg-white/5"></div>
                <div class="text-center">
                    <p class="text-lg font-black text-emerald-400">{{ $paidCount }}</p>
                    <p class="text-[8px] text-zinc-700 font-black uppercase">Pag.</p>
                </div>
            </div>
        </div>
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Aprobación Pendiente</p>
            <p class="text-2xl font-black {{ $pendingApproval > 0 ? 'text-amber-400' : 'text-zinc-600' }}">
                {{ $pendingApproval }}
            </p>
            @if($cancelledCount > 0)
            <p class="text-[9px] text-red-400/60 mt-1 font-bold uppercase">{{ $cancelledCount }} cancelados</p>
            @else
            <p class="text-[9px] text-zinc-700 mt-1 font-bold uppercase">Sin cancelados</p>
            @endif
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-3 items-center">
        <div class="relative flex-1 w-full">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar por proveedor o ID..."
                class="w-full pl-9 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs font-bold text-white placeholder-zinc-700 outline-none focus:border-yellow-500/50 uppercase tracking-wide">
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <select wire:model.live="month"
                class="bg-black/40 border border-white/10 rounded-xl px-3 py-2.5 text-[10px] font-black text-white uppercase outline-none focus:border-yellow-500/50">
                @php
                    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                @endphp
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ $meses[$m - 1] }}</option>
                @endforeach
            </select>
            <select wire:model.live="year"
                class="bg-black/40 border border-white/10 rounded-xl px-3 py-2.5 text-[10px] font-black text-white outline-none focus:border-yellow-500/50">
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
            </select>
        </div>
    </div>

    {{-- TIMELINE --}}
    <div class="space-y-8">
        @forelse($orders->groupBy(fn($o) => $o->dispatch_date->format('Y-m-d')) as $date => $dayOrders)
            @php
                $parsedDate = \Carbon\Carbon::parse($date);
                $isToday = $parsedDate->isToday();
                $isPast  = $parsedDate->isPast() && !$isToday;
                $dayBirds = $dayOrders->sum('quantity');
            @endphp

            <div class="relative pl-6 border-l-2 {{ $isToday ? 'border-yellow-500/60' : 'border-white/5' }}">

                {{-- Timeline dot --}}
                <div class="absolute -left-[5px] top-5 w-2 h-2 rounded-full {{ $isToday ? 'bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)]' : 'bg-zinc-700' }}"></div>

                {{-- Date header --}}
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-3">
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-white leading-none">{{ $parsedDate->translatedFormat('d') }}</span>
                            <span class="text-xs font-black text-yellow-500 uppercase">{{ $parsedDate->translatedFormat('M') }}</span>
                        </div>
                        <div class="h-6 w-px bg-white/10"></div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest {{ $isToday ? 'text-yellow-400' : 'text-zinc-600' }}">
                                {{ $parsedDate->translatedFormat('l') }}
                                @if($isToday) · <span class="text-emerald-400">HOY</span> @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-zinc-600 font-black uppercase tracking-widest">Carga del día</p>
                        <p class="text-xs font-black text-white font-mono">{{ number_format($dayBirds) }} aves</p>
                    </div>
                </div>

                {{-- Orders --}}
                <div class="space-y-2">
                    @foreach($dayOrders as $order)
                        @php
                            $isApproved  = $order->approval_status === 'approved';
                            $isProgrammed = in_array($order->status, ['planned', 'scheduled']);
                            $hasDispatch = (bool)($order->dispatch_exists ?? $order->dispatch ?? false);
                        @endphp

                        <div class="bg-[#0d121f] border {{ $isToday ? 'border-yellow-500/10' : 'border-white/5' }} hover:border-yellow-500/20 rounded-2xl px-5 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 group transition-all">

                            {{-- ID + Info --}}
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="shrink-0 text-center">
                                    <div class="h-10 w-10 rounded-xl bg-black/40 border border-white/5 flex flex-col items-center justify-center">
                                        <span class="text-[8px] text-zinc-600 font-black uppercase leading-none">REF</span>
                                        <span class="text-[10px] text-white font-black leading-none">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-black text-white uppercase tracking-tight truncate group-hover:text-yellow-500 transition-colors">
                                        {{ $order->provider->business_name }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        {{-- Tipo ave --}}
                                        <span class="text-[9px] font-black uppercase tracking-wide px-2 py-0.5 rounded-lg border
                                            {{ strtolower($order->poultry_type ?? '') == 'bb'
                                                ? 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20'
                                                : 'bg-orange-500/10 text-orange-400 border-orange-500/20' }}">
                                            <i class="fas {{ strtolower($order->poultry_type ?? '') == 'bb' ? 'fa-egg' : 'fa-dove' }} mr-1 text-[8px]"></i>
                                            {{ strtoupper($order->poultry_type ?? '—') }}
                                        </span>

                                        {{-- Cantidad --}}
                                        <span class="text-[9px] font-mono font-bold text-zinc-400 bg-black/30 px-2 py-0.5 rounded-lg border border-white/5">
                                            {{ number_format($order->quantity) }} unds
                                        </span>

                                        {{-- Precio si existe --}}
                                        @if($order->unit_price ?? false)
                                        <span class="text-[9px] font-mono font-bold text-emerald-400/70 bg-black/30 px-2 py-0.5 rounded-lg border border-white/5">
                                            ${{ number_format($order->unit_price, 0) }}/u
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Badges de estado --}}
                            <div class="flex items-center gap-3 shrink-0">
                                {{-- Aprobación --}}
                                <div class="text-center">
                                    <p class="text-[8px] text-zinc-700 font-black uppercase tracking-widest mb-1">Aprob.</p>
                                    <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-full border flex items-center gap-1.5
                                        {{ match($order->approval_status) {
                                            'approved'     => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                                            'under_review' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20',
                                            'rejected'     => 'text-red-400 bg-red-500/10 border-red-500/20',
                                            default        => 'text-zinc-500 bg-white/5 border-white/10',
                                        } }}">
                                        <span class="w-1 h-1 rounded-full bg-current"></span>
                                        {{ match($order->approval_status) {
                                            'approved'     => 'Aprobado',
                                            'under_review' => 'Revisión',
                                            'rejected'     => 'Rechazado',
                                            default        => 'Pendiente',
                                        } }}
                                    </span>
                                </div>

                                {{-- Operación --}}
                                <div class="text-center">
                                    <p class="text-[8px] text-zinc-700 font-black uppercase tracking-widest mb-1">Oper.</p>
                                    <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-full border flex items-center gap-1.5
                                        {{ match($order->status) {
                                            'planned'    => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                                            'dispatched' => 'text-purple-400 bg-purple-500/10 border-purple-500/20',
                                            'paid'       => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                                            'cancelled'  => 'text-zinc-500 bg-white/5 border-white/10',
                                            default      => 'text-zinc-500 bg-white/5 border-white/10',
                                        } }}">
                                        <span class="w-1 h-1 rounded-full bg-current"></span>
                                        {{ match($order->status) {
                                            'planned'    => 'Programado',
                                            'dispatched' => 'Despachado',
                                            'paid'       => 'Pagado',
                                            'cancelled'  => 'Cancelado',
                                            default      => ucfirst($order->status),
                                        } }}
                                    </span>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex items-center gap-1.5">
                                    {{-- Confrontar --}}
                                    <a href="{{ route('poultry.orders.confront', $order->id) }}"
                                       title="Confrontar pedido"
                                       class="h-8 w-8 rounded-xl bg-cyan-500/10 border border-cyan-500/20 hover:bg-cyan-500/20 text-cyan-400 flex items-center justify-center transition-all text-[10px]">
                                        <i class="fas fa-exchange-alt"></i>
                                    </a>

                                    {{-- Distribución Programada --}}
                                    @if($isProgrammed)
                                    @php $distCount = $order->distributions->count(); @endphp
                                    <a href="{{ route('poultry.distribution.show', $order) }}"
                                       title="Distribución programada"
                                       class="h-8 w-8 rounded-xl {{ $distCount > 0 ? 'bg-emerald-500/10 border-emerald-500/20 hover:bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/10 border-yellow-500/20 hover:bg-yellow-500/20 text-yellow-400' }} border flex items-center justify-center transition-all text-[10px]">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    @endif

                                    {{-- Factura de Compra PRONAVICOLA --}}
                                    @if($isApproved && $isProgrammed)
                                    @php $hasPurchaseInvoice = (bool)($order->purchaseInvoice ?? false); @endphp
                                    <a href="{{ $hasPurchaseInvoice
                                                ? route('purchase-invoices.show', $order->purchaseInvoice->id)
                                                : route('purchase-invoices.create', $order) }}"
                                       title="{{ $hasPurchaseInvoice ? 'Ver factura de compra' : 'Registrar factura de compra' }}"
                                       class="h-8 w-8 rounded-xl {{ $hasPurchaseInvoice ? 'bg-purple-500/10 border-purple-500/20 hover:bg-purple-500/20 text-purple-400' : 'bg-orange-500/10 border-orange-500/20 hover:bg-orange-500/20 text-orange-400' }} border flex items-center justify-center transition-all text-[10px]">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </a>
                                    @endif

                                    {{-- Crear/ver despacho (solo si tiene factura de compra) --}}
                                    @if($isApproved && $isProgrammed && isset($order->purchaseInvoice) && $order->purchaseInvoice)
                                    <a href="{{ $hasDispatch
                                                ? route('poultry.dispatches.show', $order->dispatch->id)
                                                : route('poultry.dispatches.create', $order) }}"
                                       title="{{ $hasDispatch ? 'Ver manifiesto' : 'Crear distribución' }}"
                                       class="h-8 w-8 rounded-xl {{ $hasDispatch ? 'bg-emerald-500/10 border-emerald-500/20 hover:bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/10 border-yellow-500/20 hover:bg-yellow-500/20 text-yellow-400' }} border flex items-center justify-center transition-all text-[10px]">
                                        <i class="fas {{ $hasDispatch ? 'fa-file-invoice' : 'fa-truck' }}"></i>
                                    </a>
                                    @endif

                                    {{-- Subir aprobación --}}
                                    @if(in_array($order->approval_status, ['pending', 'processing']))
                                    <button wire:click="openApprovalModal({{ $order->id }})"
                                       title="Subir documento de aprobación"
                                       class="h-8 w-8 rounded-xl bg-amber-500/10 border border-amber-500/20 hover:bg-amber-500/20 text-amber-400 flex items-center justify-center transition-all text-[10px]">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                    @endif

                                    {{-- Eliminar --}}
                                    <button wire:click="deleteOrder({{ $order->id }})"
                                        wire:confirm="¿Eliminar este pedido permanentemente?"
                                        title="Eliminar"
                                        class="h-8 w-8 rounded-xl bg-red-500/5 border border-red-500/10 hover:bg-red-500/20 hover:border-red-500/30 text-red-500/50 hover:text-red-400 flex items-center justify-center transition-all text-[10px]">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl py-20 flex flex-col items-center gap-4 opacity-40">
                <i class="fas fa-satellite-dish text-4xl text-zinc-700"></i>
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-600">Sin despachos en este período</p>
                <p class="text-[9px] text-zinc-700 font-bold uppercase tracking-widest">Ajusta los filtros de fecha</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL APROBACIÓN --}}
    @if($showApprovalModal && $selectedOrder)
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="$set('showApprovalModal', false)">
        <div class="bg-[#0d121f] border border-white/10 rounded-2xl p-6 w-full max-w-sm space-y-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-white uppercase tracking-tight">Documento de Aprobación</h3>
                    <p class="text-[9px] text-zinc-500 font-bold uppercase mt-0.5">{{ $selectedOrder->provider->business_name }}</p>
                </div>
                <button wire:click="$set('showApprovalModal', false)" class="h-8 w-8 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <div class="bg-black/30 border border-white/5 rounded-xl p-4 flex items-center gap-3">
                <i class="fas fa-box text-yellow-500 text-sm"></i>
                <div>
                    <p class="text-[9px] text-zinc-500 font-black uppercase">Pedido #{{ str_pad($selectedOrder->id, 3, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-xs font-black text-white">{{ number_format($selectedOrder->quantity) }} aves · {{ $selectedOrder->dispatch_date->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            @error('photo')
                <p class="text-red-400 text-[10px] font-bold flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i> {{ $message }}
                </p>
            @enderror

            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-2">
                    Adjuntar Imagen del Documento
                </label>
                <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-white/10 rounded-xl cursor-pointer hover:border-yellow-500/30 transition-all bg-black/20">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-cloud-upload-alt text-xl text-zinc-600"></i>
                        <p class="text-[9px] font-black uppercase text-zinc-600 tracking-widest">Clic o arrastra aquí</p>
                        <p class="text-[8px] text-zinc-700">JPG, PNG hasta 10MB</p>
                    </div>
                    <input type="file" wire:model="photo" class="hidden" accept="image/*">
                </label>
                @if($photo)
                    <div class="mt-2 flex items-center gap-2 text-emerald-400 text-[10px] font-black">
                        <i class="fas fa-check-circle"></i> Imagen lista para enviar
                    </div>
                @endif
            </div>

            <div wire:loading wire:target="photo" class="text-center">
                <div class="inline-flex items-center gap-3 text-yellow-400 text-[10px] font-black uppercase">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Procesando con IA...
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
