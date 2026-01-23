@extends('layouts.app')

@section('content')
<main class="admin-form container">

    <h1>Editar Estudiante</h1>

    @if ($errors->any())
        <div style="color:red; margin-bottom:10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.estudiantes.update', $estudiante->id) }}">
        @csrf
        @method('PUT')

        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $estudiante->nombre) }}" required>

        <label>Apellido</label>
        <input type="text" name="apellido" value="{{ old('apellido', $estudiante->apellido) }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $estudiante->email) }}" required>

        <label>RUT</label>
        <input type="text" name="rut" value="{{ old('rut', $estudiante->rut) }}" required>

        <button type="submit">Actualizar</button>
    </form>

</main>
@endsection
