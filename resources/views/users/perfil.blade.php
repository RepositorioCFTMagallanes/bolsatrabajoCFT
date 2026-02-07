@extends('layouts.app')

@section('content')
<main class="container perfil-user">

@php
use Illuminate\Support\Facades\Storage;

$avatarUrl = asset('img/default-avatar.png');
$cvUrl = null;

try {
    if (!empty($estudiante->avatar)) {
        $avatarUrl = Storage::disk('gcs')->url($estudiante->avatar);
    }

    if (!empty($estudiante->ruta_cv)) {
        $cvUrl = Storage::disk('gcs')->url($estudiante->ruta_cv);
    }
} catch (\Throwable $e) {
    // fallback silencioso si falla GCS
    $avatarUrl = asset('img/default-avatar.png');
    $cvUrl = null;
}
@endphp


    {{-- =========================================================
        HEADER DEL PERFIL: AVATAR + INFORMACIÓN PRINCIPAL
    ========================================================== --}}
    <section class="user-top grid-2">

        {{-- Tarjeta izquierda: identidad del postulante --}}
        <article class="card user-card">
            <div class="user-head">

                {{-- Avatar dinámico --}}
                <img class="user-avatar"
                    src="{{ $avatarUrl }}"
                    alt="Avatar estudiante">

                <div class="user-meta">
                    <h2 class="user-name">
                        {{ $estudiante->usuario->nombre ?? 'Nombre' }}
                        {{ $estudiante->usuario->apellido ?? '' }}
                    </h2>

                    <p class="user-status">
                        📘 Estado Carrera:
                        <strong>{{ $estudiante->estado_carrera ?? 'No informado' }}</strong><br>

                        🎓 {{ $estudiante->carrera ?? 'Carrera no registrada' }}
                    </p>
                </div>
            </div>

            {{-- Resumen --}}
            <p class="user-intro">
                {{ $estudiante->resumen
                    ? $estudiante->resumen
                    : 'Aquí puedes gestionar tus postulaciones y revisar las ofertas disponibles para ti.' }}
            </p>

            {{-- Acciones --}}
            <div class="user-actions">
                <a href="{{ url('/usuarios/editar') }}" class="btn btn-primary">Editar Perfil</a>

                @if ($cvUrl)
                    <a href="{{ $cvUrl }}" target="_blank" class="btn btn-outline">
                        Ver CV
                    </a>
                @else
                    <span class="btn btn-outline disabled">Sin CV</span>
                @endif
            </div>
        </article>


        {{-- Tarjeta derecha: Actividad reciente --}}
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
         LISTADO DE POSTULACIONES
    ========================================================== --}}
    <section class="user-section">
        <h3 class="section-title">Mis Postulaciones</h3>

        @if ($postulaciones->isEmpty())
            <p style="text-align:center; color:#6b7280; margin-bottom:1rem;">
                Aún no has postulado a ninguna oferta.
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

                $logoUrl = asset('img/empresas/empresa (3).png');

                try {
                    if ($empresa && $empresa->ruta_logo) {
                        $logoUrl = Storage::disk('gcs')->url($empresa->ruta_logo);
                    }
                } catch (\Throwable $e) {
                    $logoUrl = asset('img/empresas/empresa (3).png');
                }
            @endphp

            <article class="card job-card">
                <header class="job-head">
                    <img src="{{ $logoUrl }}"
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
         OFERTAS RECOMENDADAS
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

                $logoUrl = asset('img/empresas/empresa (4).png');

                try {
                    if ($empresa && $empresa->ruta_logo) {
                        $logoUrl = Storage::disk('gcs')->url($empresa->ruta_logo);
                    }
                } catch (\Throwable $e) {
                    $logoUrl = asset('img/empresas/empresa (4).png');
                }
            @endphp

            <article class="card job-card">
                <header class="job-head">
                    <img src="{{ $logoUrl }}"
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
                        <div class="job-meta-item">
                            📅 {{ date('d-m-Y', strtotime($oferta->creado_en)) }}
                        </div>
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
    ESTILOS
========================================================= --}}
@push('styles')
<style>
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

.activity-list {
    list-style: none;
    padding: 0;
    margin: .5rem 0 1rem;
}

.activity-list li {
    margin: .45rem 0;
    font-size: .95rem;
}

@media(max-width:820px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@endsection
