@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white p-4 border-bottom">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i> Registrar Nuevo Usuario</h4>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-muted small text-uppercase">Nombre
                                Completo</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg"
                                required value="{{ old('name') }}">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-muted small text-uppercase">Correo
                                Electrónico</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg"
                                required value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="role" class="form-label fw-bold text-muted small text-uppercase">Rol /
                                    Permisos</label>
                                <select name="role" id="role" class="form-select form-select-lg" required>
                                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cajero
                                        (Restringido)</option>
                                    <option value="admin" {{ old('role', 'admin') == 'admin' ? 'selected' : '' }}>
                                        Administrador (Global)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="store_id" class="form-label fw-bold text-muted small text-uppercase">Tienda
                                    Asignada</label>
                                <select name="store_id" id="store_id" class="form-select form-select-lg">
                                    <option value="">Todas (Solo para Admins)</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}"
                                            {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-muted small mt-1">Los cajeros DEBEN tener una tienda asignada.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password"
                                    class="form-label fw-bold text-muted small text-uppercase">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control form-control-lg" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation"
                                    class="form-label fw-bold text-muted small text-uppercase">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control form-control-lg" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="bi bi-eye-slash" id="toggleConfirmIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-lg px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">Registrar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setupToggle(btnId, inputId, iconId) {
            const btn = document.getElementById(btnId);
            if (!btn) return;
            btn.addEventListener('click', function() {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        }

        setupToggle('togglePassword', 'password', 'toggleIcon');
        setupToggle('toggleConfirmPassword', 'password_confirmation', 'toggleConfirmIcon');
    </script>
@endsection
