@extends('layouts.app')

@section('page_title', 'Catálogo de Inventario')

@section('content')
    <!-- Elegant Header Section -->
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark m-0" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">Catálogo
                    de Productos</h3>
                <p class="text-muted small mb-0">Gestión profesional de inventario y suministros</p>
            </div>
            @if (Auth::user()->isAdmin())
                <div class="d-flex gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-plus-lg"></i> Nuevo Producto
                    </a>
                </div>
            @endif
        </div>

        <!-- Reference-style Search and Filters -->
        <div class="card border shadow-sm rounded-1">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0 py-2 small"
                                placeholder="Buscar producto...">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex gap-2 overflow-auto pb-1">
                            <a href="{{ route('products.index') }}"
                                class="category-pill {{ !request('category') ? 'active' : '' }}">
                                Todo
                            </a>
                            @foreach ($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="category-pill {{ request('category') == $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 border shadow-sm product-card transition-all rounded-0">
                    <div class="position-relative overflow-hidden border-bottom" style="background: #fcfcfc;">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                alt="{{ $product->name }}"
                                style="height: 140px; object-fit: contain; padding: 10px; transition: transform 0.5s;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                <i class="bi bi-box-seam text-secondary opacity-25 fs-2"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-white text-dark shadow-sm px-2 py-1 rounded-pill small fw-medium">
                                {{ $product->category->name ?? 'General' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <div class="mb-1 text-muted x-small text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">
                            {{ $product->code }}
                        </div>
                        <h6 class="fw-semibold mb-1 text-truncate text-dark" style="font-size: 0.95rem;"
                            title="{{ $product->name }}">{{ $product->name }}</h6>
                        <div class="text-primary fw-bold mb-3" style="font-size: 1.1rem;">
                            S/. {{ number_format($product->price, 2) }}</div>

                        <div class="mt-auto">
                            <div class="d-flex flex-wrap gap-1 mb-0">
                                @forelse($product->sizes as $productSize)
                                    <span
                                        class="badge {{ $productSize->stock > 0 ? 'bg-light text-dark' : 'bg-danger-subtle text-danger' }} rounded-0 small border fw-medium"
                                        style="font-size: 0.65rem; padding: 0.35rem 0.6rem;">
                                        {{ $productSize->size->number ?? $productSize->name }} ({{ $productSize->stock }})
                                    </span>
                                @empty
                                    <span class="text-muted italic small">Sin stock</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 p-3 pt-0">
                        <hr class="mt-0 mb-3 opacity-10">
                        <div class="d-flex gap-1">
                            <a href="{{ route('inventory.index', $product) }}"
                                class="btn btn-sm btn-outline-secondary w-100 py-2 border rounded-0 bg-white action-btn"
                                title="Stock">
                                <i class="bi bi-box"></i>
                            </a>

                            @if ($product->category && $product->category->name !== 'Insumos')
                                <a href="{{ route('products.recipes.index', $product) }}"
                                    class="btn btn-sm btn-outline-secondary w-100 py-2 border rounded-0 bg-white action-btn"
                                    title="Receta">
                                    <i class="bi bi-list-stars"></i>
                                </a>
                            @endif

                            @if (Auth::user()->isAdmin())
                                <a href="{{ route('products.edit', $product) }}"
                                    class="btn btn-sm btn-outline-secondary w-100 py-2 border rounded-0 bg-white action-btn"
                                    title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            @endif
                        </div>

                        @if (Auth::user()->isAdmin())
                            <div class="mt-2 text-center">
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-link text-danger text-decoration-none p-0 small opacity-75 hover-opacity-100"
                                        style="font-size: 0.75rem;">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted mb-4">
                    <i class="bi bi-box-seam display-1"></i>
                </div>
                <h3>No se encontraron productos</h3>
                <p class="text-muted">Intenta seleccionar otra categoría o agrega un nuevo producto.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">Agregar Producto</a>
            </div>
        @endforelse
    </div>

    <style>
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        }

        .product-card:hover .card-img-top {
            transform: scale(1.08);
        }

        .action-btn {
            transition: all 0.2s ease;
            color: #64748b;
        }

        .action-btn:hover {
            background-color: #f8fafc !important;
            border-color: var(--primary-color) !important;
            color: var(--primary-color) !important;
            box-shadow: inset 0 0 0 1px var(--primary-color);
        }

        .action-btn:active,
        .action-btn.active {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        .x-small {
            font-size: 0.65rem;
        }

        .ls-1 {
            letter-spacing: 0.5px;
        }

        .hover-opacity-100:hover {
            opacity: 1 !important;
        }
    </style>
@endsection
