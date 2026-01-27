@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px; padding: 40px 0;">

        {{-- Título --}}
        <h1 style="font-size: 36px; font-weight: 700; margin-bottom: 15px;">
            {{ $recurso->titulo }}
        </h1>

        {{-- Resumen --}}
        @if ($recurso->resumen)
            <p style="font-size: 18px; color:#555; margin-bottom: 25px;">
                {{ $recurso->resumen }}
            </p>
        @endif

        {{-- Imagen principal --}}
        @if ($recurso->imagen)
            <img src="{{ asset($recurso->imagen) }}" alt="{{ $recurso->titulo }}"
                style="width:100%; height:350px; object-fit:cover; border-radius:12px; margin-bottom:30px;">
        @endif

        {{-- Contenido en HTML (viene desde el admin con editor) --}}
        <div style="font-size:18px; line-height:1.7; color:#333;">
            {!! $recurso->contenido !!}
        </div>

        {{-- Botón volver --}}
        <div style="margin-top:40px;">
            <a href="{{ route('recursos.index') }}"
                style="display:inline-block; padding:12px 22px; 
                  background:#c62828; color:#fff; border-radius:8px; 
                  text-decoration:none; font-weight:600;">
                ← Volver a Recursos
            </a>
        </div>

    </div>
@endsection
