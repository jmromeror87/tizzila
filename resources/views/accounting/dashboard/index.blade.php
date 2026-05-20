{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Tablero <span class="text-yellow-500">Financiero</span>
                    </h2>
                    <p id="dashboard-subtitle" class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">Vista Anual {{ $year }}</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 bg-black/40 p-1 rounded-xl border border-white/5">
                <button onclick="filterData('day')" id="btn-day"
                    class="filter-btn px-4 py-2 text-zinc-500 text-[9px] font-black uppercase tracking-widest rounded-lg hover:text-white transition-all">Hoy</button>
                <button onclick="filterData('month')" id="btn-month"
                    class="filter-btn px-4 py-2 text-zinc-500 text-[9px] font-black uppercase tracking-widest rounded-lg hover:text-white transition-all">Mes</button>
                <button onclick="filterData('year')" id="btn-year"
                    class="filter-btn px-4 py-2 bg-yellow-500 text-black text-[9px] font-black uppercase tracking-widest rounded-lg transition-all">Año</button>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0d121f] border border-emerald-500/20 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                        <i class="fas fa-arrow-trend-up text-emerald-400 text-xs"></i>
                    </div>
                    <span id="trend-income" class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-lg">+12%</span>
                </div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Ingresos Totales</p>
                <h2 id="kpi-income" class="text-2xl font-black text-white">${{ number_format($totalIncome, 0, ',', '.') }}</h2>
            </div>

            <div class="bg-[#0d121f] border border-red-500/20 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-8 w-8 rounded-lg bg-red-500/10 flex items-center justify-center border border-red-500/20">
                        <i class="fas fa-hand-holding-dollar text-red-400 text-xs"></i>
                    </div>
                    <span id="trend-expense" class="text-[9px] font-black text-red-400 bg-red-500/10 border border-red-500/20 px-2 py-0.5 rounded-lg">-5%</span>
                </div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Gastos Operativos</p>
                <h2 id="kpi-expense" class="text-2xl font-black text-white">${{ number_format($totalExpense, 0, ',', '.') }}</h2>
            </div>

            <div class="bg-[#0d121f] border border-yellow-500/20 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-8 w-8 rounded-lg bg-yellow-500/10 flex items-center justify-center border border-yellow-500/20">
                        <i class="fas fa-vault text-yellow-500 text-xs"></i>
                    </div>
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">Neta</span>
                </div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-widest mb-1">Utilidad Neta</p>
                <h2 id="kpi-profit" class="text-2xl font-black text-white">${{ number_format($profit, 0, ',', '.') }}</h2>
            </div>
        </div>

        {{-- GRÁFICA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 id="chart-title" class="text-sm font-black text-white uppercase tracking-tighter">Flujo de Caja Anual</h3>
                    <p class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">Comparativa histórica de movimientos</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5"><div class="h-0.5 w-4 bg-emerald-500 rounded-full"></div><span class="text-[9px] font-black text-zinc-500 uppercase">Ingresos</span></div>
                    <div class="flex items-center gap-1.5"><div class="h-0.5 w-4 bg-red-500 rounded-full"></div><span class="text-[9px] font-black text-zinc-500 uppercase">Gastos</span></div>
                </div>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="financeChart"></canvas>
            </div>
        </div>

        {{-- CIERRE DE PERÍODO --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-9 w-9 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                    <i class="fas fa-lock text-red-400 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white uppercase tracking-tighter">Cierre de Período</h3>
                    <p class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest">Finalizar mes contable y proteger registros</p>
                </div>
            </div>

            <form method="POST" action="{{ route('accounting.periods.close') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Año Fiscal</label>
                        <input type="number" name="year" value="{{ now()->year }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Mes a Cerrar</label>
                        <select name="month" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit"
                    onclick="return confirm('¿Estás seguro de cerrar este período? Esta acción bloqueará todas las transacciones del mes seleccionado.')"
                    class="w-full bg-red-600 hover:bg-red-500 py-3.5 rounded-xl text-white font-black uppercase tracking-widest text-[10px] transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-shield-halved"></i> Ejecutar Cierre Contable
                </button>
                <p class="text-center text-[9px] font-bold text-zinc-600 uppercase tracking-widest">
                    <i class="fas fa-circle-info mr-1 text-red-500/50"></i>
                    Los registros cerrados no podrán ser editados sin autorización del auditor.
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        let myChart;
        Chart.register(ChartDataLabels);

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('financeChart').getContext('2d');

            const g1 = ctx.createLinearGradient(0, 0, 0, 300);
            g1.addColorStop(0, 'rgba(16,185,129,0.15)'); g1.addColorStop(1, 'transparent');
            const g2 = ctx.createLinearGradient(0, 0, 0, 300);
            g2.addColorStop(0, 'rgba(239,68,68,0.15)'); g2.addColorStop(1, 'transparent');

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
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            datalabels: { align: 'top', color: '#10b981', font: { weight: 'bold', size: 9 } }
                        },
                        {
                            label: 'Gastos',
                            data: @json($expenseData),
                            borderColor: '#ef4444',
                            backgroundColor: g2,
                            fill: true,
                            tension: 0.45,
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            datalabels: { align: 'bottom', color: '#ef4444', font: { weight: 'bold', size: 9 } }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            display: ctx => ctx.dataIndex === ctx.dataset.data.length - 1,
                            formatter: val => '$' + val.toLocaleString()
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
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-yellow-500', 'text-black');
                b.classList.add('text-zinc-500');
            });
            const active = document.getElementById('btn-' + range);
            active.classList.add('bg-yellow-500', 'text-black');
            active.classList.remove('text-zinc-500');

            const titles = { day: 'Flujo de Hoy', month: 'Flujo Mensual', year: 'Flujo Anual' };
            const subtitles = { day: 'Vista del Día', month: 'Vista Mensual', year: 'Vista Anual {{ $year }}' };
            document.getElementById('chart-title').innerText = titles[range];
            document.getElementById('dashboard-subtitle').innerText = subtitles[range];

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

                if (data.trends) {
                    document.getElementById('trend-income').innerText = data.trends.income;
                    document.getElementById('trend-expense').innerText = data.trends.expense;
                }
            });
        }
    </script>
</x-app-layout>
