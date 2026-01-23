@extends('layouts.app')

@section('content')
    <main class="container postulante-perfil">

        {{-- ================================
         TÍTULO PRINCIPAL
    ================================= --}}
        <h1 class="page-title">Perfil del Postulante</h1>

        {{-- ================================
         TARJETA DE INFORMACIÓN PRINCIPAL
    ================================= --}}
        <section class="card profile-card">

            <div class="profile-header">
                {{-- Avatar del estudiante --}}
                <img class="avatar"
                    src="{{ $estudiante->avatar ? asset($estudiante->avatar) : asset('img/default-avatar.png') }}"
                    alt="Foto estudiante">

                <div class="profile-info">
                    <h2 class="name">
                        {{ $estudiante->usuario->nombre }} {{ $estudiante->usuario->apellido }}
                    </h2>

                    <p class="meta">
                        <strong>Correo:</strong> {{ $estudiante->usuario->email }} <br>
                        <strong>Teléfono:</strong> {{ $estudiante->telefono ?? 'No registrado' }} <br>
                        <strong>Ciudad:</strong> {{ $estudiante->ciudad ?? 'No registrado' }} <br>
                    </p>

                    @if ($estudiante->ruta_cv)
                        <a href="{{ asset($estudiante->ruta_cv) }}" class="btn btn-primary" target="_blank">
                            Ver / Descargar CV
                        </a>
                    @else
                        <span class="btn btn-outline disabled">CV no disponible</span>
                    @endif
                </div>
            </div>

            {{-- Resumen profesional --}}
            <p class="profile-summary">
                {{ $estudiante->resumen ?: 'El postulante no ha ingresado un resumen profesional.' }}
            </p>
        </section>



        {{-- ================================
        DETALLES ACADÉMICOS Y PROFESIONALES
    ================================= --}}
        <section class="card details-card">
            <h3 class="section-title">Información Académica y Profesional</h3>

            <ul class="details-list">
                <li>
                    <strong>Estado de carrera:</strong>
                    {{ $estudiante->estado_carrera ?? 'No registrado' }}
                </li>

                <li>
                    <strong>Carrera / Título:</strong>
                    {{ $estudiante->carrera ?? 'No registrado' }}
                </li>

                <li>
                    <strong>Institución:</strong>
                    {{ $estudiante->institucion ?? 'No registrado' }}
                </li>

                <li>
                    <strong>Año de egreso:</strong>
                    {{ $estudiante->anio_egreso ?? 'No registrado' }}
                </li>

                <li>
                    <strong>Cursos / Capacitación:</strong><br>
                    {!! nl2br(e($estudiante->cursos ?? 'Sin cursos registrados.')) !!}
                </li>

                <li>
                    <strong>LinkedIn:</strong>
                    @if ($estudiante->linkedin_url)
                        <a href="{{ $estudiante->linkedin_url }}" target="_blank">{{ $estudiante->linkedin_url }}</a>
                    @else
                        No registrado
                    @endif
                </li>

                <li>
                    <strong>Portafolio:</strong>
                    @if ($estudiante->portfolio_url)
                        <a href="{{ $estudiante->portfolio_url }}" target="_blank">{{ $estudiante->portfolio_url }}</a>
                    @else
                        No registrado
                    @endif
                </li>
            </ul>
        </section>



        {{-- ================================
        HISTORIAL DE POSTULACIONES
    ================================= --}}
        <section class="card postulaciones-card">
            <h3 class="section-title">Postulaciones del Estudiante</h3>

            @if ($postulaciones->isEmpty())
                <p class="empty">El postulante no tiene postulaciones registradas.</p>
            @else
                <div class="postulaciones-list">
                    @foreach ($postulaciones as $post)
                        <article class="post-item">
                            <h4 class="post-title">{{ $post->oferta->titulo }}</h4>
                            <p class="post-date">
                                Postulado el:
                                {{ \Carbon\Carbon::parse($post->fecha_postulacion)->format('d-m-Y') }}
                            </p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

    </main>
@endsection


@push('styles')
    <style>
        .postulante-perfil {
            padding: 1.5rem 0 2rem;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: #111;
        }

        /* ------- Tarjeta principal ------- */
        .profile-card {
            background: #fff;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
            margin-bottom: 1.5rem;
        }

        .profile-header {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .avatar {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            background: #f3f4f6;
        }

        .name {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: .25rem;
        }

        .meta {
            color: #444;
            margin-bottom: .75rem;
            line-height: 1.4;
        }

        .profile-summary {
            margin-top: 1rem;
            color: #333;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        /* ------- Detalles ------- */
        .details-card {
            background: #fff;
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .section-title {
            margin: 0 0 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #c91e25;
        }

        .details-list {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }

        .details-list li {
            margin-bottom: .8rem;
        }

        /* ------- Postulaciones ------- */
        .postulaciones-card {
            background: #fff;
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .postulaciones-list {
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .post-item {
            background: #f9fafb;
            padding: .85rem 1rem;
            border-radius: 10px;
            border: 1px solid #eef2f7;
        }

        .post-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #111;
        }

        .post-date {
            margin: .2rem 0 0;
            color: #666;
            font-size: .88rem;
        }

        .empty {
            color: #666;
            padding: .75rem 0;
            text-align: center;
        }


        /* Responsive */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .meta {
                text-align: center;
            }
        }
    </style>
@endpush
