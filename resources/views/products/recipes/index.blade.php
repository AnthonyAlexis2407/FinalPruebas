@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Gestión de Recetas</h2>
                    <p class="text-muted mb-0">
                        Configura los insumos para: <span class="fw-bold text-primary">{{ $product->name }}</span>
                    </p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Agregar Insumo</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.recipes.store', $product) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">1. Presentación
                                (Venta)</label>
                            <select name="product_size_id" class="form-select form-select-lg bg-light border-0" required>
                                @foreach ($product->sizes as $pSize)
                                    <option value="{{ $pSize->id }}">{{ $pSize->size->number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">2. Insumo a Descontar</label>
                            <select name="component_product_size_id" class="form-select form-select-lg bg-light border-0"
                                required>
                                <option value="">Seleccionar Insumo...</option>
                                @foreach ($supplies as $supply)
                                    <optgroup label="{{ $supply->name }}">
                                        @foreach ($supply->sizes as $sSize)
                                            <option value="{{ $sSize->id }}">
                                                {{ $supply->name }} - {{ $sSize->size->number }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="form-text">Solo se muestran productos de la categoría 'Insumos'.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">3. Cantidad</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="quantity"
                                    class="form-control form-control-lg bg-light border-0" value="1" min="0.1"
                                    required>
                                <span class="input-group-text bg-light border-0">unid.</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i> Agregar a Receta
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small fw-bold uppercase">Presentación</th>
                                    <th class="py-3 text-muted small fw-bold uppercase">Insumo Asociado</th>
                                    <th class="py-3 text-muted small fw-bold uppercase text-center">Cantidad</th>
                                    <th class="pe-4 py-3 text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->sizes as $pSize)
                                    @foreach ($pSize->recipes as $recipe)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                                    {{ $pSize->size->number }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-box-seam text-secondary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">
                                                            {{ $recipe->componentProductSize->product->name }}</div>
                                                        <div class="text-muted small">
                                                            {{ $recipe->componentProductSize->size->number }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center py-3">
                                                <span class="fw-bold text-dark">{{ floatval($recipe->quantity) }}</span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <form action="{{ route('products.recipes.destroy', [$product, $recipe]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Eliminar este insumo de la receta?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-link text-danger p-0 opacity-50 hover-opacity-100">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-clipboard-x display-6 mb-3 d-block opacity-25"></i>
                                            Sin presentaciones configuradas.
                                        </td>
                                    </tr>
                                @endforelse

                                @if ($product->sizes->isNotEmpty() && $product->sizes->pluck('recipes')->flatten()->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-basket display-6 mb-3 d-block opacity-25"></i>
                                            No hay insumos configurados para este producto.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
