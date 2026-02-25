@extends('layouts.app')

@section('content')
    <div class="row justify-content-center min-vh-100 align-items-center" style="margin-top: -80px;">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary"><i class="bi bi-shop me-2"></i>BobaCat</h1>
                <p class="text-muted">Inicia sesión para gestionar tu negocio</p>
            </div>
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-muted small text-uppercase">Correo
                                Electrónico</label>
                            <input type="email" name="email" id="email"
                                class="form-control form-control-lg bg-light border-0" placeholder="admin@bobacat.com"
                                required autofocus value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password"
                                class="form-label fw-bold text-muted small text-uppercase">Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg bg-light border-0" placeholder="••••••••" required>
                                <button class="btn btn-light border-0" type="button" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm mb-3">
                            Ingresar <i class="bi bi-arrow-right list-inline-item"></i>
                        </button>

                        <div class="text-center">
                            <small class="text-muted">¿Olvidaste tu contraseña? Contacta al soporte.</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    </script>
@endsection
