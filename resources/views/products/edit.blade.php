@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                <div class="card-header bg-white p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">Editar Producto</h4>
                        <span class="badge bg-light text-dark">{{ $product->code }}</span>
                    </div>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="category_id"
                                class="form-label fw-bold text-muted text-uppercase small">Categoría</label>
                            <select name="category_id" id="category_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione una categoría</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="code"
                                    class="form-label fw-bold text-muted text-uppercase small">Código</label>
                                <input type="text" name="code" id="code" class="form-control form-control-lg"
                                    value="{{ $product->code }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="name"
                                    class="form-label fw-bold text-muted text-uppercase small">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control form-control-lg"
                                    value="{{ $product->name }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label fw-bold text-muted text-uppercase small">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 fw-bold">S/.</span>
                                <input type="number" step="0.01" name="price" id="price"
                                    class="form-control form-control-lg border-start-0 ps-0" value="{{ $product->price }}"
                                    required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="image" class="form-label fw-bold text-muted text-uppercase small">Imagen</label>
                            <div class="d-flex align-items-center gap-4">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="Actual"
                                        class="rounded-3 shadow-sm" width="100" height="100" style="object-fit: cover">
                                @endif
                                <div class="flex-grow-1">
                                    <input type="file" name="image" id="image" class="form-control"
                                        accept="image/*">
                                    <div class="form-text">Subir nueva imagen para reemplazar la actual.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
