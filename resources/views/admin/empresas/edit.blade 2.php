@extends('layouts.app')

@section('content')
<main class="admin-form container py-4">

    <h2 class="fw-bold text-primary">✏️ Editar Empresa</h2>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.empresas.update', $empresa->id) }}">
        @csrf
        @method('PUT')

        <!-- Usuario -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white fw-bold">
                Datos del representante
            </div>
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control"
                        value="{{ old('nombre', $empresa->nombre) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $empresa->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">RUT *</label>
                    <input type="text" name="rut" class="form-control"
                        value="{{ old('rut', $empresa->rut) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                        value="{{ old('telefono', $empresa->empresa->telefono_contacto ?? '') }}">
                </div>
            </div>
        </div>

        <!-- Empresa -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white fw-bold">
                Información Comercial
            </div>
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre comercial *</label>
                    <input type="text" name="razon_social" class="form-control"
                        value="{{ old('razon_social', $empresa->empresa->razon_social ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sitio web</label>
                    <input type="url" name="sitio_web" class="form-control"
                        value="{{ old('sitio_web', $empresa->empresa->sitio_web ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sector económico *</label>
                    <select name="sector" class="form-select" required>
                        @php
                            $sector = old('sector', $empresa->empresa->rubro ?? 'No informado');
                        @endphp
                        <option value="No informado" {{ $sector == "No informado" ? "selected" : "" }}>Seleccionar...</option>
                        <option value="Tecnología" {{ $sector == "Tecnología" ? "selected" : "" }}>Tecnología</option>
                        <option value="Construcción" {{ $sector == "Construcción" ? "selected" : "" }}>Construcción</option>
                        <option value="Educación" {{ $sector == "Educación" ? "selected" : "" }}>Educación</option>
                        <option value="Salud" {{ $sector == "Salud" ? "selected" : "" }}>Salud</option>
                        <option value="Retail" {{ $sector == "Retail" ? "selected" : "" }}>Retail</option>
                        <option value="Otro" {{ $sector == "Otro" ? "selected" : "" }}>Otro</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.empresas.index') }}" class="btn btn-outline-secondary">Volver</a>
            <button type="submit" class="btn btn-primary fw-bold">Actualizar Empresa</button>
        </div>

    </form>
</main>
@endsection
