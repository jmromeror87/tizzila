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
                    Calendario de <span class="text-yellow-500">Obligaciones</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Control de Egresos y Recurrencias
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 overflow-hidden">
            <div id="calendar" class="text-zinc-300"></div>
        </div>
    </div>

    <style>
        :root {
            --fc-border-color: rgba(255, 255, 255, 0.05);
            --fc-daygrid-event-dot-width: 8px;
            --fc-page-bg-color: transparent;
        }

        .fc { font-family: inherit; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid var(--fc-border-color) !important; }
        .fc .fc-col-header-cell-cushion {
            font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #71717a; padding: 15px 0;
        }

        .fc .fc-daygrid-day-number {
            font-size: 11px; font-weight: 800; color: #a1a1aa; padding: 10px; text-decoration: none !important;
        }
        .fc-day-today { background: rgba(234, 179, 8, 0.03) !important; }

        .fc-event {
            background-color: rgba(234, 179, 8, 0.05) !important;
            border: 1px solid rgba(234, 179, 8, 0.2) !important;
            border-radius: 10px !important;
            padding: 5px !important;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            overflow: hidden;
        }

        .fc-event:hover {
            background-color: rgba(234, 179, 8, 0.15) !important;
            border-color: #eab308 !important;
            transform: translateY(-1px);
            z-index: 50;
        }

        .event-container { display: flex; flex-direction: column; gap: 2px; }
        .event-header { display: flex; align-items: center; gap: 4px; }
        .event-category-tag { font-size: 7px; font-weight: 900; text-transform: uppercase; color: #71717a; letter-spacing: 0.05em; }
        .event-title-text { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #fff; }
        .event-amount-badge {
            background-color: #eab308; color: #000; font-size: 9px; font-weight: 900;
            padding: 1px 6px; border-radius: 6px; display: inline-block; margin-top: 2px;
        }

        .fc .fc-button-primary {
            background-color: #0d121f !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 10px !important;
            color: #a1a1aa !important;
        }
        .fc .fc-button-primary:hover { background-color: #eab308 !important; color: #000 !important; border-color: #eab308 !important; }
        .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #eab308 !important; color: #000 !important; border-color: #eab308 !important; }
        .fc .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 900 !important; text-transform: uppercase; color: #fff; letter-spacing: -0.025em; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let calendarEl = document.getElementById('calendar');

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                events: @json($events),
                eventContent: function(arg) {
                    const icon     = arg.event.extendedProps.icon || 'fa-bolt';
                    const category = arg.event.extendedProps.category || 'Gasto';
                    const rawTitle = arg.event.title.split('(')[0];

                    const amount = new Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP',
                        maximumFractionDigits: 0
                    }).format(arg.event.extendedProps.amount || 0);

                    let container = document.createElement('div');
                    container.className = 'event-container';
                    container.innerHTML = `
                        <div class="event-header">
                            <i class="fas ${icon} text-yellow-500 text-[8px]"></i>
                            <span class="event-category-tag">${category}</span>
                        </div>
                        <div class="event-title-text">${rawTitle}</div>
                        <div><span class="event-amount-badge">${amount}</span></div>
                    `;
                    return { domNodes: [container] };
                }
            });

            calendar.render();
        });
    </script>
</x-app-layout>
