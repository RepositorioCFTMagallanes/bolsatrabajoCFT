<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recursos_empleabilidad', function (Blueprint $table) {
            $table->id();

            $table->string('titulo', 255);
            $table->string('resumen', 250)->nullable();
            $table->longText('contenido')->nullable();
            $table->string('imagen', 255)->nullable();

            $table->boolean('estado')->default(false);

            // Timestamps EXACTOS al SQL
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            $table->timestamp('eliminado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recursos_empleabilidad');
    }
};
