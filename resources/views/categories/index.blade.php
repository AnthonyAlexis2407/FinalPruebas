@extends('layouts.app')

@section('page_title', 'Gestión de Categorías')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Categorías</h4>
                    <p class="text-muted small mb-0">Gestiona las clasificaciones de productos para esta tienda.</p>
                </div>
                @if (Auth::user()->isAdmin())
                    <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#createCategoryModal">
                        <i class="bi bi-plus-lg"></i> Nueva Categoría
                    </button>
                @endif
            </div>

            <!-- Categories Table -->
            <div class="card border shadow-sm rounded-1">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light border-bottom">
                            <tr class="text-muted small fw-bold text-uppercase ls-1">
                                <th class="py-3 ps-4">Nombre</th>
                                <th class="py-3 text-center">Estado</th>
                                @if (Auth::user()->isAdmin())
                                    <th class="py-3 pe-4 text-end">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-semibold text-dark">{{ $category->name }}</div>
                                    </td>
                                    <td class="text-center py-3">
                                        @if ($category->active)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-0 fw-medium"
                                                style="font-size: 0.7rem;">Activa</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-0 fw-medium"
                                                style="font-size: 0.7rem;">Inactiva</span>
                                        @endif
                                    </td>
                                    @if (Auth::user()->isAdmin())
                                        <td class="pe-4 text-end py-3">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button
                                                    class="btn btn-sm btn-outline-secondary border rounded-0 bg-white action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCategoryModal{{ $category->id }}" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-secondary border rounded-0 bg-white action-btn text-danger"
                                                        title="Eliminar">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-1">
                                                        <div class="modal-header border-bottom py-3">
                                                            <h5 class="modal-title fw-bold">Editar Categoría</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('categories.update', $category) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body p-4 text-start">
                                                                <div class="mb-3">
                                                                    <label
                                                                        class="form-label small fw-bold text-uppercase ls-1 text-muted">Nombre</label>
                                                                    <input type="text" name="name"
                                                                        class="form-control border rounded-1 py-2"
                                                                        value="{{ $category->name }}" required>
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="active" value="1"
                                                                        {{ $category->active ? 'checked' : '' }}>
                                                                    <label
                                                                        class="form-check-label small fw-bold text-muted">Categoría
                                                                        Activa</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top py-3">
                                                                <button type="button" class="btn btn-light rounded-1 px-4"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary rounded-1 px-4">Guardar
                                                                    Cambios</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-tags display-4 mb-3 d-block opacity-10"></i>
                                        <span class="ls-1 small fw-medium">No hay categorías registradas</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->isAdmin())
        <!-- Create Modal -->
        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-1">
                    <div class="modal-header border-bottom py-3">
                        <h5 class="modal-title fw-bold">Nueva Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3 text-start">
                                <label class="form-label small fw-bold text-uppercase ls-1 text-muted">Nombre de la
                                    Categoría</label>
                                <input type="text" name="name" class="form-control border rounded-1 py-2"
                                    placeholder="Ej: Bebidas, Postres..." required>
                            </div>
                            <div class="form-check form-switch text-start">
                                <input class="form-check-input" type="checkbox" name="active" value="1" checked>
                                <label class="form-check-label small fw-bold text-muted">Activar al crear</label>
                            </div>
                        </div>
                        <div class="modal-footer border-top py-3">
                            <button type="button" class="btn btn-light rounded-1 px-4"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-1 px-4">Crear Categoría</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .ls-1 {
            letter-spacing: 0.05rem;
        }

        .action-btn {
            transition: all 0.2s ease;
            color: #64748b;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background-color: #f8fafc !important;
            border-color: var(--primary-color) !important;
            color: var(--primary-color) !important;
        }

        .action-btn.text-danger:hover {
            color: #ef4444 !important;
            border-color: #ef4444 !important;
        }
    </style>
@endsection
