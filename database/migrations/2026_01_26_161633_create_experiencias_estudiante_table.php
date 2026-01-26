<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiencias_estudiante', function (Blueprint $table) {
            $table->id();

            // FK
            $table->unsignedBigInteger('estudiante_id');

            // Datos de la experiencia
            $table->string('puesto', 150);
            $table->string('empresa', 150);
            $table->string('periodo', 50)->nullable();
            $table->text('descripcion')->nullable();

            // Timestamps personalizados
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Índice (como en el SQL)
            $table->index('estudiante_id');

            // Foreign key
            $table->foreign('estudiante_id')
                  ->references('id')->on('estudiantes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiencias_estudiante');
    }
};
