@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4 fw-bold text-primary">➕ Nuevo Estudiante</h2>

    <form action="{{ route('admin.estudiantes.store') }}" method="POST">
        @csrf

        <!-- ===== Sección 1: Datos de Usuario (Acceso al sistema) ===== -->
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
                    <input type="text" name="rut" class="form-control" placeholder="Ej: 19.345.546-3" required>
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

                <!-- Rol oculto fijo -->
                <input type="hidden" name="rol_id" value="3">

            </div>
        </div>


        <!-- ===== Sección 2: Perfil Estudiantil ===== -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white fw-bold">
                Información personal y académica
            </div>

            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">RUN</label>
                    <input type="text" name="run" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" placeholder="+569...">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ciudad</label>
                    <input type="text" name="ciudad" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Carrera</label>
                    <input type="text" name="carrera" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Institución</label>
                    <input type="text" name="institucion" value="CFT Magallanes" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estado de la carrera</label>
                    <select name="estado_carrera" class="form-select">
                        <option value="En curso">En curso</option>
                        <option value="Titulado">Titulado</option>
                        <option value="Suspendido">Suspendido</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Año de egreso (si aplica)</label>
                    <input type="number" name="anio_egreso" class="form-control">
                </div>
            </div>
        </div>


        <!-- ===== Sección 3: Perfil Profesional ===== -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white fw-bold">
                Perfil y preferencias laborales
            </div>

            <div class="card-body row g-3">
                
                <div class="col-12">
                    <label class="form-label">Resumen profesional</label>
                    <textarea name="resumen" class="form-control" rows="3" placeholder="Ej: Técnico en administración con experiencia en..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Cursos / certificados</label>
                    <textarea name="cursos" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" name="linkedin_url" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Portafolio</label>
                    <input type="url" name="portfolio_url" class="form-control">
                </div>
            </div>
        </div>


        <!-- ===== Botones Finales ===== -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-secondary">Volver</a>
            <button type="submit" class="btn btn-primary fw-bold">Guardar Estudiante</button>
        </div>

    </form>
</div>
@endsection
