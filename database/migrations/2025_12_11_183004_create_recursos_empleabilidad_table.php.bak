<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recursos_empleabilidad', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Contenido principal del recurso
            $table->string('titulo');
            $table->string('resumen', 250)->nullable(); // para el home/listado
            $table->longText('contenido')->nullable();  // texto completo del blog

            // Imagen destacada
            $table->string('imagen')->nullable(); // guardaremos la ruta/filename

            // Estado de publicación (1=publicado, 0=borrador/no publicado)
            $table->boolean('estado')->default(0);

            // Timestamps personalizados como en el resto del sistema
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->timestamp('eliminado_en')->nullable(); // para soft delete manual

            // Si más adelante quieres índices:
            // $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos_empleabilidad');
    }
};
