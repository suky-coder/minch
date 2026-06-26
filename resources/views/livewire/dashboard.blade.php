<div x-data="dashboardCharts()" x-init="initCharts()" class="space-y-6">
    
    {{-- Tarjetas de estadísticas mejoradas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Proveedores</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalSuppliers }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-blue-100 text-sm">Total registrados</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium uppercase tracking-wider">Movimientos</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalMovements }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-100 text-sm">Total transacciones</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium uppercase tracking-wider">Ingresos (Debe)</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totalDebit, 2) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-emerald-100 text-sm">Total ingresos</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium uppercase tracking-wider">Egresos (Haber)</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totalCredit, 2) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-red-100 text-sm">Total egresos</span>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Movimientos Mensuales</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">Últimos 6 meses</span>
            </div>
            <div id="columnChart" style="height: 350px;"></div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Distribución por Tipo</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">Porcentaje</span>
            </div>
            <div id="pieChart" style="height: 350px;"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Saldo por Cuenta</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">Balance</span>
            </div>
            <div id="barChart" style="height: 350px;"></div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Últimos Movimientos</h3>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">Ver todos →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider py-3">Fecha</th>
                            <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider py-3">Descripción</th>
                            <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider py-3">Tipo</th>
                            <th class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider py-3">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($recentMovements as $movement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="py-3 text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y') }}</td>
                            <td class="py-3 text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($movement->description, 30) }}</td>
                            <td class="py-3 text-sm">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $movement->type == 'D' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       ($movement->type == 'C' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200') }}">
                                    {{ $movement->type == 'D' ? 'Debe' : ($movement->type == 'C' ? 'Haber' : 'Saldo') }}
                                </span>
                            </td>
                            <td class="py-3 text-sm text-right font-medium {{ $movement->type == 'D' ? 'text-green-600 dark:text-green-400' : ($movement->type == 'C' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }}">
                                {{ number_format($movement->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">No hay movimientos recientes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function dashboardCharts() {
            return {
                initCharts() {
                    // Column Chart
                    const columnOptions = {
                        series: [{
                            name: 'Debe (Ingresos)',
                            data: @json($chartData['debit'])
                        }, {
                            name: 'Haber (Egresos)',
                            data: @json($chartData['credit'])
                        }],
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '60%',
                                endingShape: 'rounded',
                                borderRadius: 4
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: @json($chartData['categories']),
                            labels: {
                                style: {
                                    colors: '#6B7280',
                                    fontSize: '12px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 500
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Monto (Bs)',
                                style: {
                                    color: '#6B7280',
                                    fontSize: '12px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 500
                                }
                            },
                            labels: {
                                formatter: function(value) {
                                    return value.toFixed(0);
                                }
                            }
                        },
                        fill: {
                            opacity: 1,
                            colors: ['#10B981', '#EF4444']
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(2) + ' Bs'
                                }
                            },
                            theme: 'dark'
                        },
                        grid: {
                            borderColor: '#E5E7EB',
                            strokeDashArray: 4,
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                colors: '#6B7280',
                                useSeriesColors: false
                            }
                        }
                    };

                    const columnChart = new ApexCharts(document.querySelector("#columnChart"), columnOptions);
                    columnChart.render();

                    // Pie Chart
                    const pieOptions = {
                        series: @json($pieData['series']),
                        chart: {
                            type: 'pie',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        labels: @json($pieData['labels']),
                        colors: @json($pieData['colors']),
                        legend: {
                            position: 'bottom',
                            labels: {
                                colors: '#6B7280',
                                useSeriesColors: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val, opts) {
                                return opts.w.globals.series[opts.seriesIndex] + ' (' + val.toFixed(1) + '%)'
                            },
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 500
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + ' movimientos'
                                }
                            },
                            theme: 'dark'
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: '100%'
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    const pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
                    pieChart.render();

                    // Bar Chart Horizontal
                    const barOptions = {
                        series: [{
                            name: 'Saldo',
                            data: @json($barData['series'])
                        }],
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                horizontal: true,
                                barHeight: '60%',
                                colors: {
                                    ranges: [{
                                        from: -1000000,
                                        to: -0.01,
                                        color: '#EF4444'
                                    }, {
                                        from: 0,
                                        to: 1000000,
                                        color: '#10B981'
                                    }]
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return val.toFixed(2)
                            },
                            style: {
                                fontSize: '11px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 500,
                                colors: ['#6B7280']
                            }
                        },
                        xaxis: {
                            categories: @json($barData['categories']),
                            labels: {
                                style: {
                                    colors: '#6B7280',
                                    fontSize: '12px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 500
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#6B7280',
                                    fontSize: '12px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 500
                                }
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(2) + ' Bs'
                                }
                            },
                            theme: 'dark'
                        },
                        grid: {
                            borderColor: '#E5E7EB',
                            strokeDashArray: 4,
                        },
                        legend: {
                            show: false
                        }
                    };

                    const barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
                    barChart.render();
                }
            }
        }
    </script>
</div>