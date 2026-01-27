@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        {{-- HEADER --}}
        <div class="panel-header">
            <h2 class="title">Recursos de Empleabilidad</h2>

            <a href="{{ route('admin.recursos.create') }}" class="btn btn-primary">
                + Crear nuevo recurso
            </a>
        </div>

        {{-- TABLA --}}
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-center" style="width: 200px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recursos as $recurso)
                        <tr>

                            {{-- TÍTULO --}}
                            <td>{{ $recurso->titulo }}</td>

                            {{-- ESTADO --}}
                            <td>
                                @if ($recurso->estado == 1)
                                    <span class="badge-green">Publicado</span>
                                @else
                                    <span class="badge-info">Borrador</span>
                                @endif
                            </td>

                            {{-- FECHA --}}
                            <td>
                                {{ $recurso->creado_en ? $recurso->creado_en->format('d/m/Y') : '-' }}
                            </td>

                            {{-- ACCIONES --}}
                            <td class="text-center">
                                <div class="row-actions">
                                    <a href="{{ route('admin.recursos.edit', $recurso->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Editar
                                    </a>

                                    <form action="{{ route('admin.recursos.destroy', $recurso->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Eliminar este recurso?')">
                                            Eliminar
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.recursos.toggle', $recurso->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf

                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            {{ $recurso->estado ? 'Ocultar' : 'Publicar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding:20px; color:#777;">
                                No hay recursos creados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
