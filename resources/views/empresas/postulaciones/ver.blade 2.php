@extends('layouts.app')

@section('content')
    <main class="container perfil-postulante">

        <h2 class="titulo">Perfil del Postulante</h2>

        <!-- ====== TARJETA DE IDENTIDAD ====== -->
        <section class="perfil-card">

            <div class="foto-area">
                <img src="{{ $estudiante->avatar ? asset($estudiante->avatar) : asset('img/default-avatar.png') }}"
                    alt="Foto estudiante">
            </div>

            <div class="info-area">
                <h3>{{ $estudiante->usuario->nombre }}</h3>

                <p><strong>Correo:</strong> {{ $estudiante->usuario->email }}</p>

                <p><strong>Teléfono:</strong> {{ $estudiante->telefono ?? 'No registrado' }}</p>
                <p><strong>Ciudad:</strong> {{ $estudiante->ciudad ?? 'No registrada' }}</p>

                <p><strong>Estado carrera:</strong> {{ $estudiante->estado_carrera ?? 'No informado' }}</p>
                <p><strong>Carrera / Título:</strong> {{ $estudiante->carrera ?? 'No registrado' }}</p>

                @if ($estudiante->resumen)
                    <p style="margin-top:10px;"><strong>Resumen profesional:</strong></p>
                    <p>{{ $estudiante->resumen }}</p>
                @endif

                @if ($estudiante->ruta_cv)
                    <a href="{{ asset($estudiante->ruta_cv) }}" class="btn btn-danger mt-3" target="_blank">
                        Ver CV en PDF
                    </a>
                @else
                    <p style="margin-top:10px;"><strong>CV:</strong> No cargado por el postulante.</p>
                @endif
            </div>

        </section>

        <!-- ====== FORMACIÓN ACADÉMICA ====== -->
        <section class="section-card">
            <h3>Formación Académica</h3>

            <ul class="detail-list">
                <li><strong>Institución:</strong> {{ $estudiante->institucion ?? 'No registrada' }}</li>
                <li><strong>Año de egreso:</strong> {{ $estudiante->anio_egreso ?? 'No indicado' }}</li>
                <li><strong>Carrera / Programa:</strong> {{ $estudiante->carrera ?? 'No registrado' }}</li>
            </ul>

            @if ($estudiante->cursos)
                <p style="margin-top:10px;">
                    <strong>Cursos y certificaciones:</strong><br>
                    {{ $estudiante->cursos }}
                </p>
            @endif
        </section>

        <!-- ====== ENLACES DEL POSTULANTE ====== -->
        @if ($estudiante->linkedin_url || $estudiante->portfolio_url)
            <section class="section-card">
                <h3>Enlaces del postulante</h3>
                <ul class="detail-list">
                    @if ($estudiante->linkedin_url)
                        <li>
                            <strong>LinkedIn:</strong>
                            <a href="{{ $estudiante->linkedin_url }}" target="_blank">
                                {{ $estudiante->linkedin_url }}
                            </a>
                        </li>
                    @endif

                    @if ($estudiante->portfolio_url)
                        <li>
                            <strong>Portafolio / Página personal:</strong>
                            <a href="{{ $estudiante->portfolio_url }}" target="_blank">
                                {{ $estudiante->portfolio_url }}
                            </a>
                        </li>
                    @endif
                </ul>
            </section>
        @endif

        <!-- ====== POSTULACIONES DEL ESTUDIANTE ====== -->
        <section class="postulaciones-recientes mt-4 mb-5">
            <h3>Postulaciones del Estudiante</h3>

            @forelse($postulaciones as $p)
                <article class="post-card">
                    <h4>{{ $p->oferta->titulo }}</h4>
                    <p><strong>Postulado el:</strong>
                        {{ \Carbon\Carbon::parse($p->fecha_postulacion)->format('d-m-Y') }}
                    </p>
                </article>
            @empty
                <p>Este postulante aún no tiene postulaciones registradas.</p>
            @endforelse
        </section>

    </main>

    <style>
        .perfil-postulante .titulo {
            font-size: 26px;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .perfil-card {
            display: flex;
            gap: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 35px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .foto-area img {
            width: 160px;
            height: 160px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .info-area h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .section-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .section-card h3 {
            margin-bottom: 10px;
            font-size: 22px;
            font-weight: bold;
        }

        .detail-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .detail-list li {
            margin-bottom: 6px;
        }

        .postulaciones-recientes h3 {
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 22px;
            font-weight: bold;
        }

        .post-card {
            padding: 18px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #fafafa;
        }
    </style>

@endsection
