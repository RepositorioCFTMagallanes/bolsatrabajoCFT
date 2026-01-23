@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        <div class="panel-header">
            <h2 class="title">Editar recurso</h2>
        </div>

        <form action="{{ route('admin.recursos.update', $recurso->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- TÍTULO -->
            <div class="form-group">
                <label>Título del recurso</label>
                <input type="text" name="titulo" class="input-text" value="{{ $recurso->titulo }}" required>
            </div>

            <!-- RESUMEN -->
            <div class="form-group">
                <label>Resumen (máx. 250 caracteres)</label>
                <textarea name="resumen" class="input-text" maxlength="250" rows="2">{{ $recurso->resumen }}</textarea>
            </div>

            <!-- CONTENIDO -->
            <div class="form-group">
                <label>Contenido del artículo</label>
                <textarea name="contenido" class="input-text" rows="6">{{ $recurso->contenido }}</textarea>
            </div>

            <!-- IMAGEN -->
            <div class="form-group">
                <label>Imagen destacada</label>

                <input type="file" name="imagen" accept="image/*" class="input-text">

                @if ($recurso->imagen)
                    <div style="margin-top: 10px;">
                        <p>Imagen actual:</p>
                        <img src="{{ asset($recurso->imagen) }}" alt="Imagen actual"
                            style="width: 250px; border-radius: 8px;">
                    </div>
                @endif

                <small>Si subes una nueva imagen, reemplazará la actual.</small>
            </div>

            <!-- ESTADO -->
            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="input-text">
                    <option value="1" {{ $recurso->estado == 1 ? 'selected' : '' }}>Publicado</option>
                    <option value="0" {{ $recurso->estado == 0 ? 'selected' : '' }}>Borrador</option>
                </select>
            </div>

            <!-- BOTONES -->
            <div style="margin-top: 20px;">
                <button class="btn-primary">Actualizar recurso</button>
                <a href="{{ route('admin.recursos.index') }}" class="btn-secondary">Volver</a>
            </div>

        </form>

    </div>
@endsection
