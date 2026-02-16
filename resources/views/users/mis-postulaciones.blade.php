{{-- Vista: Postulaciones reales del estudiante --}}
@extends('layouts.app')

@section('content')
{{-- WRAPPER para asegurar separación correcta del footer --}}
<div class="apps-page-wrapper">

    <div class="container user-apps">

        {{-- HEADER --}}
        <header class="apps-header">
            <h1 class="section-title">Mis Postulaciones</h1>

            <p class="muted">
                Revisa el estado de tus postulaciones y accede a los detalles de cada oferta.
            </p>

            {{-- Filtros (aún no funcionales) --}}
            <form class="apps-filters" method="GET">
                <div class="field">
                    <label for="f_estado">Estado</label>
                    <select id="f_estado" name="estado">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_revision">En revisión</option>
                        <option value="seleccionado">Seleccionado</option>
                        <option value="descartado">Descartado</option>
                        <option value="retirada">Retirada</option>
                    </select>
                </div>

                <div class="field">
                    <label for="f_fecha">Ordenar por</label>
                    <select id="f_fecha" name="orden">
                        <option value="recientes">Más recientes</option>
                        <option value="antiguas">Más antiguas</option>
                    </select>
                </div>

                <div class="field field-search">
                    <label for="f_q">Buscar</label>
                    <input id="f_q" type="text" name="q" placeholder="Puesto, empresa, ubicación…">
                </div>

                <div class="field field-actions">
                    <button class="btn btn-primary" type="submit">Aplicar filtros</button>
                </div>
            </form>
        </header>

        {{-- ===========================
                LISTA DE POSTULACIONES
            ============================ --}}
        <section class="apps-list">

            @if ($postulaciones->isEmpty())
            <p style="text-align:center; color:#6b7280; margin-top:1rem;">
                Aún no has postulado a ninguna oferta. Explora las vacantes disponibles.
            </p>
            @endif

            @foreach ($postulaciones as $postulacion)
            @php
            $oferta = $postulacion->oferta;
            $empresa = $oferta?->empresa;

            $titulo = $oferta->titulo ?? 'Oferta sin título';
            $empresaNombre = $empresa->nombre_comercial ?? 'Empresa no registrada';
            $ciudad = $oferta->ciudad ?? 'Ubicación no registrada';

            $estado = $postulacion->estado_postulacion;
            $badgeClass = match ($estado) {
            'seleccionado' => 'seleccionada',
            'descartado', 'retirada' => 'rechazada',
            default => 'en-proceso',
            };

            $fecha = $postulacion->fecha_postulacion
            ? \Carbon\Carbon::parse($postulacion->fecha_postulacion)->format('d M Y')
            : 'Fecha no registrada';
            @endphp

            <article class="app-card">

                {{-- ENCABEZADO --}}
                <div class="app-card-head">

                    @php
                    $logoUrl = $empresa->ruta_logo ?? asset('img/empresas/empresa (1).png');
                    @endphp

                    <img class="company-logo" src="{{ $logoUrl }}" alt="Logo {{ $empresaNombre }}">


                    <div class="job-meta">
                        <h3 class="job-title">{{ $titulo }}</h3>
                        <p class="company">{{ $empresaNombre }}</p>
                        <p class="location">📍 {{ $ciudad }}</p>
                    </div>

                    <span class="badge status {{ $badgeClass }}">
                        {{ ucfirst(str_replace('_', ' ', $estado)) }}
                    </span>
                </div>

                {{-- CUERPO --}}
                <div class="app-card-body">
                    <ul class="meta-inline">
                        <li>📅 Postulado: {{ $fecha }}</li>
                        <li>🧭 Etapa: {{ ucfirst(str_replace('_', ' ', $estado)) }}</li>
                    </ul>

                    <p class="excerpt">
                        {{ Str::limit($oferta->descripcion ?? 'Sin descripción disponible.', 140) }}
                    </p>
                </div>

                {{-- ACCIONES --}}
                <div class="app-card-actions">
                    <a class="btn btn-light btn-ver-detalle" href="#" data-id="{{ $postulacion->id }}">
                        Ver detalle
                    </a>

                    @if ($estado !== 'retirada' && $estado !== 'seleccionado')
                    <form action="{{ route('postulaciones.retirar', $postulacion->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <button class="btn btn-danger" type="submit"
                            onclick="return confirm('¿Seguro que deseas retirar esta postulación?')">
                            Retirar postulación
                        </button>
                    </form>
                    @endif
                </div>

            </article>
            @endforeach

        </section>

        {{-- 👇 El modal-container SIEMPRE dentro del wrapper para evitar solapamiento con footer --}}
        <div id="modal-container"></div>

    </div> {{-- /container --}}
</div> {{-- /apps-page-wrapper --}}
@endsection



{{-- ===========================
        ESTILOS LOCALES
=========================== --}}
@push('styles')
<style>
    /* Wrapper que genera espacio REAL antes del footer */
    .apps-page-wrapper {
        padding-bottom: 6rem;
        /* evita que el footer tape contenido */
    }

    .user-apps {
        padding: 1.25rem 0 0;
    }

    .apps-header .muted {
        color: #6b7280;
        margin-bottom: .75rem;
    }

    /* --------------------------
                                FORMULARIO FILTROS
                        --------------------------- */
    .apps-filters {
        background: #ffffff;
        padding: 1.2rem;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(4, 1fr);
        align-items: end;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }

    .apps-filters .field {
        display: flex;
        flex-direction: column;
    }

    .apps-filters label {
        font-weight: 600;
        margin-bottom: .3rem;
        color: #374151;
    }

    .apps-filters select,
    .apps-filters input {
        padding: .55rem .75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: .9rem;
        background: #fafafa;
        transition: .2s;
    }

    .apps-filters select:focus,
    .apps-filters input:focus {
        border-color: #c91e25;
        background: #ffffff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(201, 30, 37, .18);
    }

    .field-actions button {
        width: 100%;
        padding: .65rem;
        border-radius: 8px;
        background: #c91e25;
        font-weight: 600;
        color: white;
        border: none;
        transition: .2s;
    }

    .field-actions button:hover {
        background: #a71820;
    }

    @media (max-width: 900px) {
        .apps-filters {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {
        .apps-filters {
            grid-template-columns: 1fr;
        }
    }

    /* --------------------------
                                TARJETAS
                        --------------------------- */
    .apps-list {
        display: grid;
        gap: 1rem;
        grid-template-columns: 1fr 1fr;
        margin-top: 1rem;
    }

    .app-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
    }

    .app-card-head {
        display: grid;
        grid-template-columns: 56px 1fr auto;
        gap: .75rem;
        align-items: center;
    }

    .company-logo {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
    }

    .job-title {
        font-size: 1.05rem;
        font-weight: 700;
    }

    .badge {
        padding: .25rem .6rem;
        border-radius: 999px;
        font-size: .78rem;
    }

    .status.en-proceso {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .status.seleccionada {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status.rechazada {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .meta-inline {
        display: flex;
        gap: 1rem;
        list-style: none;
        padding: 0;
        margin-bottom: .5rem;
        color: #4b5563;
        font-size: .92rem;
    }

    .app-card-actions {
        margin-top: .75rem;
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .btn-light {
        background: #f3f4f6;
        color: #111827;
        padding: .6rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-light:hover {
        background: #e5e7eb;
    }

    .btn-danger {
        background: #fee2e2;
        color: #991b1b;
        padding: .6rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
    }

    @media (max-width:768px) {
        .apps-list {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush



{{-- ===========================
        SCRIPTS
=========================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();

                const id = btn.dataset.id;

                const response = await fetch(`/usuarios/postulacion-detalle/${id}`);
                const data = await response.json();

                if (data.html) {
                    document.querySelector('#modal-container').innerHTML = data.html;

                    const overlay = document.querySelector('.modal-overlay');
                    overlay.classList.add('show');

                    document.querySelector('.modal-close').onclick = () => {
                        overlay.classList.remove('show');
                        setTimeout(() => overlay.remove(), 200);
                    };
                }
            });
        });

    });
</script>
@endpush