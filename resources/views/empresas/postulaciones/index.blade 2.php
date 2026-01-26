@extends('layouts.app')

@push('styles')
<style>
/* =========================================================
   CONTENEDOR GENERAL
========================================================= */
.container-postulaciones {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px 60px;
}

/* =========================================================
   ENCABEZADO
========================================================= */
.header-section {
    margin-bottom: 25px;
}

.titulo-section {
    font-size: 34px;
    font-weight: 800;
    margin-bottom: 5px;
}

.subtitulo-section {
    color: #666;
    font-size: 16px;
}

/* =========================================================
   TOTAL
========================================================= */
.total-box {
    background: #f8f8f8;
    padding: 14px 22px;
    border-radius: 12px;
    font-size: 16px;
    margin-bottom: 30px;
    border: 1px solid #e5e5e5;
    width: fit-content;
}

/* =========================================================
   GRID
========================================================= */
.postulaciones-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 22px;
}

/* =========================================================
   CARD POSTULANTE
========================================================= */
.post-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px;
    border: 1px solid #ececec;
    box-shadow: 0 4px 10px rgba(0,0,0,.06);
    display: flex;
    gap: 18px;
    transition: .25s ease;
}

.post-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,.12);
}

/* =========================================================
   FOTO
========================================================= */
.post-img {
    width: 96px;
    height: 96px;
    flex-shrink: 0;
    border-radius: 14px;
    overflow: hidden;
    background: #f1f1f1;
}

.post-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* =========================================================
   INFO
========================================================= */
.post-info {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.post-info h3 {
    font-size: 20px;
    font-weight: 800;
    margin: 0;
    line-height: 1.2;
}

.puesto {
    color: #333;
    font-size: 15px;
    margin: 6px 0 10px;
}

.fecha {
    color: #888;
    font-size: 14px;
    margin-bottom: 14px;
}

/* =========================================================
   BOTÓN
========================================================= */
.btn-detail {
    margin-top: auto;
    display: block;
    background: #c91e25;
    color: #fff;
    text-align: center;
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    min-height: 44px;
    line-height: 24px;
    white-space: nowrap;
    transition: .2s;
}

.btn-detail:hover {
    background: #a5161c;
}

/* =========================================================
   NO DATA
========================================================= */
.no-data {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px 0;
    color: #777;
    font-size: 18px;
}
</style>
@endpush

@section('content')
<div class="container-postulaciones">

    {{-- ENCABEZADO --}}
    <div class="header-section">
        <h1 class="titulo-section">Postulaciones Recibidas</h1>
        <p class="subtitulo-section">
            Revisa todos los candidatos interesados en tus ofertas laborales.
        </p>
    </div>

    {{-- TOTAL --}}
    <div class="total-box">
        Total: <strong>{{ $postulaciones->count() }}</strong>
    </div>

    {{-- GRID --}}
    <div class="postulaciones-grid">

        @forelse ($postulaciones as $post)
            <article class="post-card">

                {{-- FOTO --}}
                <div class="post-img">
                    <img
                        src="{{ $post->estudiante->avatar
                            ? asset($post->estudiante->avatar)
                            : asset('img/otros/no-user.png') }}"
                        alt="Foto postulante">
                </div>

                {{-- INFO --}}
                <div class="post-info">

                    <h3>
                        {{ $post->estudiante->usuario->nombre }}
                        {{ $post->estudiante->usuario->apellido }}
                    </h3>

                    <p class="puesto">
                        {{ $post->oferta->titulo }}
                    </p>

                    <p class="fecha">
                        📅 Postulado el
                        {{ \Carbon\Carbon::parse($post->fecha_postulacion)->format('d M Y') }}
                    </p>

                    <a href="{{ route('empresas.postulante', $post->estudiante->id) }}"
                       class="btn-detail">
                        Ver perfil completo
                    </a>

                </div>

            </article>
        @empty
            <p class="no-data">
                Aún no has recibido postulaciones.
            </p>
        @endforelse

    </div>
</div>
@endsection
