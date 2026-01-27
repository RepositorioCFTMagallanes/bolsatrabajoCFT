@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/empresas-mis-ofertas.css') }}">
@endpush

@section('content')
    <div class="container-mis-ofertas">

        {{-- Encabezado --}}
        <div class="header-section">
            <h1 class="titulo-section">Mis Ofertas</h1>
            <p class="subtitulo-section">
                Administra tus ofertas laborales y accede rápidamente a sus detalles o edición.
            </p>
        </div>

        {{-- Grid de ofertas --}}
        <div class="ofertas-grid">

            @forelse ($ofertas as $oferta)
                @php
                    $estadoTexto =
                        [
                            \App\Models\OfertaTrabajo::ESTADO_PENDIENTE => 'Pendiente revisión',
                            \App\Models\OfertaTrabajo::ESTADO_APROBADA => 'Publicada',
                            \App\Models\OfertaTrabajo::ESTADO_RECHAZADA => 'Rechazada',
                            \App\Models\OfertaTrabajo::ESTADO_REENVIADA => 'Reenviada',
                            \App\Models\OfertaTrabajo::ESTADO_FINALIZADA => 'Finalizada',
                        ][$oferta->estado] ?? 'Pendiente revisión';

                    $estadoClase = match ($estadoTexto) {
                        'Publicada' => 'badge-green',
                        'Finalizada' => 'badge-dark',
                        'Rechazada' => 'badge-red',
                        'Reenviada' => 'badge-blue',
                        default => 'badge-gray',
                    };
                @endphp

                <div class="oferta-card">

                    {{-- Logo --}}
                    <div class="oferta-logo">
                        <img src="{{ $empresa->ruta_logo ? asset($empresa->ruta_logo) : asset('img/logo-placeholder.png') }}"
                            alt="Logo empresa">
                    </div>

                    {{-- Header --}}
                    <div class="oferta-header">
                        <h3 class="oferta-titulo">{{ $oferta->titulo }}</h3>
                        <span class="badge-estado {{ $estadoClase }}">{{ $estadoTexto }}</span>
                    </div>

                    {{-- Datos --}}
                    <div class="oferta-datos">
                        <p class="dato-item"><i class="bi bi-geo-alt"></i> {{ $oferta->ciudad ?? 'Sin ubicación' }}</p>
                        <p class="dato-item"><i class="bi bi-calendar3"></i>
                            Publicada: {{ optional($oferta->creado_en)->format('d M Y') ?? '—' }}
                        </p>

                        @if ($oferta->fecha_cierre)
                            <p class="dato-item"><i class="bi bi-hourglass-split"></i>
                                Cierre: {{ \Carbon\Carbon::parse($oferta->fecha_cierre)->format('d M Y') }}
                            </p>
                        @endif

                        <p class="dato-item"><i class="bi bi-people"></i>
                            Vacantes: {{ $oferta->vacantes ?? 1 }}
                        </p>
                    </div>

                    {{-- Descripción --}}
                    <p class="oferta-descripcion">
                        {{ Str::limit($oferta->descripcion, 160) }}
                    </p>

                    {{-- Acciones --}}
                    <div class="oferta-actions">

                        @if ($oferta->estado == \App\Models\OfertaTrabajo::ESTADO_PENDIENTE)
                            <span class="info-msg">⏳ En revisión — No puedes editar</span>
                        @elseif ($oferta->estado == \App\Models\OfertaTrabajo::ESTADO_APROBADA)
                            <div class="actions-row">
                                <a href="{{ route('empresas.ofertas.editar', $oferta->id) }}" class="btn-empresa btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('empresas.ofertas.finalizar', $oferta->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de finalizar esta oferta? Esta acción no se puede deshacer.');">
                                    @csrf
                                    <button type="submit" class="btn-retirar btn-sm">
                                        Finalizar
                                    </button>
                                </form>
                            </div>
                        @elseif (in_array($oferta->estado, [
                                \App\Models\OfertaTrabajo::ESTADO_RECHAZADA,
                                \App\Models\OfertaTrabajo::ESTADO_REENVIADA,
                            ]))
                            <span
                                class="info-msg {{ $oferta->estado == \App\Models\OfertaTrabajo::ESTADO_RECHAZADA ? 'error' : '' }}">
                                {{ $oferta->estado == \App\Models\OfertaTrabajo::ESTADO_RECHAZADA
                                    ? '❌ Rechazada — revisa el motivo y corrige'
                                    : '📤 En revisión — puedes seguir editando antes de aprobación' }}
                            </span>

                            <div class="actions-row">
                                <a href="{{ route('empresas.ofertas.editar', $oferta->id) }}" class="btn-empresa btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('empresas.ofertas.enviarRevision', $oferta->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-publicar btn-sm">
                                        Enviar a revisión
                                    </button>
                                </form>

                                <form action="{{ route('empresas.ofertas.destroy', $oferta->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-retirar btn-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @elseif ($oferta->estado == \App\Models\OfertaTrabajo::ESTADO_FINALIZADA)
                            <span class="info-msg">
                                🏁 Oferta finalizada — ya no recibe postulaciones
                            </span>
                        @endif

                    </div>


                </div>

            @empty
                <p class="no-ofertas">Aún no has creado ofertas laborales.</p>
            @endforelse

        </div>
    </div>
@endsection
