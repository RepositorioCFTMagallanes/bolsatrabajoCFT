@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        <!-- ENCABEZADO -->
        <div class="panel-header">
            <h2 class="title">Validación de Ofertas</h2>
        </div>

        <!-- FILTROS POR ESTADO -->
        <div class="filter-tabs-wrapper">
            @php
                $tabs = [
                    'pending' => 'Pendientes',
                    'approved' => 'Aprobadas',
                    'rejected' => 'Rechazadas',
                    'resubmitted' => 'Reenviadas',
                    'finalized' => 'Finalizadas',
                    'all' => 'Todas',
                ];
            @endphp

            <div class="filter-tabs">
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('admin.ofertas.index', ['estado' => $key]) }}"
                        class="btn-status {{ $estadoFiltro === $key ? 'active' : '' }}">

                        {{ $label }}

                        @if (in_array($key, ['pending', 'approved', 'rejected', 'resubmitted', 'finalized']))
                            <span class="count">{{ $stats[$key] ?? 0 }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>


        <!-- TABLA -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Oferta</th>
                        <th>Empresa</th>
                        <th>Ciudad</th>
                        <th>Fecha creación</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($ofertas as $oferta)
                        <tr>

                            <!-- OFERTA -->
                            <td>
                                <strong>{{ $oferta->titulo }}</strong><br>
                                <small style="color:#666;">
                                    {{ $oferta->area->nombre ?? 'Sin área' }}
                                </small>
                            </td>

                            <!-- EMPRESA -->
                            <td>{{ $oferta->empresa->razon_social ?? 'Empresa sin nombre' }}</td>

                            <!-- CIUDAD -->
                            <td>{{ $oferta->ciudad ?? '-' }}</td>

                            <!-- FECHA -->
                            <td>{{ optional($oferta->fecha_publicacion)->format('d-m-Y') }}</td>

                            <!-- ESTADO -->
                            <td>
                                @php
                                    $estado = $oferta->estado_nombre;
                                    $labelMap = [
                                        'Pendiente' => 'Pendiente',
                                        'Aprobada' => 'Aprobada',
                                        'Rechazada' => 'Rechazada',
                                        'Reenviada' => 'Reenviada',
                                        'Finalizada' => 'Finalizada',
                                    ];

                                    $classMap = [
                                        'Aprobada' => 'badge-green',
                                        'Rechazada' => 'badge-red',
                                        'Reenviada' => 'badge-info',
                                        'Pendiente' => 'badge-info',
                                        'Finalizada' => 'badge-dark',
                                    ];

                                @endphp

                                <span class="badge {{ $classMap[$estado] ?? 'badge-info' }}">
                                    {{ $labelMap[$estado] ?? 'Pendiente' }}
                                </span>
                            </td>

                            <!-- ACCIONES -->
                            <td style="text-align:right;">
                                <a href="{{ route('admin.ofertas.show', $oferta->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">
                                No hay ofertas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        @if ($ofertas->hasPages())
            <div class="pagination-admin">

                {{-- Anterior --}}
                @if ($ofertas->onFirstPage())
                    <span class="pag-btn disabled">Anterior</span>
                @else
                    <a href="{{ $ofertas->previousPageUrl() }}" class="pag-btn">Anterior</a>
                @endif

                {{-- Números --}}
                @foreach ($ofertas->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $ofertas->currentPage())
                        <span class="pag-number active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-number">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Siguiente --}}
                @if ($ofertas->hasMorePages())
                    <a href="{{ $ofertas->nextPageUrl() }}" class="pag-btn">Siguiente</a>
                @else
                    <span class="pag-btn disabled">Siguiente</span>
                @endif

            </div>
        @endif

    </div>
@endsection
