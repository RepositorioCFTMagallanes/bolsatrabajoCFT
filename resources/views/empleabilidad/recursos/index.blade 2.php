@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 40px 0;">

        <h2 class="section-title" style="font-size:32px; font-weight:700; margin-bottom:25px;">
            Guías, consejos y tendencias
        </h2>

        {{-- GRID DE TARJETAS --}}
        <div class="recursos-grid"
            style="display:grid; grid-template-columns:repeat(auto-fill, minmax(350px, 1fr)); gap:25px;">

            @foreach ($recursos as $recurso)
                <div class="recurso-card"
                    style="background:#fff; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,0.08); overflow:hidden;">

                    {{-- IMAGEN --}}
                    @if ($recurso->imagen)
                        <img src="{{ asset($recurso->imagen) }}" alt="Imagen del recurso"
                            style="width:100%; height:220px; object-fit:cover;">
                    @endif

                    <div style="padding:20px;">

                        {{-- TÍTULO --}}
                        <h3 style="font-size:20px; font-weight:600; margin-bottom:10px;">
                            {{ $recurso->titulo }}
                        </h3>

                        {{-- RESUMEN --}}
                        <p style="font-size:15px; color:#555; margin-bottom:20px;">
                            {{ $recurso->resumen }}
                        </p>

                        {{-- BOTÓN LEER MÁS --}}
                        <a href="{{ route('recursos.show', $recurso->id) }}"
                            style="display:inline-block; padding:10px 20px;
                              background:#c62828; color:#fff; border-radius:8px;
                              text-decoration:none; font-weight:600;">
                            LEER MÁS
                        </a>

                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
