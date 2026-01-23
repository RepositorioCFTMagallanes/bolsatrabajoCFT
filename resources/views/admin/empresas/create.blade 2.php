@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4 fw-bold text-primary">➕ Nueva Empresa</h2>

    <!-- Mostrar errores -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los errores antes de continuar:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.empresas.store') }}" method="POST">
        @csrf

        <!-- ===== Sección 1: Datos Usuario ===== -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white fw-bold">
                Datos del representante
            </div>
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre del representante *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">RUT *</label>
                    <input type="text" name="rut" class="form-control" value="{{ old('rut') }}" placeholder="Ej: 12.345.678-9" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej: +56912345678">
                </div>

                <!-- PASSWORD -->
                <div class="col-md-6">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmar contraseña *</label>
                    <input type="password" name="contrasena_confirmation" class="form-control" required>
                </div>

                <!-- Rol fijo -->
                <input type="hidden" name="rol_id" value="2">
            </div>
        </div>


        <!-- ===== Sección 2: Información Empresa ===== -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white fw-bold">
                Información Comercial
            </div>
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre comercial *</label>
                    <input type="text" name="razon_social" class="form-control" value="{{ old('razon_social') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sitio web</label>
                    <input type="url" name="sitio_web" class="form-control" value="{{ old('sitio_web') }}" placeholder="https://">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sector económico *</label>
                    <select name="sector" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Construcción">Construcción</option>
                        <option value="Educación">Educación</option>
                        <option value="Salud">Salud</option>
                        <option value="Retail">Retail</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- ===== Botones ===== -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.empresas.index') }}" class="btn btn-outline-secondary">Volver</a>
            <button type="submit" class="btn btn-primary fw-bold">Guardar Empresa</button>
        </div>

    </form>
</div>
@endsection
