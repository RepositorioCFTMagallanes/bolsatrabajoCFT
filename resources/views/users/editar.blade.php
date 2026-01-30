@extends('layouts.app')

@section('content')
    <main class="container user-edit">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ul>
                <li><a href="{{ route('usuarios.perfil') }}">Perfil postulante</a></li>
                <li class="current" aria-current="page">Editar perfil</li>
            </ul>
        </nav>

        {{-- Encabezado de página --}}
        <header class="page-header">
            <h1>Editar Perfil</h1>
            <p class="muted">Actualiza tu información para mejorar tus postulaciones.</p>
        </header>

        <form action="{{ url('/usuarios/editar') }}" method="post" enctype="multipart/form-data">
            @csrf

            {{-- === Avatar + Nombre / Titulación === --}}
            <section class="card">
                <h2>Identidad</h2>
                <div class="grid-2">
                    <div class="field">
                        <label for="avatar">Foto de perfil</label>
                        <div class="avatar-row">
                            @php
                                $avatar =
                                    $estudiante->avatar && file_exists(public_path($estudiante->avatar))
                                        ? asset($estudiante->avatar)
                                        : asset('img/testimonios/test (2).png');
                            @endphp

                            <img class="avatar-preview" src="{{ $avatar }}" alt="Avatar actual">

                            <div class="avatar-actions">
                                <input type="file" id="avatar" name="avatar" accept="image/*">
                                <p class="hint">Formatos: JPG o PNG, máx. 2MB.</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="grid-2">
                            <div class="field">
                                <label for="nombre">Nombre</label>
                                <input id="nombre" name="nombre" type="text"
                                    value="{{ optional($estudiante->usuario)->nombre }}">
                                <div class="field">
                                    <label for="apellido">Apellido</label>
                                    <input id="apellido" name="apellido" type="text"
                                        value="{{ optional($estudiante->usuario)->apellido }}">
                                </div>
                            </div>
                            <div class="field">
                                <label for="run">RUN (opcional)</label>
                                <input id="run" name="run" type="text" value="{{ $estudiante->run }}">
                            </div>
                        </div>
                        <div class="grid-2">
                            <div class="field">
                                <label for="estado">Estado carrera</label>
                                <select id="estado" name="estado">
                                    <option value="Egresado/a"
                                        {{ $estudiante->estado_carrera == 'Egresado/a' ? 'selected' : '' }}>Egresado/a
                                    </option>
                                    <option value="Estudiante"
                                        {{ $estudiante->estado_carrera == 'Estudiante' ? 'selected' : '' }}>Estudiante
                                    </option>
                                    <option value="Titulado(a)"
                                        {{ $estudiante->estado_carrera == 'Titulad(a)' ? 'selected' : '' }}>Titulado/a
                                    </option>
                                </select>

                            </div>
                            <div class="field">
                                <label for="titulo">Carrera / Título</label>
                                <input id="titulo" name="titulo" type="text" value="{{ $estudiante->carrera }}">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- === Contacto === --}}
            <section class="card">
                <h2>Contacto</h2>
                <div class="grid-3">
                    <div class="field">
                        <label for="email">Correo</label>
                        <input id="email" name="email" type="email"
                            value="{{ optional($estudiante->usuario)->email }}">
                    </div>
                    <div class="field">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono" name="telefono" type="text" value="{{ $estudiante->telefono }}">
                    </div>
                    <div class="field">
                        <label for="ciudad">Ciudad</label>
                        <input id="ciudad" name="ciudad" type="text" value="{{ $estudiante->ciudad }}">
                    </div>
                </div>
                <div class="field">
                    <label for="resumen">Resumen (extracto breve)</label>
                    <textarea id="resumen" name="resumen" rows="3">{{ $estudiante->resumen }}</textarea>
                    <span class="hint">Máximo recomendado: 280–400 caracteres.</span>
                </div>
            </section>

            {{-- === Formación === --}}
            <section class="card">
                <h2>Formación</h2>
                <div class="grid-2">
                    <div class="field">
                        <label for="institucion">Institución</label>
                        <input id="institucion" name="institucion" type="text" value="{{ $estudiante->institucion }}">
                    </div>
                    <div class="field">
                        <label for="anio_egreso">Año de egreso</label>
                        <input id="anio_egreso" name="anio_egreso" type="number" min="1990" max="2099"
                            value="{{ $estudiante->anio_egreso }}">
                    </div>
                </div>
                <div class="field">
                    <label for="cursos">Cursos / Certificaciones (opcional)</label>
                    <textarea id="cursos" name="cursos" rows="2">{{ $estudiante->cursos }}</textarea>
                </div>
            </section>

            {{-- === CV / Portafolio === --}}
            <section class="card">
                <h2>CV y enlaces</h2>
                <div class="grid-2">
                    <div class="field">
                        <label for="cv">Subir CV (PDF)</label>
                        <input id="cv" name="cv" type="file" accept="application/pdf">
                        @if ($estudiante->ruta_cv)
                            <p class="hint">
                                CV actual: <a href="{{ asset($estudiante->ruta_cv) }}" target="_blank">Ver PDF</a>
                            </p>
                        @endif

                        <span class="hint">Máx. 4MB. Formato PDF.</span>
                    </div>
                    <div class="field">
                        <label for="linkedin">LinkedIn (opcional)</label>
                        <input id="linkedin" name="linkedin" type="url" value="{{ $estudiante->linkedin_url }}">
                    </div>
                </div>
                <div class="field">
                    <label for="portfolio">Portafolio / Sitio (opcional)</label>
                    <input id="portfolio" name="portfolio" type="url" value="{{ $estudiante->portfolio_url }}">
                </div>
            </section>

            {{-- === Preferencias === --}}
            <section class="card">
                <h2>Preferencias</h2>
                <div class="grid-3">

                    <div class="field">
                        <label for="area">Área de interés</label>
                        <select id="area" name="area">
                            <option value="1" {{ $estudiante->area_interes_id == 1 ? 'selected' : '' }}>Salud
                            </option>
                            <option value="2" {{ $estudiante->area_interes_id == 2 ? 'selected' : '' }}>
                                Administración</option>
                            <option value="3" {{ $estudiante->area_interes_id == 3 ? 'selected' : '' }}>Logística
                            </option>
                            <option value="4" {{ $estudiante->area_interes_id == 4 ? 'selected' : '' }}>Turismo
                            </option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="jornada">Jornada</label>
                        <select id="jornada" name="jornada">
                            <option value="1" {{ $estudiante->jornada_preferencia_id == 1 ? 'selected' : '' }}>
                                Completa</option>
                            <option value="2" {{ $estudiante->jornada_preferencia_id == 2 ? 'selected' : '' }}>Media
                                jornada</option>
                            <option value="3" {{ $estudiante->jornada_preferencia_id == 3 ? 'selected' : '' }}>
                                Práctica</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="modalidad">Modalidad</label>
                        <select id="modalidad" name="modalidad">
                            <option value="1" {{ $estudiante->modalidad_preferencia_id == 1 ? 'selected' : '' }}>
                                Presencial</option>
                            <option value="2" {{ $estudiante->modalidad_preferencia_id == 2 ? 'selected' : '' }}>
                                Híbrido</option>
                            <option value="3" {{ $estudiante->modalidad_preferencia_id == 3 ? 'selected' : '' }}>
                                Remoto</option>
                        </select>
                    </div>

                </div>
            </section>


            {{-- Acciones --}}
            <div class="form-actions">
                <a href="{{ route('usuarios.perfil') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" name="borrador" value="1" class="btn btn-outline">Guardar borrador</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </main>

    @push('styles')
        <style>
            /* Breadcrumb (igual al usado en páginas anteriores) */
            .breadcrumb ul {
                list-style: none;
                display: flex;
                gap: .5rem;
                padding: 0;
                margin: 0 0 .75rem;
                align-items: center;
                color: #6b7280;
                font-size: .92rem;
            }

            .breadcrumb li a {
                color: #6b7280;
                text-decoration: none;
            }

            .breadcrumb li a:hover {
                color: #374151;
                text-decoration: underline;
            }

            .breadcrumb li+li::before {
                content: '›';
                opacity: .6;
                margin: 0 .35rem 0 .15rem;
            }

            .breadcrumb .current {
                color: #111827;
                font-weight: 600;
            }

            .user-edit {
                padding: 1.25rem 0 2rem;
            }

            .page-header h1 {
                margin: 0 0 .25rem;
                font-size: 1.5rem;
            }

            .page-header .muted {
                color: #6b7280;
                margin: 0 0 1rem;
            }

            .card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 12px;
                padding: 1.25rem;
                margin-bottom: 1rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            }

            .card>h2 {
                margin: 0 0 1rem;
                font-size: 1.05rem;
            }

            .grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .grid-3 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }

            .field {
                display: flex;
                flex-direction: column;
                gap: .4rem;
            }

            .field input,
            .field select,
            .field textarea {
                padding: .65rem .75rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: .95rem;
                background: #fff;
            }

            .field textarea {
                resize: vertical;
            }

            .hint {
                color: #6b7280;
                font-size: .8rem;
            }

            .avatar-row {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .avatar-preview {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                object-fit: cover;
            }

            .avatar-actions input[type="file"] {
                padding: .55rem;
                border: 1px dashed #d1d5db;
                border-radius: 8px;
                background: #fafafa;
            }

            .form-actions {
                display: flex;
                gap: .75rem;
                justify-content: flex-end;
                align-items: center;
                margin-top: 1rem;
            }

            /* Botones (coherentes con los ya usados en el sitio) */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: .4rem;
                border-radius: 10px;
                padding: .8rem 1.25rem;
                font-weight: 700;
                line-height: 1;
                border: 1px solid transparent;
                text-decoration: none;
                cursor: pointer;
                transition: background .2s, color .2s, border-color .2s, box-shadow .2s, transform .06s;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            }

            .btn:active {
                transform: translateY(1px);
            }

            .btn-primary {
                background: #c91e25;
                color: #fff;
                border-color: #c91e25;
            }

            .btn-primary:hover {
                background: #b01920;
                border-color: #b01920;
            }

            .btn-primary:focus {
                outline: 0;
                box-shadow: 0 0 0 4px rgba(201, 30, 37, .18);
            }

            .btn-outline {
                background: #fff;
                color: #111827;
                border-color: #d1d5db;
            }

            .btn-outline:hover {
                background: #f9fafb;
                border-color: #cbd5e1;
            }

            .btn-outline:focus {
                outline: 0;
                box-shadow: 0 0 0 4px rgba(2, 132, 199, .15);
            }

            .btn-ghost {
                background: transparent;
                color: #c91e25;
                border-color: transparent;
            }

            .btn-ghost:hover {
                background: #fff1f2;
            }

            .btn-ghost:focus {
                outline: 0;
                box-shadow: 0 0 0 4px rgba(201, 30, 37, .15);
            }

            /* ====== Responsive ====== */

            /* Tablet (≤ 1024px) */
            @media (max-width: 1024px) {
                .grid-3 {
                    grid-template-columns: 1fr 1fr;
                }
            }

            /* Mobile (≤ 768px) */
            @media (max-width: 768px) {

                .grid-2,
                .grid-3 {
                    grid-template-columns: 1fr;
                }

                /* Acciones del formulario: apilar y ocupar ancho completo */
                .form-actions {
                    display: flex;
                    flex-direction: column;
                    align-items: stretch;
                    /* ← stretch va en align-items, no en justify-content */
                    gap: .5rem;
                    margin-top: 1rem;
                }

                .form-actions .btn {
                    width: 100%;
                }
            }

            /* Small mobile (≤ 640px) */
            @media (max-width: 640px) {

                /* Contenedor y tarjetas */
                .user-edit {
                    padding: .75rem 0 1.25rem;
                }

                .card {
                    padding: .9rem;
                    border-radius: 12px;
                }

                /* Tipografía de cabecera */
                .page-header h1 {
                    font-size: 1.25rem;
                }

                .page-header .muted {
                    font-size: .9rem;
                }

                /* Breadcrumb con salto de línea */
                .breadcrumb ul {
                    flex-wrap: wrap;
                    row-gap: .25rem;
                }

                /* Avatar compacto */
                .avatar-row {
                    align-items: flex-start;
                    gap: .75rem;
                }

                .avatar-preview {
                    width: 56px;
                    height: 56px;
                }

                /* Inputs legibles y sin zoom iOS */
                .field input,
                .field select,
                .field textarea {
                    font-size: 16px;
                    /* evita el auto-zoom en iOS */
                    padding: .65rem .75rem;
                }

                /* File inputs a todo el ancho */
                .avatar-actions input[type="file"],
                #cv {
                    width: 100%;
                }

                /* Aire en bloques de experiencia */
                .exp-block {
                    padding: .85rem;
                }
            }
        </style>
    @endpush
@endsection
