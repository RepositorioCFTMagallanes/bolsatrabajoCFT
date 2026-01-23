@extends('layouts.admin')

@section('admin-content')

<div class="admin-container">

    <div class="panel-header">
        <h2 class="title">Crear nuevo recurso</h2>
    </div>

    <form action="{{ route('admin.recursos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- TÍTULO -->
        <div class="form-group">
            <label>Título del recurso</label>
            <input 
                type="text" 
                name="titulo" 
                class="input-text" 
                placeholder="Ej: Cómo preparar tu CV"
                required>
        </div>

        <!-- RESUMEN -->
        <div class="form-group">
            <label>Resumen (máx. 250 caracteres)</label>
            <textarea 
                name="resumen" 
                class="input-text"
                maxlength="250"
                rows="2"
                placeholder="Descripción corta que aparecerá en el inicio..."
            ></textarea>
        </div>

        <!-- CONTENIDO -->
        <div class="form-group">
            <label>Contenido del artículo</label>
            <textarea 
                name="contenido" 
                class="input-text"
                rows="6"
                placeholder="Escribe aquí el contenido completo del blog..."
            ></textarea>
        </div>

        <!-- IMAGEN -->
        <div class="form-group">
            <label>Imagen destacada</label>
            <input 
                type="file" 
                name="imagen" 
                accept="image/*"
                class="input-text">

            <small>Formatos recomendados: JPG / PNG — 1200x600</small>
        </div>

        <!-- ESTADO -->
        <div class="form-group">
            <label>Estado</label>
            <select name="estado" class="input-text">
                <option value="1">Publicado</option>
                <option value="0">Borrador</option>
            </select>
        </div>

        <!-- BOTONES -->
        <div style="margin-top: 20px;">
            <button class="btn-primary">Guardar recurso</button>
            <a href="{{ route('admin.recursos.index') }}" class="btn-secondary">Volver</a>
        </div>

    </form>

</div>

@endsection
