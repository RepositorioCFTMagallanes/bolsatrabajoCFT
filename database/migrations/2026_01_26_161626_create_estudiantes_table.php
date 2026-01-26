<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();

            // FK
            $table->unsignedBigInteger('usuario_id')->unique();

            // Datos personales / académicos
            $table->string('run', 20)->nullable();
            $table->string('estado_carrera', 50)->nullable();
            $table->string('carrera', 150)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->text('resumen')->nullable();
            $table->string('institucion', 150)->nullable();
            $table->year('anio_egreso')->nullable();
            $table->text('cursos')->nullable();

            // Archivos / enlaces
            $table->string('ruta_cv')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('avatar')->nullable();

            // Preferencias
            $table->unsignedBigInteger('area_interes_id')->nullable();
            $table->unsignedBigInteger('jornada_preferencia_id')->nullable();
            $table->unsignedBigInteger('modalidad_preferencia_id')->nullable();

            $table->enum('visibilidad', ['publico', 'privado'])->default('publico');
            $table->enum('frecuencia_alertas', ['diario', 'semanal', 'ninguna'])->default('diario');

            // Timestamps personalizados
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Índices (como en el SQL)
            $table->index('area_interes_id');
            $table->index('jornada_preferencia_id');
            $table->index('modalidad_preferencia_id');

            // Foreign keys
            $table->foreign('usuario_id')
                  ->references('id')->on('usuarios')
                  ->onDelete('cascade');

            $table->foreign('area_interes_id')
                  ->references('id')->on('areas_empleo');

            $table->foreign('jornada_preferencia_id')
                  ->references('id')->on('jornadas');

            $table->foreign('modalidad_preferencia_id')
                  ->references('id')->on('modalidades');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
