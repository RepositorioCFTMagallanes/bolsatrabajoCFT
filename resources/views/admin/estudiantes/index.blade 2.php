@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        <!-- ENCABEZADO -->
        <div class="panel-header">
            <h2 class="title">Estudiantes Registrados</h2>

            <div class="actions-top">
                <form method="GET" action="{{ route('admin.estudiantes.index') }}" style="display:flex; gap:10px;">
                    <input type="text" name="search" placeholder="🔍 Buscar estudiante..." value="{{ $search }}"
                        class="input-search">
                </form>
                @if ($search)
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn-reset-filters">
                        ✖ Quitar filtros
                    </a>
                @endif

                <a href="{{ route('admin.estudiantes.create') }}" class="btn-create">
                    + Nuevo Estudiante
                </a>
            </div>
        </div>

        <!-- TABLA -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Carrera</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($estudiantes as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
                            <td>{{ $e->nombre }} {{ $e->apellido }}</td>
                            <td>{{ $e->email }}</td>
                            <td>{{ optional($e->estudiante)->carrera ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $e->deleted_at ? 'badge-red' : 'badge-green' }}">
                                    {{ $e->deleted_at ? 'Eliminado' : 'Activo' }}
                                </span>
                            </td>
                            <td class="row-actions">

                                {{-- Editar --}}
                                <a href="{{ route('admin.estudiantes.edit', $e->id) }}" class="icon edit" title="Editar">
                                    ✏️
                                </a>

                                {{-- Eliminar o Restaurar --}}
                                @if (!$e->deleted_at)
                                    <form action="{{ route('admin.estudiantes.destroy', $e->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon delete" title="Eliminar"
                                            onclick="return confirm('¿Eliminar este estudiante?')">
                                            🗑️
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.estudiantes.restore', $e->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button class="icon restore" title="Restaurar">
                                            ♻️
                                        </button>
                                    </form>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
