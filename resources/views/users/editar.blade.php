@extends('layouts.app')

@section('content')
@php
use Illuminate\Support\Facades\Storage;

$avatarUrl = asset('img/testimonios/test (2).png');
$cvUrl = null;

try {
    if (isset($estudiante) && $estudiante && !empty($estudiante->avatar)) {
        $avatarUrl = Storage::disk('gcs')->url($estudiante->avatar);
    }

    if (isset($estudiante) && $estudiante && !empty($estudiante->ruta_cv)) {
        $cvUrl = Storage::disk('gcs')->url($estudiante->ruta_cv);
    }
} catch (\Throwable $e) {
    // fallback silencioso si falla GCS
    $avatarUrl = asset('img/testimonios/test (2).png');
    $cvUrl = null;
}
@endphp

<main class="container user-edit">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <ul>
            <li><a href="{{ route('usuarios.perfil') }}">Perfil postulante</a></li>
            <li class="current" aria-current="page">Editar perfil</li>
        </ul>
    </nav>

    {{-- Encabezado --}}
    <header class="page-header">
        <h1>Editar Perfil</h1>
        <p class="muted">Actualiza tu información para mejorar tus postulaciones.</p>
    </header>

    <form action="{{ url('/usuarios/editar') }}" method="post" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        {{-- IDENTIDAD --}}
        <section class="card">
            <h2>Identidad</h2>
            <div class="grid-2">

                <div class="field">
                    <label for="avatar">Foto de perfil</label>
                    <div class="avatar-row">
                        <img class="avatar-preview"
                            src="{{ $avatarUrl }}"
                            alt="Avatar actual">

                        <div class="avatar-actions">
                            <input type="file" id="avatar" name="avatar" accept="image/*">
                            <p class="hint">Formatos: JPG o PNG, máx. 2MB.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="grid-2">
                        <div class="field">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" name="nombre" type="text"
                                value="{{ $estudiante->usuario->nombre ?? '' }}">
                        </div>

                        <div class="field">
                            <label for="apellido">Apellido</label>
                            <input id="apellido" name="apellido" type="text"
                                value="{{ $estudiante->usuario->apellido ?? '' }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label for="run">RUN (opcional)</label>
                            <input id="run" name="run" type="text"
                                value="{{ $estudiante->run ?? '' }}">
                        </div>

                        <div class="field">
                            <label for="estado">Estado carrera</label>
                            <select id="estado" name="estado">
                                <option value="Egresado/a"
                                    {{ ($estudiante->estado_carrera ?? '') == 'Egresado/a' ? 'selected' : '' }}>
                                    Egresado/a
                                </option>
                                <option value="Estudiante"
                                    {{ ($estudiante->estado_carrera ?? '') == 'Estudiante' ? 'selected' : '' }}>
                                    Estudiante
                                </option>
                                <option value="Titulado(a)"
                                    {{ ($estudiante->estado_carrera ?? '') == 'Titulado(a)' ? 'selected' : '' }}>
                                    Titulado/a
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label for="titulo">Carrera / Título</label>
                        <input id="titulo" name="titulo" type="text"
                            value="{{ $estudiante->carrera ?? '' }}">
                    </div>
                </div>
            </div>
        </section>


        {{-- CONTACTO --}}
        <section class="card">
            <h2>Contacto</h2>
            <div class="grid-3">
                <div class="field">
                    <label for="email">Correo</label>
                    <input id="email" name="email" type="email"
                        value="{{ $estudiante->usuario->email ?? '' }}">
                </div>
                <div class="field">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" type="text"
                        value="{{ $estudiante->telefono ?? '' }}">
                </div>
                <div class="field">
                    <label for="ciudad">Ciudad</label>
                    <input id="ciudad" name="ciudad" type="text"
                        value="{{ $estudiante->ciudad ?? '' }}">
                </div>
            </div>

            <div class="field">
                <label for="resumen">Resumen</label>
                <textarea id="resumen" name="resumen" rows="3" maxlength="2000">{{ $estudiante->resumen ?? '' }}</textarea>

                <span class="hint">
                    Máximo: 2000 caracteres.
                    <span id="contador-resumen">0 / 2000</span>
                </span>
            </div>
        </section>


        {{-- FORMACIÓN --}}
        <section class="card">
            <h2>Formación</h2>
            <div class="grid-2">
                <div class="field">
                    <label for="institucion">Institución</label>
                    <input id="institucion" name="institucion" type="text"
                        value="{{ $estudiante->institucion ?? '' }}">
                </div>
                <div class="field">
                    <label for="anio_egreso">Año de egreso</label>
                    <input id="anio_egreso" name="anio_egreso" type="number"
                        value="{{ $estudiante->anio_egreso ?? '' }}">
                </div>
            </div>
        </section>


        {{-- CV --}}
        <section class="card">
            <h2>CV</h2>
            <div class="field">
                <label for="cv">Subir CV (PDF)</label>
                <input id="cv" name="cv" type="file" accept="application/pdf">

                @if ($cvUrl)
                    <p class="hint">
                        CV actual:
                        <a href="{{ $cvUrl }}" target="_blank">Ver PDF</a>
                    </p>
                @endif
            </div>
        </section>


        {{-- ACCIONES --}}
        <div class="form-actions">
            <a href="{{ route('usuarios.perfil') }}" class="btn btn-ghost">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>

    </form>
</main>
@endsection
