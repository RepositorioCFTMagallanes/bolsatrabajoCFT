@extends('layouts.admin')

@section('admin-content')
<div class="admin-container">

    <!-- ENCABEZADO -->
    <div class="panel-header">
        <h2 class="title">Administradores Registrados</h2>

        <div class="actions-top">
            <form method="GET" action="{{ route('admin.administradores.index') }}" style="display:flex; gap:10px;">
                <input type="text" name="buscar" placeholder="🔍 Buscar administrador..."
                    value="{{ request('buscar') }}" class="input-search">
            </form>

            @if (request('buscar'))
                <a href="{{ route('admin.administradores.index') }}" class="btn-reset-filters">
                    ✖ Quitar filtros
                </a>
            @endif

            <a href="{{ route('admin.administradores.create') }}" class="btn-create">
                + Nuevo Administrador
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
                    <th>RUT</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($administradores as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->nombre }} {{ $a->apellido }}</td>
                        <td>{{ $a->email }}</td>
                        <td>{{ $a->rut }}</td>
                        <td>
                            <span class="badge {{ $a->deleted_at ? 'badge-red' : 'badge-green' }}">
                                {{ $a->deleted_at ? 'Eliminado' : 'Activo' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">No hay administradores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <div class="pagination-wrapper">
        {{ $administradores->links() }}
    </div>

</div>
@endsection
