@extends('layouts.admin')

@section('admin-content')
    <main class="container postulante-perfil">

        <a href="{{ route('admin.postulantes.index') }}" class="back-link">← Volver al listado</a>

        <h1 class="page-title">Perfil del Postulante</h1>

        {{-- TARJETA PRINCIPAL --}}
        <section class="card profile-card">
            <div class="profile-header">

                {{-- AVATAR (null-safe) --}}
                <img class="avatar"
                    src="{{ optional($estudiante)->avatar ? asset($estudiante->avatar) : asset('img/default-avatar.png') }}"
                    alt="Foto postulante">

                <div class="profile-info">
                    <h2 class="name">
                        {{ $postulante->nombre }} {{ $postulante->apellido }}
                    </h2>

                    <p class="meta">
                        <strong>Correo:</strong> {{ $postulante->email }} <br>
                        <strong>Teléfono:</strong> {{ optional($estudiante)->telefono ?? 'No registrado' }} <br>
                        <strong>Ciudad:</strong> {{ optional($estudiante)->ciudad ?? 'No especificada' }} <br>
                    </p>

                    {{-- CV --}}
                    @if (optional($estudiante)->ruta_cv)
                        <a href="{{ asset($estudiante->ruta_cv) }}" class="btn btn-primary" target="_blank">
                            📄 Ver / Descargar CV
                        </a>
                    @endif
                </div>
            </div>

            {{-- RESUMEN --}}
            <p class="profile-summary">
                {{ optional($estudiante)->resumen ?: 'El postulante no ha ingresado un resumen profesional.' }}
            </p>
        </section>

        {{-- DETALLES PROFESIONALES --}}
        <section class="card details-card">
            <h3 class="section-title">Información Académica y Profesional</h3>

            <ul class="details-list">
                <li><strong>Estado de carrera:</strong> {{ optional($estudiante)->estado_carrera ?? 'No indicado' }}</li>
                <li><strong>Carrera / Título:</strong> {{ optional($estudiante)->carrera ?? 'No registrado' }}</li>
                <li><strong>Institución:</strong> {{ optional($estudiante)->institucion ?? 'No registrado' }}</li>
                <li><strong>Año de egreso:</strong> {{ optional($estudiante)->anio_egreso ?? 'No especificado' }}</li>
                <li>
                    <strong>LinkedIn:</strong>
                    @if (optional($estudiante)->linkedin_url)
                        <a href="{{ $estudiante->linkedin_url }}" target="_blank">
                            {{ $estudiante->linkedin_url }}
                        </a>
                    @else
                        No registrado
                    @endif
                </li>
            </ul>
        </section>

        {{-- HISTORIAL DE POSTULACIONES --}}
        <section class="card postulaciones-card">
            <h3 class="section-title">Postulaciones Realizadas</h3>

            @if ($postulaciones->isEmpty())
                <p class="empty">El postulante no tiene postulaciones registradas.</p>
            @else
                <div class="postulaciones-list">
                    @foreach ($postulaciones as $post)
                        @php
                            $fechaBase = $post->fecha_postulacion ?? $post->creado_en;
                        @endphp

                        <article class="post-item">
                            <h4 class="post-title">
                                {{ optional($post->oferta)->titulo ?? 'Oferta no disponible' }}
                            </h4>

                            <p class="post-date">
                                Postulado el:
                                @if ($fechaBase)
                                    {{ \Carbon\Carbon::parse($fechaBase)->format('d-m-Y') }}
                                @else
                                    Fecha no registrada
                                @endif
                            </p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-postulante.css') }}">
@endpush
