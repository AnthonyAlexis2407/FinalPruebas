@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold m-0">Reporte Diario de Ventas</h2>
            <form action="{{ route('reports.daily') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="date" class="form-control" value="{{ $date }}"
                    onchange="this.form.submit()">
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2 opacity-75">Ventas Totales</h6>
                    <h3 class="fw-bold mb-0">{{ $totalSales }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2 opacity-75">Ingresos Totales</h6>
                    <h3 class="fw-bold mb-0">S/. {{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-primary">Balance Diario (Ventas por Hora)</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4"># Venta</th>
                                    <th>Hora</th>
                                    <th>Productos</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td class="ps-4 fw-bold">#{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $sale->created_at->format('H:i A') }}</td>
                                        <td>
                                            <small class="text-muted d-block">{{ $sale->details->count() }} items</small>
                                            @foreach ($sale->details->take(2) as $detail)
                                                <div class="text-truncate" style="max-width: 200px;">
                                                    - {{ $detail->productSize->product->name }} ({{ $detail->quantity }})
                                                </div>
                                            @endforeach
                                            @if ($sale->details->count() > 2)
                                                <small class="text-muted">...+{{ $sale->details->count() - 2 }} más</small>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">S/. {{ number_format($sale->total, 2) }}</td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('sales.ticket', $sale) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-dark" title="Ticket">
                                                    <i class="bi bi-receipt"></i>
                                                </a>
                                                <a href="{{ route('sales.print', $sale) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary" title="Factura A4">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                            No hay ventas registradas en esta fecha.
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
        const ctx = document.getElementById('dailyChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($hours) !!},
                datasets: [{
                    label: 'Ventas (S/.)',
                    data: {!! json_encode($amounts) !!},
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
