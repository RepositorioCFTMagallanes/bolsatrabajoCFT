@extends('layouts.app')

@section('content')
<main class="container perfil-user">



    {{-- =========================================================
        HEADER DEL PERFIL: AVATAR + INFORMACIÓN PRINCIPAL
        Incluye soporte para avatar guardado o avatar por defecto
    ========================================================== --}}
    <section class="user-top grid-2">

        {{-- Tarjeta izquierda: identidad del postulante --}}
        <article class="card user-card">
            <div class="user-head">

                {{-- === Avatar dinámico === --}}
                <img class="user-avatar"
                    src="{{ $estudiante->avatar
        ? \Illuminate\Support\Facades\Storage::disk('gcs')->url($estudiante->avatar)
        : asset('img/default-avatar.png') }}"
                    alt="Avatar estudiante">


                <div class="user-meta">
                    <h2 class="user-name">
                        {{ $estudiante->usuario->nombre }}
                        {{ $estudiante->usuario->apellido }}
                    </h2>

                    <p class="user-status">
                        📘 Estado Carrera:
                        <strong>{{ $estudiante->estado_carrera ?? 'No informado' }}</strong><br>

                        🎓 {{ $estudiante->carrera ?? 'Carrera no registrada' }}
                    </p>
                </div>
            </div>

            {{-- Resumen / Extracto Profesional --}}
            <p class="user-intro">
                {{ $estudiante->resumen
                        ? $estudiante->resumen
                        : 'Aquí puedes gestionar tus postulaciones y revisar las ofertas disponibles para ti.' }}
            </p>

            {{-- Acciones del usuario --}}
            <div class="user-actions">
                <a href="{{ url('/usuarios/editar') }}" class="btn btn-primary">Editar Perfil</a>

                @if ($estudiante->ruta_cv)
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('gcs')->url($estudiante->ruta_cv) }}"
                    target="_blank"
                    class="btn btn-outline">
                    Ver CV
                </a>
                @else
                <span class="btn btn-outline disabled">Sin CV</span>
                @endif

            </div>
        </article>


        {{-- =========================================================
            Tarjeta derecha: Actividad reciente del usuario
        ========================================================== --}}
        <article class="card activity-card">
            <header class="card-header">
                <h3>Actividad Reciente</h3>
            </header>

            <ul class="activity-list">
                <li>✉️ <strong>{{ $totalPostulaciones }}</strong> Postulaciones enviadas</li>

                <li>👤 <strong>{{ $postulacionesEnAvance }}</strong> Avance en tus postulaciones</li>

                <li>💡 <strong>{{ $totalOfertasRecomendadas }}</strong> Ofertas nuevas recomendadas</li>
            </ul>

            <div class="activity-cta">
                <a href="{{ route('postulaciones.index') }}" class="btn btn-primary">
                    Mis Postulaciones
                </a>
            </div>
        </article>

    </section>



    {{-- =========================================================
         LISTADO DE POSTULACIONES DEL USUARIO
         ========================================================== --}}
    <section class="user-section">
        <h3 class="section-title">Mis Postulaciones</h3>

        @if ($postulaciones->isEmpty())
        <p style="text-align:center; color:#6b7280; margin-bottom:1rem;">
            Aún no has postulado a ninguna oferta. Explora las vacantes disponibles y envía tu primera postulación.
        </p>
        @else
        <div class="cards-grid-3 slider-mobile">
            @foreach ($postulaciones as $postulacion)
            @php
            $oferta = $postulacion->oferta;
            $empresa = $oferta?->empresa;
            $titulo = $oferta?->titulo ?? 'Oferta sin título';
            $empresaNombre = $empresa?->nombre_comercial ?? 'Empresa no registrada';

            $fecha = $postulacion->fecha_postulacion
            ? date('d-m-Y', strtotime($postulacion->fecha_postulacion))
            : null;
            @endphp

            <article class="card job-card">
                <header class="job-head">
                    <img src="{{ $empresa && $empresa->ruta_logo
        ? \Illuminate\Support\Facades\Storage::disk('gcs')->url($empresa->ruta_logo)
        : asset('img/empresas/empresa (3).png') }}"
                        class="job-icon"
                        alt="Logo {{ $empresaNombre }}">


                    <h4 class="job-title">{{ $titulo }}</h4>
                </header>

                <p class="job-company">{{ $empresaNombre }}</p>

                <div class="job-meta">
                    <div class="job-meta-item">⏳ Postulado</div>

                    @if ($fecha)
                    <div class="job-meta-item">📅 {{ $fecha }}</div>
                    @endif
                </div>

                <a href="{{ route('postulaciones.index') }}" class="job-link">
                    Ver detalle
                </a>
            </article>
            @endforeach
        </div>
        @endif
    </section>



    {{-- =========================================================
         OFERTAS RECOMENDADAS (dinámico)
         ========================================================== --}}
    <section class="user-section">
        <h3 class="section-title alt">Ofertas Recomendadas</h3>

        @if ($ofertasRecomendadas->isEmpty())
        <p style="text-align:center; color:#6b7280; margin-bottom:1rem;">
            Aún no tenemos recomendaciones suficientes para tu perfil.
        </p>
        @else
        <div class="cards-grid-3 slider-mobile">
            @foreach ($ofertasRecomendadas as $oferta)
            @php
            $empresa = $oferta->empresa;
            @endphp

            <article class="card job-card">
                <header class="job-head">
                    <img src="{{ $empresa && $empresa->ruta_logo
        ? \Illuminate\Support\Facades\Storage::disk('gcs')->url($empresa->ruta_logo)
        : asset('img/empresas/empresa (4).png') }}"
                        class="job-icon"
                        alt="Logo {{ $empresa?->nombre_comercial ?? 'Empresa' }}">


                    <h4 class="job-title">{{ $oferta->titulo }}</h4>
                </header>

                <p class="job-company">
                    {{ $empresa?->nombre_comercial ?? 'Empresa no registrada' }}
                </p>

                <div class="job-meta">
                    @if ($oferta->ciudad)
                    <div class="job-meta-item">📍 {{ $oferta->ciudad }}</div>
                    @endif

                    @if ($oferta->creado_en)
                    <div class="job-meta-item">📅 {{ date('d-m-Y', strtotime($oferta->creado_en)) }}</div>
                    @endif
                </div>

                <a href="{{ url('/ofertas/' . $oferta->id) }}" class="job-link">
                    Ver Detalles
                </a>
            </article>
            @endforeach
        </div>
        <div class="recommended-footer">
            <a href="{{ route('empleos.index') }}" class="btn-view-all">
                Ver todas las ofertas →
            </a>
        </div>
        @endif
    </section>


</main>



{{-- =========================================================
    ESTILOS DEL PERFIL DE USUARIO
    Mantienen coherencia UI con todo el sistema de CFT
========================================================= --}}
@push('styles')
<style>
    /* === Layout general === */
    .perfil-user {
        padding: 1.25rem 0 5rem;
        position: relative;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .cards-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
    }

    /* === Avatar === */
    .user-head {
        display: flex;
        align-items: center;
        gap: .9rem;
        margin-bottom: .75rem;
    }

    .user-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* === Info de usuario === */
    .user-name {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .user-status {
        color: #555;
        font-size: .92rem;
        line-height: 1.35;
    }

    .user-intro {
        color: #555;
        margin: .5rem 0 1rem;
    }

    .user-actions {
        display: flex;
        gap: .6rem;
        flex-wrap: wrap;
    }

    /* === Actividad Reciente === */
    .activity-list {
        list-style: none;
        padding: 0;
        margin: .5rem 0 1rem;
    }

    .activity-list li {
        margin: .45rem 0;
        font-size: .95rem;
    }

    /* === Postulaciones === */
    .job-head {
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .job-icon {
        width: 36px;
        height: 36px;
        object-fit: contain;
    }

    .job-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
    }

    .job-company {
        color: #6b7280;
        margin: .35rem 0 .6rem;
    }

    .job-meta {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        font-size: .9rem;
        margin-bottom: .5rem;
    }

    .job-meta-item {
        background: #f9fafb;
        border: 1px solid #eef2f7;
        padding: .3rem .55rem;
        border-radius: 8px;
    }

    /* === Cerrar sesión === */
    .logout-box {
        position: absolute;
        top: 30px;
        right: 30px;
        z-index: 10;
    }

    .logout-btn {
        background: #e02424;
        color: white;
        border: none;
        padding: .45rem .9rem;
        border-radius: 8px;
        font-size: .9rem;
        cursor: pointer;
        transition: .2s;
    }

    .logout-btn:hover {
        background: #c81e1e;
    }

    /* === Secciones === */
    .section-title {
        text-align: center;
        font-size: 1.1rem;
        font-weight: 800;
        color: #c91e25;
        margin: .5rem 0 1rem;
    }

    .recommended-footer {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .btn-view-all {
        background: #c91e25;
        color: white;
        padding: .65rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .btn-view-all:hover {
        background: #a71820;
    }

    /* === Responsive === */
    @media(max-width:820px) {

        .grid-2 {
            grid-template-columns: 1fr;
        }

        /* === Slider horizontal para mobile === */
        .slider-mobile {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 1rem;
            padding-bottom: .5rem;
            scroll-snap-type: x mandatory;
        }

        .slider-mobile::-webkit-scrollbar {
            height: 6px;
        }

        .slider-mobile::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .slider-mobile .card {
            min-width: 85%;
            scroll-snap-align: start;
        }

    }
</style>
@endpush

@endsection