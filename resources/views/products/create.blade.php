@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                <div class="card-header bg-primary text-white p-4 border-0">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-bag-plus me-2"></i> Nuevo Producto</h4>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="category_id"
                                class="form-label fw-bold text-muted text-uppercase small">Categoría</label>
                            <select name="category_id" id="category_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione una categoría</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="code"
                                    class="form-label fw-bold text-muted text-uppercase small">Código</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-upc-scan"></i></span>
                                    <input type="text" name="code" id="code"
                                        class="form-control form-control-lg border-start-0 ps-0"
                                        placeholder="Ej: BEB-001 / SNA-001" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="name"
                                    class="form-label fw-bold text-muted text-uppercase small">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control form-control-lg"
                                    placeholder="Ej: Té Boba Clásico / Alitas BBQ" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label fw-bold text-muted text-uppercase small">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 fw-bold">S/.</span>
                                <input type="number" step="0.01" name="price" id="price"
                                    class="form-control form-control-lg border-start-0 ps-0" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="image" class="form-label fw-bold text-muted text-uppercase small">Imagen del
                                Producto</label>
                            <input type="file" name="image" id="image" class="form-control form-control-lg"
                                accept="image/*">
                            <div class="form-text">Formatos permitidos: JPG, PNG, WEBP</div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">Guardar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
