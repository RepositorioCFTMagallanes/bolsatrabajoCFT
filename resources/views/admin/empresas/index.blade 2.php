@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        <!-- ENCABEZADO -->
        <div class="panel-header">
            <h2 class="title">Empresas Registradas</h2>

            <div class="actions-top">
                <form method="GET" action="{{ route('admin.empresas.index') }}" style="display:flex; gap:10px;">
                    <input type="text" name="search" placeholder="🔍 Buscar empresa..." value="{{ request('search') }}"
                        class="input-search">
                </form>
                @if ($search)
                    <a href="{{ route('admin.empresas.index') }}" class="btn-reset-filters">
                        ✖ Quitar filtros
                    </a>
                @endif


                <a href="{{ route('admin.empresas.create') }}" class="btn-create">
                    + Nueva Empresa
                </a>
            </div>
        </div>

        <!-- TABLA -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Comercial</th>
                        <th>Representante</th>
                        <th>Email</th>
                        <th>RUT</th>
                        <th>Teléfono</th>
                        <th>Sitio Web</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($empresas as $empresa)
                        <tr>
                            <!-- ID USUARIO -->
                            <td>{{ $empresa->id }}</td>

                            <!-- NOMBRE COMERCIAL (empresa) -->
                            <td>
                                {{ $empresa->empresa->razon_social ?? 'Sin registro' }}
                            </td>

                            <!-- REPRESENTANTE (usuario.nombre) -->
                            <td>{{ $empresa->nombre }}</td>

                            <!-- EMAIL -->
                            <td>{{ $empresa->email }}</td>

                            <!-- RUT (usuario.rut) -->
                            <td>{{ $empresa->rut }}</td>

                            <!-- TELÉFONO (empresa.telefono_contacto) -->
                            <td>{{ $empresa->empresa->telefono_contacto ?? 'No informado' }}</td>

                            <!-- SITIO WEB -->
                            <td>
                                @if ($empresa->empresa && $empresa->empresa->sitio_web)
                                    <a href="{{ $empresa->empresa->sitio_web }}" target="_blank">
                                        {{ $empresa->empresa->sitio_web }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            <!-- ESTADO -->
                            <td>
                                <span class="badge {{ $empresa->deleted_at ? 'badge-red' : 'badge-green' }}">
                                    {{ $empresa->deleted_at ? 'Eliminada' : 'Activa' }}
                                </span>
                            </td>

                            <!-- ACCIONES -->
                            <td class="row-actions">

                                <!-- Editar -->
                                <a href="{{ route('admin.empresas.edit', $empresa->id) }}" class="icon edit"
                                    title="Editar">
                                    ✏️
                                </a>

                                <!-- Eliminar / Restaurar -->
                                @if (!$empresa->deleted_at)
                                    <form action="{{ route('admin.empresas.destroy', $empresa->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon delete" title="Eliminar"
                                            onclick="return confirm('¿Eliminar empresa?')">
                                            🗑️
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.empresas.restore', $empresa->id) }}" method="POST"
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
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-3">
                                No hay empresas registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
