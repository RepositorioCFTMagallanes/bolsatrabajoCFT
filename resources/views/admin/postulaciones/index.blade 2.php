@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        {{-- HEADER SUPERIOR --}}
        <div class="panel-header">
            <h2 class="title">Postulaciones Registradas</h2>
        </div>

        {{-- TABLA --}}
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Postulante</th>
                        <th>Email</th>
                        <th>Oferta</th>
                        <th>Empresa</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($postulaciones as $post)
                        <tr>

                            {{-- ID --}}
                            <td>{{ $post->id }}</td>

                            {{-- POSTULANTE --}}
                            <td>
                                {{ $post->estudiante?->usuario?->nombre }}
                                {{ $post->estudiante?->usuario?->apellido }}
                            </td>

                            {{-- EMAIL --}}
                            <td>{{ $post->estudiante?->usuario?->email }}</td>

                            {{-- OFERTA --}}
                            <td>{{ $post->oferta?->titulo ?? 'Oferta eliminada' }}</td>

                            {{-- EMPRESA --}}
                            <td>
                                {{ optional($post->oferta?->empresa)->razon_social 
                                    ?? $post->oferta?->nombre_contacto 
                                    ?? 'No disponible' }}
                            </td>

                            {{-- FECHA --}}
                            <td>
                                @php
                                    $fecha = $post->fecha_postulacion ?? $post->creado_en;
                                @endphp
                                {{ $fecha ? \Carbon\Carbon::parse($fecha)->format('d-m-Y') : 'Sin registro' }}
                            </td>

                            {{-- ESTADO --}}
                            <td>
                                @php $estado = strtolower($post->estado_postulacion); @endphp

                                @if ($estado === 'pendiente')
                                    <span class="badge-info">Pendiente</span>

                                @elseif($estado === 'aceptado')
                                    <span class="badge-green">Aceptado</span>

                                @elseif($estado === 'rechazado')
                                    <span class="badge-red">Rechazado</span>

                                @else
                                    <span class="badge-info">{{ ucfirst($post->estado_postulacion) }}</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding:20px; color:#777;">
                                No existen postulaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN ESTANDARIZADA --}}
        @if ($postulaciones->hasPages())
            <div class="pagination-admin">

                {{-- Botón Anterior --}}
                @if ($postulaciones->onFirstPage())
                    <span class="pag-btn disabled">Anterior</span>
                @else
                    <a href="{{ $postulaciones->previousPageUrl() }}" class="pag-btn">Anterior</a>
                @endif

                {{-- Números --}}
                @foreach ($postulaciones->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $postulaciones->currentPage())
                        <span class="pag-number active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-number">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Botón Siguiente --}}
                @if ($postulaciones->hasMorePages())
                    <a href="{{ $postulaciones->nextPageUrl() }}" class="pag-btn">Siguiente</a>
                @else
                    <span class="pag-btn disabled">Siguiente</span>
                @endif

            </div>
        @endif

    </div>
@endsection
