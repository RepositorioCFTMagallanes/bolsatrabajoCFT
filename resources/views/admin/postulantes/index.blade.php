@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        <!-- ENCABEZADO -->
        <div class="panel-header">
            <h2 class="title">Postulantes Registrados</h2>

            <div class="actions-top">
                <form method="GET" action="{{ route('admin.postulantes.index') }}" style="display:flex; gap:10px;">
                    <input type="text" name="search" placeholder="🔍 Buscar postulante..." value="{{ $search }}"
                        class="input-search">
                </form>

                @if ($search)
                    <a href="{{ route('admin.postulantes.index') }}" class="btn-reset-filters">
                        ✖ Quitar filtros
                    </a>
                @endif
            </div>
        </div>

        <!-- TABLA -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Postulante</th>
                        <th>Email</th>
                        <th>Postulaciones</th>
                        <th>Registrado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($postulantes as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->nombre }} {{ $p->apellido }}</td>
                            <td>{{ $p->email }}</td>
                            <td><span class="badge badge-info">{{ $p->postulaciones_count ?? 0 }}</span></td>
                            <td>{{ optional($p->creado_en)->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge {{ $p->deleted_at ? 'badge-red' : 'badge-green' }}">
                                    {{ $p->deleted_at ? 'Inactivo' : 'Activo' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.postulantes.show', $p->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Ver detalle
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">No hay postulantes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $postulantes->links() }}
        </div>

    </div>
@endsection
