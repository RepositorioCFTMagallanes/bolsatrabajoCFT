@extends('layouts.app')

@section('content')
@if (session()->has('empresa_alerta'))
<div id="empresaAlertaModal" class="modal-overlay visible">
    <div class="modal-box">
        <h3>Notificación</h3>

        <p style="margin-top:12px; font-size: 1rem; line-height:1.5;">
            {{ session('empresa_alerta') }}
        </p>

        <div class="modal-actions" style="margin-top:18px;">
            <button class="btn-confirm">Entendido</button>
        </div>
    </div>
</div>
@endif

<main class="empresa-dashboard container">

    {{-- === Header Empresa === --}}
    <section class="empresa-header container">
        <div class="empresa-grid">

            {{-- Bloque Izquierdo --}}
            <div class="empresa-info">

                @if ($empresa->ruta_logo)
                <img src="{{ $empresa->ruta_logo }}" class="empresa-logo" />
                @else
                <img src="{{ asset('img/otros/default-logo.png') }}" class="empresa-logo" />
                @endif


                <p class="empresa-descripcion">
                    Hola, {{ $empresa->nombre_comercial }},
                    aquí puedes gestionar tus ofertas laborales
                    y revisar los candidatos interesados en tus vacantes.
                </p>

                <a href="{{ route('empresas.editar') }}" class="btn btn-danger">Editar Perfil</a>
            </div>

            {{-- Bloque Derecho --}}
            <div class="empresa-detalle">
                <ul class="stats-list">
                    <li>📦 <strong>Ofertas publicadas:</strong> {{ $totalOfertas }}</li>
                    <li>👥 <strong>Postulaciones recibidas:</strong> {{ $totalPostulaciones }}</li>
                </ul>

                <img src="{{ asset('img/otros/Fuentes-de-tráfico.jpg') }}" alt="Gráfico actividad" class="chart-img" />

                <a href="{{ route('empresas.crear') }}" class="btn btn-publicar">Publicar Nueva Oferta</a>

                <a href="{{ route('empresas.ofertas.index') }}" class="btn btn-outline-secondary"
                    style="margin-top:10px; display:block; text-align:center;">
                    Ver todas mis ofertas publicadas
                </a>

            </div>

        </div>
    </section>



    {{-- === Sección Mis Ofertas Activas === --}}
    <section class="empresa-ofertas">
        <h3>Mis Ofertas Activas</h3>

        <div class="ofertas-grid">

            @foreach ($ofertas as $oferta)
            <article class="oferta-card">

                <div class="oferta-icon">
                    <img src="{{ asset('img/iconos/4310877.png') }}" alt="Icono oferta de trabajo">
                </div>

                <div class="oferta-body">
                    <h4>{{ $oferta->titulo }}</h4>

                    <p>{{ Str::limit($oferta->descripcion, 80) }}</p>

                    <p class="lugar">📍 {{ $oferta->ciudad }}</p>

                    <a href="{{ route('ofertas.detalle', $oferta->id) }}" class="link">Ver Detalles</a>
                </div>

            </article>
            @endforeach

            {{-- Sin ofertas --}}
            @if ($ofertas->count() == 0)
            <p class="no-ofertas">Aún no tienes ofertas publicadas.</p>
            @endif

        </div>

        {{-- Botón Ver Todas --}}
        @if ($totalOfertas > 4)
        <div style="margin-top: 20px;">
            <a href="{{ route('empresas.ofertas.index') }}" class="btn btn-primary">
                Ver todas las ofertas
            </a>
        </div>
        @endif
    </section>



    {{-- === Sección Publicar Nueva Oferta === --}}
    <section class="empresa-publicar">
        <h3>Publicar Nueva Oferta</h3>

        <div class="publicar-contenedor">
            <p>¿Tienes una nueva vacante disponible? Publica tu oferta y llega a cientos de estudiantes y egresados.</p>

            <a href="{{ route('empresas.crear') }}" class="btn-publicar">+ Crear Nueva Oferta</a>
        </div>
    </section>



    {{-- === Postulaciones Recientes === --}}
    <section class="empresa-postulaciones">
        <h3>Postulaciones</h3>

        <div class="postulaciones-grid">

            @foreach ($postulaciones as $post)
            <article class="postulante-card">
                <img src="{{ $post->estudiante->avatar_url 
    ? $post->estudiante->avatar_url 
    : asset('img/otros/no-user.png') }}"
                    alt="Foto postulante">


                <div class="postulante-info">
                    <h4>{{ $post->estudiante->usuario->nombre }} {{ $post->estudiante->usuario->apellido }}</h4>

                    <p>{{ $post->oferta->titulo }}</p>

                    <p>📅 {{ \Carbon\Carbon::parse($post->fecha_postulacion)->format('d-m-Y') }}</p>

                    <a href="{{ route('empresas.postulante', $post->estudiante_id) }}" class="btn-perfil">
                        Ver perfil completo
                    </a>
                </div>
            </article>
            @endforeach

            @if ($postulaciones->count() == 0)
            <p class="no-ofertas">Aún no tienes postulaciones.</p>
            @endif

        </div>

        {{-- Ver todos --}}
        <a href="{{ route('empresas.postulaciones.index') }}" class="ver-todos">Ver todos</a>

    </section>

</main>
@push('styles')
<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, .45);
        display: none;
        /* oculto por defecto */
        justify-content: center;
        align-items: center;
        z-index: 99999;
    }

    .modal-overlay.visible {
        display: flex;
        /* solo aparece si trae la clase visible */
    }

    .modal-box {
        background: white;
        width: 90%;
        max-width: 420px;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .15);
        text-align: center;
    }

    .modal-actions {
        margin-top: 18px;
        display: flex;
        justify-content: center;
    }

    .btn-confirm {
        background: #C91E25;
        color: white;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        border: none;
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById('empresaAlertaModal');
        if (!modal) return;

        modal.querySelector('.btn-confirm')?.addEventListener('click', () => {
            modal.classList.remove('visible');
        });
    });
</script>
@endpush
@endsection