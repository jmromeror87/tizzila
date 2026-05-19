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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Agenda <span class="text-yellow-500">Logística</span>
                </h2>
                <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Control de inventario vivo · Programación avícola
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('poultry.orders.index') }}"
                   class="h-10 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                    <i class="fas fa-list text-xs"></i> Lista
                </a>
                <button onclick="window.calendar && window.calendar.refetchEvents()"
                    class="h-10 w-10 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-yellow-500 rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-sync-alt text-xs"></i>
                </button>
                <a href="{{ route('poultry.orders.create') }}"
                    class="h-10 px-5 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                    <i class="fas fa-plus"></i> Programar Lote
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- KPIs del Mes (dinámicos vía JS) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="kpi-row">
            @foreach([
                ['id'=>'kpi-total','label'=>'Total Aves Mes','icon'=>'fa-egg','color'=>'text-yellow-500','border'=>'hover:border-yellow-500/20'],
                ['id'=>'kpi-orders','label'=>'Pedidos Mes','icon'=>'fa-file-alt','color'=>'text-blue-400','border'=>'hover:border-blue-500/20'],
                ['id'=>'kpi-providers','label'=>'Proveedores','icon'=>'fa-truck-loading','color'=>'text-purple-400','border'=>'hover:border-purple-500/20'],
                ['id'=>'kpi-today','label'=>'Aves Hoy','icon'=>'fa-calendar-check','color'=>'text-emerald-400','border'=>'hover:border-emerald-500/20'],
            ] as $kpi)
            <div class="bg-[#0d121f] border border-white/5 {{ $kpi['border'] }} rounded-2xl px-5 py-4 transition-all">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas {{ $kpi['icon'] }} {{ $kpi['color'] }} text-xs"></i>
                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">{{ $kpi['label'] }}</p>
                </div>
                <p id="{{ $kpi['id'] }}" class="text-2xl font-black text-white tracking-tighter">—</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-5">

            {{-- PANEL LATERAL --}}
            <div class="xl:col-span-1 space-y-4">

                {{-- Leyenda de tipos --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-white/5">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Líneas Genéticas</p>
                    </div>
                    <div class="p-3 space-y-1" id="legend-list">
                        @php
                            $legendItems = [
                                ['slug'=>'pollito-bb',         'name'=>'Pollito BB',         'color'=>'bg-yellow-500'],
                                ['slug'=>'lohmann-lsl',        'name'=>'Lohmann LSL',        'color'=>'bg-blue-500'],
                                ['slug'=>'lohmann-brown',      'name'=>'Lohmann Brown',      'color'=>'bg-orange-500'],
                                ['slug'=>'engorde',            'name'=>'Engorde',            'color'=>'bg-emerald-500'],
                                ['slug'=>'pollita-levantada',  'name'=>'Pollita Levantada',  'color'=>'bg-cyan-500'],
                                ['slug'=>'h-n',                'name'=>'H&N',                'color'=>'bg-purple-500'],
                                ['slug'=>'otros',              'name'=>'Otros',              'color'=>'bg-zinc-500'],
                            ];
                        @endphp
                        @foreach($legendItems as $item)
                        <div class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-white/[0.02] transition-all cursor-pointer group"
                             onclick="filterByType('{{ $item['slug'] }}')">
                            <div class="h-2 w-2 rounded-full {{ $item['color'] }} shrink-0"></div>
                            <span class="text-xs font-bold text-zinc-400 group-hover:text-white transition-colors">{{ $item['name'] }}</span>
                            <span class="ml-auto text-[9px] font-black text-zinc-700 type-count" data-type="{{ $item['slug'] }}">0</span>
                        </div>
                        @endforeach
                        <button onclick="filterByType(null)" class="w-full text-center text-[9px] font-black uppercase tracking-widest text-zinc-600 hover:text-yellow-500 py-2 transition-colors">
                            Ver Todos
                        </button>
                    </div>
                </div>

                {{-- Próximos despachos --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Próximos Despachos</p>
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </div>
                    <div id="upcoming-list" class="p-2 space-y-1 max-h-64 overflow-y-auto">
                        <p class="text-[9px] text-zinc-700 text-center py-4">Cargando...</p>
                    </div>
                </div>

                {{-- Acciones rápidas --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 space-y-2">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-600 mb-3">Acciones</p>
                    <a href="{{ route('poultry.orders.create') }}"
                       class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-yellow-500/10 border border-yellow-500/20 hover:bg-yellow-500/20 text-yellow-400 text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fas fa-plus text-xs"></i> Nuevo Pedido
                    </a>
                    <a href="{{ route('poultry.orders.index') }}"
                       class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.05] text-zinc-400 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fas fa-list text-xs"></i> Ver Lista Completa
                    </a>
                    <a href="{{ route('poultry.providers.index') }}"
                       class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.05] text-zinc-400 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fas fa-truck-loading text-xs"></i> Proveedores
                    </a>
                </div>
            </div>

            {{-- CALENDARIO --}}
            <div class="xl:col-span-3">
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 md:p-6 relative overflow-hidden">

                    {{-- Loader --}}
                    <div id="calendar-loading" class="absolute inset-0 bg-[#0d121f]/90 z-50 items-center justify-center hidden">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 border-4 border-yellow-500/20 border-t-yellow-500 rounded-full animate-spin"></div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-yellow-500">Sincronizando...</p>
                        </div>
                    </div>

                    <div id="calendar" class="min-h-[540px]"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETALLE DEL DÍA --}}
    <div id="day-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="bg-[#0d121f] border border-white/10 rounded-3xl w-full max-w-lg shadow-2xl max-h-[80vh] flex flex-col">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between shrink-0">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Despachos del día</p>
                    <h3 id="modal-date-title" class="text-lg font-black text-white uppercase tracking-tight mt-0.5"></h3>
                </div>
                <div class="flex items-center gap-2">
                    <a id="modal-new-btn" href="{{ route('poultry.orders.create') }}"
                       class="h-8 px-3 bg-yellow-500 hover:bg-yellow-400 text-black text-[9px] font-black uppercase tracking-widest rounded-xl flex items-center gap-1.5 transition-all">
                        <i class="fas fa-plus text-[9px]"></i> Nuevo
                    </a>
                    <button onclick="document.getElementById('day-modal').classList.add('hidden')"
                        class="h-8 w-8 rounded-xl bg-white/5 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                        <i class="fas fa-xmark text-sm"></i>
                    </button>
                </div>
            </div>
            <div id="modal-events-list" class="overflow-y-auto flex-1 p-4 space-y-2"></div>
            <div id="modal-day-total" class="px-6 py-3 border-t border-white/5 flex justify-between items-center shrink-0">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Total del día</p>
                <p class="text-sm font-black text-yellow-500" id="modal-total-value">0 aves</p>
            </div>
        </div>
    </div>

    <style>
        :root {
            --fc-page-bg-color: transparent;
            --fc-border-color: rgba(255,255,255,0.04);
            --fc-today-bg-color: rgba(234,179,8,0.05);
            --fc-button-bg-color: #111827;
            --fc-button-border-color: rgba(255,255,255,0.08);
            --fc-button-hover-bg-color: #1f2937;
            --fc-button-active-bg-color: #eab308;
            --fc-button-text-color: #ffffff;
        }
        .fc .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 900 !important; text-transform: uppercase; letter-spacing: -0.04em; color: white; }
        .fc .fc-button { font-size: 9px !important; font-weight: 900 !important; text-transform: uppercase; border-radius: 10px !important; padding: 7px 14px !important; letter-spacing: 0.05em; }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active { background-color: #eab308 !important; color: black !important; border-color: #eab308 !important; }
        .fc .fc-col-header-cell-cushion { font-size: 9px !important; font-weight: 900 !important; color: #52525b !important; text-transform: uppercase; letter-spacing: 0.1em; padding: 10px 0 !important; }
        .fc .fc-daygrid-day-number { font-size: 13px !important; font-weight: 900 !important; color: #52525b !important; padding: 8px !important; }
        .fc .fc-day-today .fc-daygrid-day-number { color: #eab308 !important; }
        .fc .fc-day-today { background-color: rgba(234,179,8,0.04) !important; }
        .fc-event { border: none !important; margin: 1px 3px !important; border-radius: 5px !important; cursor: pointer; }
        .fc-event-main { padding: 3px 6px !important; }
        .fc-daygrid-event-dot { display: none !important; }

        /* Tipo colores */
        .event-pollito-bb     { background: #eab308 !important; color: #000 !important; }
        .event-lohmann-lsl    { background: #3b82f6 !important; color: #fff !important; }
        .event-lohmann-brown  { background: #f97316 !important; color: #fff !important; }
        .event-engorde        { background: #10b981 !important; color: #fff !important; }
        .event-pollita-levantada { background: #06b6d4 !important; color: #fff !important; }
        .event-h-n            { background: #a855f7 !important; color: #fff !important; }
        .event-otros          { background: #64748b !important; color: #fff !important; }

        /* Badge total día */
        .day-total-badge {
            position: absolute; bottom: 3px; right: 3px;
            background: rgba(0,0,0,0.7); color: #eab308;
            font-size: 9px; font-weight: 900;
            padding: 1px 6px; border-radius: 6px;
            border: 1px solid rgba(234,179,8,0.2);
            z-index: 4; pointer-events: none;
        }

        @media (max-width: 768px) {
            .fc .fc-toolbar { flex-direction: column; gap: 8px; }
            .fc .fc-toolbar-title { font-size: 0.9rem !important; }
        }
    </style>

    <script>
    let allEvents = [];
    let activeTypeFilter = null;

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const loader     = document.getElementById('calendar-loading');
        const today      = new Date().toISOString().split('T')[0];

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
            locale: 'es',
            firstDay: 1,
            dayMaxEvents: 3,
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,listMonth'
            },
            events: '{{ route('poultry.orders.calendar.events') }}',

            loading(isLoading) {
                loader.style.display = isLoading ? 'flex' : 'none';
            },

            eventsSet(events) {
                allEvents = events;
                updateKPIs(events);
                updateTypeCount(events);
                updateUpcoming(events);
                renderDayBadges(events);
            },

            eventContent(arg) {
                const p = arg.event.extendedProps;
                return {
                    html: `<div style="font-size:9px;font-weight:900;text-transform:uppercase;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;padding:2px 0;">
                             <span style="opacity:.7">${p.poultry || 'LOTE'}</span>
                             <span style="margin-left:4px">${Number(p.quantity).toLocaleString()}</span>
                           </div>`
                };
            },

            eventClassNames(arg) {
                const slug = (arg.event.extendedProps.type || 'otros')
                    .toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'').replace(/ /g,'-');
                return [`event-${slug}`];
            },

            // Click en evento → navegar a editar
            eventClick(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) window.location.href = info.event.url;
            },

            // Click en celda de día → abrir modal
            dateClick(info) {
                openDayModal(info.dateStr, allEvents);
            },

            datesSet() {
                setTimeout(() => renderDayBadges(calendar.getEvents()), 150);
            }
        });

        calendar.render();
        window.calendar = calendar;
    });

    function updateKPIs(events) {
        const today = new Date().toISOString().split('T')[0];
        const month = today.substring(0, 7);

        let totalQty = 0, totalOrders = 0, todayQty = 0;
        const providers = new Set();

        events.forEach(e => {
            const qty = parseInt(e.extendedProps.quantity) || 0;
            totalQty    += qty;
            totalOrders += 1;
            providers.add(e.extendedProps.provider);
            if (e.startStr.startsWith(today)) todayQty += qty;
        });

        document.getElementById('kpi-total').textContent     = totalQty.toLocaleString();
        document.getElementById('kpi-orders').textContent    = totalOrders;
        document.getElementById('kpi-providers').textContent = providers.size;
        document.getElementById('kpi-today').textContent     = todayQty.toLocaleString();
    }

    function updateTypeCount(events) {
        const counts = {};
        events.forEach(e => {
            const type = (e.extendedProps.type || 'otros').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'').replace(/ /g,'-');
            counts[type] = (counts[type] || 0) + parseInt(e.extendedProps.quantity || 0);
        });
        document.querySelectorAll('.type-count').forEach(el => {
            const t = el.dataset.type;
            el.textContent = (counts[t] || 0).toLocaleString();
        });
    }

    function updateUpcoming(events) {
        const today = new Date().toISOString().split('T')[0];
        const upcoming = events
            .filter(e => e.startStr >= today)
            .sort((a,b) => a.startStr.localeCompare(b.startStr))
            .slice(0, 8);

        const list = document.getElementById('upcoming-list');
        if (!upcoming.length) {
            list.innerHTML = '<p class="text-[9px] text-zinc-700 text-center py-4">Sin despachos próximos</p>';
            return;
        }

        list.innerHTML = upcoming.map(e => {
            const p = e.extendedProps;
            const slug = (p.type||'otros').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'').replace(/ /g,'-');
            const colorMap = {
                'pollito-bb':'bg-yellow-500','lohmann-lsl':'bg-blue-500','lohmann-brown':'bg-orange-500',
                'engorde':'bg-emerald-500','pollita-levantada':'bg-cyan-500','h-n':'bg-purple-500','otros':'bg-zinc-500'
            };
            const color = colorMap[slug] || 'bg-zinc-500';
            const dateStr = e.startStr;
            const d = new Date(dateStr + 'T00:00:00');
            const label = d.toLocaleDateString('es-CO', { weekday:'short', day:'numeric', month:'short' });
            return `<div class="flex items-center gap-2.5 px-2 py-2 rounded-lg hover:bg-white/[0.03] transition-all cursor-pointer"
                         onclick="window.calendar.gotoDate('${dateStr}')">
                        <div class="h-6 w-1 rounded-full ${color} shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] font-black text-white truncate uppercase">${p.provider || '—'}</p>
                            <p class="text-[8px] text-zinc-600">${label} · ${Number(p.quantity).toLocaleString()} aves</p>
                        </div>
                    </div>`;
        }).join('');
    }

    function renderDayBadges(events) {
        const dayTotals = {};
        events.forEach(e => {
            const d = e.startStr.split('T')[0];
            dayTotals[d] = (dayTotals[d] || 0) + (parseInt(e.extendedProps.quantity) || 0);
        });
        document.querySelectorAll('.fc-daygrid-day').forEach(cell => {
            const date = cell.getAttribute('data-date');
            cell.querySelectorAll('.day-total-badge').forEach(b => b.remove());
            if (dayTotals[date]) {
                const badge = document.createElement('div');
                badge.className = 'day-total-badge';
                badge.textContent = dayTotals[date].toLocaleString();
                cell.style.position = 'relative';
                cell.appendChild(badge);
            }
        });
    }

    function filterByType(type) {
        activeTypeFilter = type;
        window.calendar.getEvents().forEach(e => {
            if (!type) { e.setProp('display', 'auto'); return; }
            const slug = (e.extendedProps.type || 'otros').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'').replace(/ /g,'-');
            e.setProp('display', slug === type ? 'auto' : 'none');
        });
    }

    function openDayModal(dateStr, events) {
        const dayEvents = events.filter(e => e.startStr.split('T')[0] === dateStr);
        if (!dayEvents.length) return;

        const d = new Date(dateStr + 'T00:00:00');
        const label = d.toLocaleDateString('es-CO', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        document.getElementById('modal-date-title').textContent = label;

        const total = dayEvents.reduce((s, e) => s + (parseInt(e.extendedProps.quantity)||0), 0);
        document.getElementById('modal-total-value').textContent = total.toLocaleString() + ' aves';

        const list = document.getElementById('modal-events-list');
        list.innerHTML = dayEvents.map(e => {
            const p = e.extendedProps;
            const slug = (p.type||'otros').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'').replace(/ /g,'-');
            const colorMap = {
                'pollito-bb':'bg-yellow-500','lohmann-lsl':'bg-blue-500','lohmann-brown':'bg-orange-500',
                'engorde':'bg-emerald-500','pollita-levantada':'bg-cyan-500','h-n':'bg-purple-500','otros':'bg-zinc-500'
            };
            const statusMap = {
                'PLANNED':   ['PROGRAMADO',  'text-blue-400',    'bg-blue-500/10',    'border-blue-500/20'],
                'DISPATCHED':['DESPACHADO',  'text-purple-400',  'bg-purple-500/10',  'border-purple-500/20'],
                'PAID':      ['PAGADO',       'text-emerald-400', 'bg-emerald-500/10', 'border-emerald-500/20'],
                'CANCELLED': ['CANCELADO',   'text-red-400',     'bg-red-500/10',     'border-red-500/20'],
            };
            const [stLabel, stText, stBg, stBorder] = statusMap[p.status] || ['—','text-zinc-400','bg-zinc-500/10','border-zinc-500/20'];
            const color = colorMap[slug] || 'bg-zinc-500';
            return `<div class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-white/10 transition-all">
                        <div class="h-8 w-1.5 rounded-full ${color} shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-white uppercase truncate">${p.provider || '—'}</p>
                            <p class="text-[9px] text-zinc-500">${p.poultry || 'Sin tipo'} · ${Number(p.quantity).toLocaleString()} aves</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[8px] font-black px-2 py-0.5 rounded-lg ${stBg} ${stBorder} border ${stText}">${stLabel}</span>
                            ${e.url ? `<a href="${e.url}" class="h-7 w-7 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-600 flex items-center justify-center transition-all text-[9px]"><i class="fas fa-pen"></i></a>` : ''}
                        </div>
                    </div>`;
        }).join('');

        document.getElementById('day-modal').classList.remove('hidden');
    }

    // Cerrar modal con ESC o click fuera
    document.addEventListener('keydown', e => { if (e.key === 'Escape') document.getElementById('day-modal').classList.add('hidden'); });
    document.getElementById('day-modal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
    </script>
</x-app-layout>
