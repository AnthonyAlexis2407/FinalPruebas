@extends('layouts.app')

@section('page_title', 'Gestión de Stock')

@section('content')
    <div class="row justify-content-center">
        <!-- Elegant Product Header -->
        <div class="col-lg-10 mb-4">
            <div class="card border shadow-sm rounded-1">
                <div class="card-body p-3 d-flex align-items-center gap-4">
                    <div class="p-1 bg-light rounded-0 border">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="rounded-0" width="80"
                                height="80" style="object-fit: cover;">
                        @else
                            <div class="rounded-0 bg-white d-flex align-items-center justify-content-center border"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-image text-muted opacity-25 fs-4"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">
                            {{ $product->code }}</div>
                        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">{{ $product->name }}
                        </h4>
                        <div class="text-primary fw-bold fs-5">S/. {{ number_format($product->price, 2) }}</div>
                    </div>
                    <div class="ms-auto pe-2">
                        <a href="{{ route('products.index') }}"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-4 py-2 border-1 bg-white hover-shadow transition-all">
                            <i class="bi bi-arrow-left me-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Stock Update Section -->
        <div class="col-lg-10">
            <div class="card border shadow-sm rounded-1">
                <div class="card-body p-4">

                    <!-- Refined Update Form -->
                    <div class="p-4 bg-light rounded-1 border mb-5 shadow-sm">
                        <form action="{{ route('inventory.store', $product) }}" method="POST">
                            @csrf
                            <div class="row g-4 align-items-end">
                                <div class="col-md-5">
                                    <label for="size_id"
                                        class="form-label fw-bold small text-uppercase ls-1 text-muted mb-2">
                                        Presentación / Envase
                                    </label>
                                    <select name="size_id" id="size_id"
                                        class="form-select border shadow-sm py-2 rounded-1" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="stock"
                                        class="form-label fw-bold small text-uppercase ls-1 text-muted mb-2">
                                        Cantidad / Stock
                                    </label>
                                    <input type="number" name="stock" id="stock"
                                        class="form-control border shadow-sm py-2 rounded-1" min="0" required
                                        placeholder="0">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit"
                                        class="btn btn-primary w-100 py-2 rounded-1 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                        <i class="bi bi-arrow-repeat fs-5"></i> Actualizar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Clean Table Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                        <h6 class="fw-bold text-dark mb-0 ls-1">LISTADO DE PRESENTACIONES</h6>
                        <a href="{{ route('sizes.index') }}"
                            class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 bg-white shadow-sm border-1">
                            <i class="bi bi-gear-fill me-1"></i> Gestionar Tipos de Envases
                        </a>
                    </div>

                    <!-- Simplified Elegant Table -->
                    <div class="table-responsive rounded-1 border shadow-sm">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light border-bottom">
                                <tr class="text-muted small fw-bold text-uppercase ls-1">
                                    <th class="py-3 ps-4" style="width: 35%;">Presentación</th>
                                    <th class="py-3">Stock Actual</th>
                                    <th class="py-3 text-center">Estado</th>
                                    <th class="py-3 pe-4 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->sizes as $productSize)
                                    <tr class="transition-all">
                                        <td class="ps-4 py-3">
                                            <div class="fw-semibold text-dark fs-6">
                                                {{ $productSize->size->number ?? $productSize->name }}</div>
                                        </td>
                                        <td class="py-3">
                                            <span
                                                class="d-inline-block px-3 py-1 bg-white border rounded-1 text-dark fw-bold shadow-sm">
                                                {{ $productSize->stock }}
                                            </span>
                                        </td>
                                        <td class="text-center py-3">
                                            @if ($productSize->stock > 5)
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-0 fw-medium"
                                                    style="font-size: 0.7rem;">
                                                    Disponible
                                                </span>
                                            @elseif($productSize->stock > 0)
                                                <span
                                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-0 fw-medium"
                                                    style="font-size: 0.7rem;">
                                                    Bajo Stock
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-0 fw-medium"
                                                    style="font-size: 0.7rem;">
                                                    Agotado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end py-3">
                                            <form action="{{ route('inventory.destroy', [$product, $productSize]) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Eliminar esta presentación del producto? (El stock se perderá)');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-link text-danger p-0 opacity-25 hover-opacity-100 transition-all"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash3 fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-box-seam display-4 mb-3 d-block opacity-10"></i>
                                            <span class="ls-1 small fw-medium">No hay registros de inventario</span>
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

    <style>
        .ls-1 {
            letter-spacing: 0.05rem;
        }

        .hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            transform: translateY(-1px);
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .hover-opacity-100:hover {
            opacity: 1 !important;
        }

        .form-select,
        .form-control {
            font-size: 0.95rem;
        }

        .table th {
            font-size: 0.7rem !important;
        }
    </style>
@endsection
