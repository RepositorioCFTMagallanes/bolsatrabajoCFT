<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas_trabajo', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('tipo_contrato_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->unsignedBigInteger('jornada_id')->nullable();

            // Datos principales
            $table->string('titulo', 150);
            $table->unsignedInteger('vacantes')->default(1);
            $table->date('fecha_cierre')->nullable();

            // Ubicación
            $table->string('region', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('direccion', 255)->nullable();

            // Sueldo
            $table->decimal('sueldo_min', 12, 2)->nullable();
            $table->decimal('sueldo_max', 12, 2)->nullable();
            $table->boolean('mostrar_sueldo')->default(true);

            // Detalle oferta
            $table->text('beneficios')->nullable();
            $table->text('descripcion');
            $table->text('requisitos');
            $table->text('habilidades_deseadas')->nullable();

            // Archivo adjunto
            $table->string('ruta_archivo', 255)->nullable();

            // Contacto
            $table->string('nombre_contacto', 150);
            $table->string('correo_contacto', 150);
            $table->string('telefono_contacto', 50)->nullable();

            // Estado workflow
            $table->tinyInteger('estado')->default(0);
            $table->text('motivo_rechazo')->nullable();
            $table->timestamp('revisada_en')->nullable();

            // Timestamps personalizados
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Índices (como en el SQL)
            $table->index('empresa_id');
            $table->index('area_id');
            $table->index('tipo_contrato_id');
            $table->index('modalidad_id');
            $table->index('jornada_id');

            // Foreign keys
            $table->foreign('empresa_id')
                  ->references('id')->on('empresas')
                  ->onDelete('cascade');

            $table->foreign('area_id')
                  ->references('id')->on('areas_empleo');

            $table->foreign('tipo_contrato_id')
                  ->references('id')->on('tipos_contrato');

            $table->foreign('modalidad_id')
                  ->references('id')->on('modalidades');

            $table->foreign('jornada_id')
                  ->references('id')->on('jornadas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas_trabajo');
    }
};
