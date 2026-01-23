@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- TÍTULO PRINCIPAL --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary m-0">Editar Oferta Laboral</h2>
        
        <a href="{{ route('empresas.ofertas.index') }}" class="btn btn-secondary">
            ← Volver al listado
        </a>
    </div>

    {{-- CARD DEL FORMULARIO --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">

            {{-- MENSAJES DE ÉXITO / ERROR --}}
            @if(session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- ERRORES DE VALIDACIÓN --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Corrige los siguientes errores:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO --}}
            <form 
                action="{{ route('empresas.ofertas.update', $oferta->id) }}" 
                method="POST" 
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                {{-- IMPORTAMOS EL FORMULARIO REUTILIZABLE --}}
                @include('ofertas.form', ['modo' => 'editar'])
            </form>

        </div>
    </div>

</div>
@endsection
