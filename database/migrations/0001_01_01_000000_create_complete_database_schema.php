<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // TABLAS PRINCIPALES (sin foreign keys)

        // Tabla roles
        Schema::create('roles', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 255)->nullable();
        });

        // Tabla usuarios
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('rol_id');
            $table->string('rut', 20)->nullable();
            $table->string('nombre', 100);
            $table->string('apellido', 255)->nullable();
            $table->string('email', 150)->unique();
            $table->string('contrasena', 255);
            $table->timestamp('email_verificado_en')->nullable();
            $table->string('token_recordar', 100)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at');

            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('restrict');
        });

        // Tablas catálogo
        Schema::create('areas_empleo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
        });

        Schema::create('rubros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
        });

        Schema::create('tamanos_empresa', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
        });

        Schema::create('jornadas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
        });

        Schema::create('modalidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
        });

        Schema::create('tipos_contrato', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
        });

        // Tabla empresas
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->string('ruta_logo', 255)->nullable();
            $table->string('nombre_comercial', 255)->nullable();
            $table->string('razon_social', 150)->nullable();
            $table->string('rut', 20)->nullable();
            $table->unsignedBigInteger('rubro_id')->nullable();
            $table->unsignedBigInteger('tamano_id')->nullable();
            $table->string('correo_contacto', 150);
            $table->string('telefono_contacto', 50);
            $table->string('sitio_web', 255)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->enum('recepcion_postulaciones', ['plataforma', 'correo', 'url'])->default('plataforma');
            $table->string('correo_postulaciones', 150)->nullable();
            $table->string('url_postulaciones', 255)->nullable();
            $table->boolean('mostrar_sueldo')->default(true);
            $table->boolean('mostrar_logo')->default(true);
            $table->string('nombre_representante', 150)->nullable();
            $table->string('cargo_representante', 150)->nullable();
            $table->string('correo_representante', 150)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('rubro_id')->references('id')->on('rubros')->onDelete('set null');
            $table->foreign('tamano_id')->references('id')->on('tamanos_empresa')->onDelete('set null');
        });

        // Tabla estudiantes
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->string('run', 20)->nullable();
            $table->string('estado_carrera', 50)->nullable();
            $table->string('carrera', 150)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->text('resumen')->nullable();
            $table->string('institucion', 150)->nullable();
            $table->year('anio_egreso')->nullable();
            $table->text('cursos')->nullable();
            $table->string('ruta_cv', 255)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->string('portfolio_url', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->unsignedBigInteger('area_interes_id')->nullable();
            $table->unsignedBigInteger('jornada_preferencia_id')->nullable();
            $table->unsignedBigInteger('modalidad_preferencia_id')->nullable();
            $table->enum('visibilidad', ['publico', 'privado'])->default('publico');
            $table->enum('frecuencia_alertas', ['diario', 'semanal', 'ninguna'])->default('diario');
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('area_interes_id')->references('id')->on('areas_empleo')->onDelete('set null');
            $table->foreign('jornada_preferencia_id')->references('id')->on('jornadas')->onDelete('set null');
            $table->foreign('modalidad_preferencia_id')->references('id')->on('modalidades')->onDelete('set null');
        });

        // Tabla experiencias_estudiante
        Schema::create('experiencias_estudiante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->string('puesto', 150);
            $table->string('empresa', 150);
            $table->string('periodo', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
        });

        // Tabla ofertas_trabajo
        Schema::create('ofertas_trabajo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('titulo', 150);
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('tipo_contrato_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->unsignedBigInteger('jornada_id')->nullable();
            $table->unsignedInteger('vacantes')->default(1);
            $table->date('fecha_cierre')->nullable();
            $table->string('region', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->decimal('sueldo_min', 12, 2)->nullable();
            $table->decimal('sueldo_max', 12, 2)->nullable();
            $table->boolean('mostrar_sueldo')->default(true);
            $table->text('beneficios')->nullable();
            $table->text('descripcion');
            $table->text('requisitos');
            $table->text('habilidades_deseadas')->nullable();
            $table->string('ruta_archivo', 255)->nullable();
            $table->string('nombre_contacto', 150);
            $table->string('correo_contacto', 150);
            $table->string('telefono_contacto', 50)->nullable();
            $table->boolean('estado')->default(false);
            $table->text('motivo_rechazo')->nullable();
            $table->timestamp('revisada_en')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas_empleo')->onDelete('restrict');
            $table->foreign('tipo_contrato_id')->references('id')->on('tipos_contrato')->onDelete('restrict');
            $table->foreign('modalidad_id')->references('id')->on('modalidades')->onDelete('restrict');
            $table->foreign('jornada_id')->references('id')->on('jornadas')->onDelete('set null');
        });

        // Tabla postulaciones
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('oferta_id');
            $table->string('estado_postulacion', 50)->default('pendiente');
            $table->timestamp('fecha_postulacion')->useCurrent();
            $table->text('notas')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['estudiante_id', 'oferta_id']);
            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('oferta_id')->references('id')->on('ofertas_trabajo')->onDelete('cascade');
        });

        // Tabla ofertas_favoritas
        Schema::create('ofertas_favoritas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('oferta_id');
            $table->timestamp('fecha_guardado')->useCurrent();

            $table->unique(['estudiante_id', 'oferta_id']);
            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('oferta_id')->references('id')->on('ofertas_trabajo')->onDelete('cascade');
        });

        // Tabla recursos_empleabilidad
        Schema::create('recursos_empleabilidad', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->string('resumen', 250)->nullable();
            $table->longText('contenido')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->boolean('estado')->default(false);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->softDeletes('eliminado_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recursos_empleabilidad');
        Schema::dropIfExists('ofertas_favoritas');
        Schema::dropIfExists('postulaciones');
        Schema::dropIfExists('ofertas_trabajo');
        Schema::dropIfExists('experiencias_estudiante');
        Schema::dropIfExists('estudiantes');
        Schema::dropIfExists('empresas');
        Schema::dropIfExists('tipos_contrato');
        Schema::dropIfExists('modalidades');
        Schema::dropIfExists('jornadas');
        Schema::dropIfExists('tamanos_empresa');
        Schema::dropIfExists('rubros');
        Schema::dropIfExists('areas_empleo');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
    }
};
