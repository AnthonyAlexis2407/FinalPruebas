@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold m-0">Reporte Mensual de Ventas</h2>
            <form action="{{ route('reports.monthly') }}" method="GET" class="d-flex gap-2">
                <select name="month" class="form-select" onchange="this.form.submit()">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @foreach (range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 opacity-75">Transacciones</h6>
                        <h3 class="fw-bold mb-0">{{ $totalTransactions }}</h3>
                        <small class="opacity-75">Ventas realizadas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 opacity-75">Ingresos Totales</h6>
                        <h3 class="fw-bold mb-0">S/. {{ number_format($totalRevenue, 2) }}</h3>
                        <small class="opacity-75">Dinero recaudado</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info text-dark h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 opacity-75">Ticket Promedio</h6>
                        <h3 class="fw-bold mb-0">S/. {{ number_format($averageTicket, 2) }}</h3>
                        <small class="opacity-75">Ingreso por venta</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-success">Tendencia de Ventas</h5>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3">
                        <i class="bi bi-graph-up"></i> Vista Gráfica
                    </span>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Table -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-secondary">Desglose Diario</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th class="text-center">Cant. Ventas</th>
                                    <th class="text-end" style="min-width: 200px;">Total Diario</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailySales as $day)
                                    @php
                                        $percent = $maxDailyRevenue > 0 ? ($day->total / $maxDailyRevenue) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">
                                                {{ \Carbon\Carbon::parse($day->date)->translatedFormat('d F, Y') }}</div>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($day->date)->translatedFormat('l') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill bg-light text-dark border px-3 py-2">{{ $day->count }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-bold text-success mb-1">S/. {{ number_format($day->total, 2) }}
                                            </div>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $percent }}%"></div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('reports.daily', ['date' => $day->date]) }}"
                                                class="btn btn-sm btn-outline-secondary rounded-circle" title="Ver detalle">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div class="mb-3">
                                                <i class="bi bi-calendar-x display-1 text-light"></i>
                                            </div>
                                            <h5>Sin movimientos</h5>
                                            <p class="small">No hay ventas registradas en este mes.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Ventas Diarias (S/.)',
                    data: {!! json_encode($chartValues) !!},
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(25, 135, 84, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'S/. ' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
