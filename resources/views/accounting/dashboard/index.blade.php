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

    <style>
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
</style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4 py-2">
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-indigo-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-indigo-400 border border-indigo-500/50 shadow-[0_10px_20px_rgba(99,102,241,0.2)]">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Dashboard <span class="text-[#f3c444]">Financiero</span>
                    </h1>
                    <p id="dashboard-subtitle" class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Tizzila Engine • Vista Anual {{ $year }}
                    </p>
                </div>
            </div>

            {{-- 🔘 FILTROS EN VIVO --}}
            <div class="flex items-center gap-2 bg-[#0a0a0c] p-1.5 rounded-2xl border border-white/5 shadow-inner">
                <button onclick="filterData('day')" id="btn-day" class="filter-btn px-4 py-2 text-zinc-500 text-[9px] font-black uppercase tracking-widest rounded-xl hover:text-white transition-all">Hoy</button>
                <button onclick="filterData('month')" id="btn-month" class="filter-btn px-4 py-2 text-zinc-500 text-[9px] font-black uppercase tracking-widest rounded-xl hover:text-white transition-all">Mes</button>
                <button onclick="filterData('year')" id="btn-year" class="filter-btn px-4 py-2 bg-[#f3c444] text-black text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-[#f3c444]/10">Año</button>
            </div>
        </div>

      


    </x-slot>

    <div class="p-8 space-y-8">
        {{-- 🚀 KPIs CON ICONOS Y TENDENCIAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Ingresos --}}
            <div class="group bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden transition-all hover:border-emerald-500/30">
                <div class="absolute -right-4 -top-4 bg-emerald-500/5 w-24 h-24 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-emerald-500/10 h-10 w-10 rounded-lg flex items-center justify-center text-emerald-500 border border-emerald-500/20">
                        <i class="fas fa-arrow-trend-up"></i>
                    </div>
                    <span id="trend-income" class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-1 rounded-md border border-emerald-500/20">+12%</span>
                </div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-1">Ingresos Totales</p>
                <h2 id="kpi-income" class="text-4xl font-black text-white tracking-tighter">${{ number_format($totalIncome, 0, ',', '.') }}</h2>
            </div>

            {{-- Gastos --}}
            <div class="group bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden transition-all hover:border-rose-500/30">
                <div class="absolute -right-4 -top-4 bg-rose-500/5 w-24 h-24 rounded-full blur-3xl group-hover:bg-rose-500/10 transition-all"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-rose-500/10 h-10 w-10 rounded-lg flex items-center justify-center text-rose-500 border border-rose-500/20">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <span id="trend-expense" class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-2 py-1 rounded-md border border-rose-500/20">-5%</span>
                </div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-1">Gastos Operativos</p>
                <h2 id="kpi-expense" class="text-4xl font-black text-white tracking-tighter">${{ number_format($totalExpense, 0, ',', '.') }}</h2>
            </div>

            {{-- Utilidad --}}
            <div class="group bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-[#f3c444]/20 shadow-2xl relative overflow-hidden transition-all hover:border-[#f3c444]">
                <div class="absolute -right-4 -top-4 bg-[#f3c444]/5 w-24 h-24 rounded-full blur-3xl group-hover:bg-[#f3c444]/10 transition-all"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-[#f3c444]/10 h-10 w-10 rounded-lg flex items-center justify-center text-[#f3c444] border border-[#f3c444]/20">
                        <i class="fas fa-vault"></i>
                    </div>
                    <span id="trend-profit" class="text-[10px] font-black text-[#f3c444] bg-[#f3c444]/10 px-2 py-1 rounded-md border border-[#f3c444]/20">Proyectado</span>
                </div>
                <p class="text-[10px] font-black text-[#f3c444] uppercase tracking-[0.3em] mb-1">Utilidad Neta</p>
                <h2 id="kpi-profit" class="text-4xl font-black text-white tracking-tighter">${{ number_format($profit, 0, ',', '.') }}</h2>
            </div>
        </div>

        {{-- 📈 GRÁFICA PRINCIPAL CON LÍNEAS --}}
        <div class="bg-[#0a0a0c] p-10 rounded-[3rem] border border-white/5 shadow-2xl relative">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h3 id="chart-title" class="text-xl font-black text-white uppercase tracking-tighter ">Flujo de Caja Anual</h3>
                    <p class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">Comparativa histórica de movimientos</p>
                </div>
                <div class="flex gap-6">
                    <div class="flex items-center gap-2"><div class="h-1 w-4 bg-emerald-500 rounded-full"></div><span class="text-[10px] font-black text-zinc-500 uppercase">Ingresos</span></div>
                    <div class="flex items-center gap-2"><div class="h-1 w-4 bg-rose-500 rounded-full"></div><span class="text-[10px] font-black text-zinc-500 uppercase">Gastos</span></div>
                </div>
            </div>
            <div class="relative h-[400px] w-full">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
          <div class="bg-[#0a0a0c] p-10 rounded-[3rem] border border-white/5 shadow-2xl relative overflow-hidden">
    {{-- Efecto de fondo sutil --}}
    <div class="absolute -right-20 -bottom-20 bg-rose-500/5 w-64 h-64 rounded-full blur-[100px]"></div>

    <div class="flex items-center gap-4 mb-8">
        <div class="bg-rose-500/10 h-12 w-12 rounded-2xl flex items-center justify-center text-rose-500 border border-rose-500/20 shadow-[0_0_15px_rgba(244,63,94,0.2)]">
            <i class="fas fa-lock text-xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-black text-white uppercase tracking-tighter ">Cierre de Período</h3>
            <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Finalizar mes contable y proteger registros</p>
        </div>
    </div>

    <form method="POST" action="{{ route('accounting.periods.close') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Selector de Año --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ml-2">Año Fiscal</label>
                <div class="relative">
                    <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                    <input type="number" name="year" value="{{ now()->year }}" required
                        class="w-full bg-[#0d121f] border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white font-bold focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all outline-none">
                </div>
            </div>

            {{-- Selector de Mes --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ml-2">Mes a Cerrar</label>
                <div class="relative">
                    <i class="fas fa-moon absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                    <select name="month" required
                        class="w-full bg-[#0d121f] border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white font-bold focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all outline-none appearance-none">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Botón de Acción --}}
        <div class="pt-4">
            <button type="submit" 
                onclick="return confirm('¿Estás seguro de cerrar este período? Esta acción bloqueará todas las transacciones del mes seleccionado.')"
                class="group relative w-full bg-rose-600 hover:bg-rose-500 py-5 rounded-2xl text-white font-black uppercase tracking-[0.2em] text-[11px] transition-all shadow-[0_10px_30px_rgba(225,29,72,0.3)] overflow-hidden">
                <span class="relative z-10 flex items-center justify-center gap-3">
                    <i class="fas fa-shield-halved group-hover:rotate-12 transition-transform"></i>
                    Ejecutar Cierre Contable
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-shimmer"></div>
            </button>
        </div>

        <p class="text-center text-[9px] font-bold text-zinc-600 uppercase tracking-widest mt-4">
            <i class="fas fa-circle-info mr-1 text-rose-500/50"></i> 
            Los registros cerrados no podrán ser editados sin autorización del auditor.
        </p>
    </form>
    
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Plugin para mostrar valores en las series --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        let myChart;
        Chart.register(ChartDataLabels);

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('financeChart').getContext('2d');
            
            const g1 = ctx.createLinearGradient(0, 0, 0, 400);
            g1.addColorStop(0, 'rgba(16, 185, 129, 0.15)'); g1.addColorStop(1, 'transparent');
            const g2 = ctx.createLinearGradient(0, 0, 0, 400);
            g2.addColorStop(0, 'rgba(244, 63, 94, 0.15)'); g2.addColorStop(1, 'transparent');

            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: @json($incomeData),
                            borderColor: '#10b981',
                            backgroundColor: g1,
                            fill: true,
                            tension: 0.45,
                            borderWidth: 4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            datalabels: { align: 'top', color: '#10b981', font: { weight: 'bold', size: 9 } }
                        },
                        {
                            label: 'Gastos',
                            data: @json($expenseData),
                            borderColor: '#f43f5e',
                            backgroundColor: g2,
                            fill: true,
                            tension: 0.45,
                            borderWidth: 4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            datalabels: { align: 'bottom', color: '#f43f5e', font: { weight: 'bold', size: 9 } }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        datalabels: {
                            display: function(context) {
                                // Solo mostrar el último valor para no saturar
                                return context.dataIndex === context.dataset.data.length - 1;
                            },
                            formatter: (val) => '$' + val.toLocaleString()
                        }
                    },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.02)' }, ticks: { color: '#52525b', font: { size: 10, weight: 'bold' } } },
                        x: { grid: { display: false }, ticks: { color: '#52525b', font: { size: 10, weight: 'bold' } } }
                    }
                }
            });
        });

        function filterData(range) {
            // Actualización UI
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-[#f3c444]', 'text-black', 'shadow-lg');
                b.classList.add('text-zinc-500');
            });
            document.getElementById('btn-' + range).classList.add('bg-[#f3c444]', 'text-black', 'shadow-lg');

            // Textos dinámicos
            const titles = { 'day': 'Flujo de Hoy', 'month': 'Flujo Mensual', 'year': 'Flujo Anual' };
            document.getElementById('chart-title').innerText = titles[range];
            document.getElementById('dashboard-subtitle').innerText = `Tizzila Engine • Vista ${range.toUpperCase()}`;

            fetch(`{{ route('accounting.dashboard') }}?scale=${range}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                myChart.data.labels = data.labels;
                myChart.data.datasets[0].data = data.income;
                myChart.data.datasets[1].data = data.expense;
                myChart.update();

                document.getElementById('kpi-income').innerText = '$' + data.kpis.income;
                document.getElementById('kpi-expense').innerText = '$' + data.kpis.expense;
                document.getElementById('kpi-profit').innerText = '$' + data.kpis.profit;

                // Si tu controlador envía tendencias (opcional)
                if(data.trends) {
                    document.getElementById('trend-income').innerText = data.trends.income;
                    document.getElementById('trend-expense').innerText = data.trends.expense;
                }
            });
        }
    </script>
</x-app-layout>