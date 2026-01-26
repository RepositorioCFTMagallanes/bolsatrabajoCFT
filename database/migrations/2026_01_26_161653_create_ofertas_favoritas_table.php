<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas_favoritas', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('oferta_id');

            // Fecha
            $table->timestamp('fecha_guardado')->useCurrent();

            // Índices (como en el SQL)
            $table->unique(['estudiante_id', 'oferta_id']);
            $table->index('oferta_id');

            // Foreign keys
            $table->foreign('estudiante_id')
                  ->references('id')->on('estudiantes')
                  ->onDelete('cascade');

            $table->foreign('oferta_id')
                  ->references('id')->on('ofertas_trabajo')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas_favoritas');
    }
};
