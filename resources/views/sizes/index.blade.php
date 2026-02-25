@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Gestión de Presentaciones</h2>
                        <p class="text-muted mb-0">Crea y administra los tipos de envases/tamaños globales.</p>
                    </div>
                </div>
            </div>

            <!-- Create New Size -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">Nueva Presentación</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('sizes.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Nombre / Capacidad</label>
                                <input type="text" name="number" class="form-control form-control-lg bg-light border-0"
                                    placeholder="Ej: Litro, Vaso Jumbo, Caja x6" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Categoría
                                    (Opcional)</label>
                                <select name="category_id" class="form-select form-select-lg bg-light border-0">
                                    <option value="">-- Global (Para todos) --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Si seleccionas una categoría, este tamaño solo aparecerá para
                                    productos de esa categoría.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-save me-2"></i> Guardar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List Existing Sizes -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small fw-bold uppercase">Nombre</th>
                                        <th class="py-3 text-muted small fw-bold uppercase">Ámbito / Categoría</th>
                                        <th class="pe-4 py-3 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sizes as $size)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">{{ $size->number }}</td>
                                            <td>
                                                @if ($size->category)
                                                    <span
                                                        class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                        <i class="bi bi-tag-fill me-1"></i> {{ $size->category->name }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                                        <i class="bi bi-globe me-1"></i> Global
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <!-- Edit Modal Trigger (Simplified: Use updated form in logic or modal) -->
                                                    <!-- For simplicity/speed in MVP, we might skip edit modal and just rely on delete/recreate or assume edit isn't priority if delete works.
                                                                 But let's add a basic delete. -->

                                                    <form action="{{ route('sizes.destroy', $size) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar esta presentación global? Solo se puede si ningún producto la usa.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger border-0">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
