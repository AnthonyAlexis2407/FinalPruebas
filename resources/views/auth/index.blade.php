@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Gestión de Usuarios</h1>
            <p class="text-muted">Administra el acceso a la plataforma</p>
        </div>
        @if (Auth::user()->isAdmin())
            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-person-plus me-2"></i> Nuevo Usuario
            </a>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="py-3 ps-4">Nombre</th>
                            <th class="py-3">Correo</th>
                            <th class="py-3">Rol</th>
                            <th class="py-3">Tienda</th>
                            <th class="py-3">Fecha Registro</th>
                            @if (Auth::user()->isAdmin())
                                <th class="py-3">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @if ($user->isAdmin())
                                        <span
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">Admin</span>
                                    @else
                                        <span
                                            class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">Cajero</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-shop me-1"></i>
                                    {{ $user->store->name ?? 'Global' }}
                                </td>
                                <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                @if (Auth::user()->isAdmin())
                                    <td>
                                        @if (Auth::id() !== $user->id)
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                                data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                <i class="bi bi-trash me-1"></i> Eliminar
                                            </button>
                                        @else
                                            <span class="badge bg-light text-muted rounded-pill px-3">Tú</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="deleteUserModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar al usuario <strong id="deleteUserName"></strong>? Esta
                            acción no se puede deshacer.</p>
                        <div class="mb-3">
                            <label for="admin_password"
                                class="form-label small text-muted text-uppercase fw-bold">Contraseña de
                                Administrador</label>
                            <input type="password" name="admin_password" class="form-control rounded-3" id="admin_password"
                                required placeholder="Ingresa tu contraseña para confirmar">
                            @error('admin_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Eliminar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteUserModal = document.getElementById('deleteUserModal');
            const deleteUserForm = document.getElementById('deleteUserForm');
            const deleteUserNameSpan = document.getElementById('deleteUserName');

            if (deleteUserModal) {
                deleteUserModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    if (button) {
                        const userId = button.getAttribute('data-user-id');
                        const userName = button.getAttribute('data-user-name');

                        deleteUserForm.action = "{{ route('users.destroy', ':id') }}".replace(':id',
                            userId);
                        deleteUserNameSpan.textContent = userName;
                    }
                });
            }

            @if ($errors->has('admin_password') && session('target_user_id'))
                const lastUserId = "{{ session('target_user_id') }}";
                const lastUserName = "{{ session('target_user_name') }}";

                deleteUserForm.action = "{{ route('users.destroy', ':id') }}".replace(':id', lastUserId);
                deleteUserNameSpan.textContent = lastUserName;

                const bsModal = new bootstrap.Modal(deleteUserModal);
                bsModal.show();
            @endif
        });
    </script>
@endsection
