@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4 fw-bold text-primary">➕ Nuevo Administrador</h2>

    <form action="{{ route('admin.administradores.store') }}" method="POST">
        @csrf

        <!-- ===== Sección: Datos de Acceso ===== -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white fw-bold">
                Datos de acceso
            </div>

            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">RUT *</label>
                    <input type="text" name="rut" class="form-control"
                        placeholder="Ej: 19.345.546-3" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmar contraseña *</label>
                    <input type="password" name="contrasena_confirmation" class="form-control" required>
                </div>

                <!-- Rol fijo Administrador -->
                <input type="hidden" name="rol_id" value="1">

            </div>
        </div>

        <!-- BOTONES -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.administradores.index') }}" class="btn btn-outline-secondary">
                Volver
            </a>
            <button type="submit" class="btn btn-primary fw-bold">
                Guardar Administrador
            </button>
        </div>

    </form>
</div>
@endsection
