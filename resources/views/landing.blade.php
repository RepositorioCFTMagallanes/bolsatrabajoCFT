@extends('layouts.app')

@section('content')
    <!--
                                                                                                                    Página de inicio de la bolsa de empleo.  Esta vista compone las secciones
                                                                                                                    principales descritas en el diseño de referencia: hero, trabajos
                                                                                                                    destacados, empresas destacadas, testimonios y llamada a la acción.
                                                                                                                  -->

    <!-- Sección Hero -->
    <section class="hero">
        <div class="hero-wrapper container">
            <div class="hero-content">
                <h1>Encuentra tu práctica o empleo ideal</h1>
                <p>
                    Conecta con empresas que buscan talento técnico en Magallanes.
                </p>
                <form class="search-form" action="{{ route('empleos.index') }}" method="GET">
                    <input type="text" name="q" placeholder="Busca tu práctica o empleo..." value="{{ request('q') }}"
                        aria-label="Buscar práctica o empleo" />
                    <button type="submit" class="btn-search">Buscar</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Trabajos destacados -->
    <section class="featured_jobs">
        <div class="container">
            <h2>Trabajos Destacados PRUEBA</h2>

            <div class="jobs-grid">

                @forelse ($featuredJobs as $job)
                    <article class="job-card">

                        <!-- Logo de la empresa -->
                        <div class="logo-wrapper">
                            <img src="{{ $job->empresa && $job->empresa->ruta_logo ? asset($job->empresa->ruta_logo) : asset('img/iconos/logo.png') }}"
                                alt="{{ $job->empresa->nombre_comercial ?? 'Empresa no especificada' }}">
                        </div>

                        <!-- Contenido del trabajo -->
                        <h3>{{ $job->titulo }}</h3>

                        <p class="company">
                            {{ $job->empresa?->nombre_comercial ?? 'Empresa no especificada' }}
                        </p>

                        <p class="location">
                            {{ $job->ciudad ?? 'Ubicación no especificada' }}
                        </p>

                        <a href="{{ route('ofertas.detalle', $job->id) }}" class="details-link">Ver Detalles</a>
                    </article>
                @empty
                    <p>No hay ofertas destacadas disponibles.</p>
                @endforelse

            </div>
        </div>
    </section>


    <!-- Empresas destacadas -->
    <section class="featured-companies">
        <div class="container">
            <h2>Empresas Destacadas</h2>
            <p class="subtitle">
                Las empresas más atractivas para trabajar están reclutando talentos como
                tú
            </p>
            <div class="companies-row">
                @forelse ($topCompanies as $company)
                    <div class="company-item">
                        <img src="{{ $company->ruta_logo ? asset($company->ruta_logo) : asset('img/iconos/logo.png') }}"
                            alt="{{ $company->nombre_comercial }}">
                    </div>
                @empty
                    <p>No hay empresas destacadas aún.</p>
                @endforelse

            </div>
        </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="testimonials-section">
        <div class="container">
            <div class="mission">
                <p>
                    “Formamos personas comprometidas con el crecimiento de nuestra región. Te acompañamos en cada paso de tu
                    desarrollo profesional y velamos porque tus talentos encuentren su mejor destino”.
                </p>
                <p>
                    Sabemos que comenzar tu <span class="highlight">vida laboral</span> es un
                    desafío, pero en <span class="highlight">CFT Magallanes</span> te acompañamos a
                    dar el primer paso.
                </p>
            </div>

            @php
                $testimonials = [
                    [
                        'name' => 'Daniela Soto',
                        'role' => 'Técnico en Enfermería',
                        'cta' => 'Ver empleos del área salud',
                        'area_id' => 2,
                        'photo' => 'test (1).png',
                        'quote' =>
                            '“Gracias al CFT Magallanes encontré una práctica donde puedo aplicar todo lo que aprendí. El equipo me ayudó en cada paso del proceso.”',
                    ],
                    [
                        'name' => 'Camila Reyes',
                        'role' => 'Técnico en Administración',
                        'cta' => 'Ver empleos administrativos',
                        'area_id' => 4,
                        'photo' => 'test (2).png',
                        'quote' =>
                            '“Participé en una feria laboral del CFT y hoy trabajo en una empresa local. Me sentí muy apoyado por los docentes y el equipo de vinculación.”',
                    ],
                    [
                        'name' => 'Felipe Andrade',
                        'role' => 'Técnico en Educación Parvularia',
                        'cta' => 'Ver empleos en educación',
                        'area_id' => 6,
                        'photo' => 'test (3).png',
                        'quote' =>
                            '“Nunca imaginé que una práctica profesional me abriría tantas puertas. El CFT Magallanes me ayudó a conectar con mi primer empleo.”',
                    ],
                ];
            @endphp

            <div class="testimonials">
                @foreach ($testimonials as $t)
                    <article class="testimonial-card">
                        <div class="avatar">
                            <img src="{{ asset('img/testimonios/' . rawurlencode($t['photo'])) }}"
                                alt="Foto de {{ $t['name'] }}">
                        </div>
                        <p class="quote">{{ $t['quote'] }}</p>
                        <p class="author"><strong>Nombre:</strong> {{ $t['name'] }}</p>
                        <p class="position"><strong>Cargo:</strong> {{ $t['role'] }}</p>
                        <a href="{{ route('empleos.index', ['area[]' => $t['area_id']]) }}" class="job-link">
                            {{ $t['cta'] }}
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Blogs y páginas de interés e información adicional -->
    <section class="cft-blog py-5">
        <div class="container">
            <p class="overline text-center mb-2">Recursos de Empleabilidad</p>
            <h2 class="display-6 text-center fw-bold mb-4">
                Conoce las últimas novedades para tu empleabilidad
            </h2>

            <div class="cft-blog-grid">

                @forelse ($recursos as $recurso)
                    <article class="cft-blog-card">

                        <a href="{{ route('recursos.show', $recurso->id) }}" class="cft-blog-media ratio ratio-16x9">

                            @if ($recurso->imagen)
                                <img src="{{ $recurso->imagen ? asset($recurso->imagen) : asset('img/otros/placeholder.png') }}"
                                    alt="{{ $recurso->titulo }}" loading="lazy">
                            @else
                                <img src="{{ asset('img/otros/placeholder.png') }}" alt="Recurso sin imagen"
                                    loading="lazy">
                            @endif

                        </a>

                        <h3 class="cft-blog-title mt-3 mb-2">
                            {{ $recurso->titulo }}
                        </h3>

                        <a href="{{ route('recursos.show', $recurso->id) }}" class="btn btn-cft">
                            LEER MÁS
                        </a>

                    </article>
                @empty
                    <p class="text-center">
                        Aún no hay recursos de empleabilidad publicados.
                    </p>
                @endforelse

                {{-- CTA --}}
                <div class="cft-blog-cta">
                    <a href="{{ route('recursos.index') }}" class="btn btn-cft">
                        Ver todos los recursos
                    </a>
                </div>

            </div>
        </div>
    </section>



    <!-- Llamado a la acción final -->
    <section class="call-to-action">
        <div class="container cta-inner">
            <h2>¿Listo para encontrar tu próximo desafío profesional?</h2>
            <p>
                Ingresa al buscador de empleos y conecta con empresas de Magallanes.
            </p>
            <a href="{{ route('empleos.index') }}" class="cta-button">Ir al buscador de empleos</a>
        </div>
    </section>
@endsection
