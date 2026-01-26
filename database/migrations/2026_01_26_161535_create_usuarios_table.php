<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            // Rol
            $table->foreignId('rol_id')
                  ->constrained('roles')
                  ->cascadeOnUpdate();

            // Datos personales
            $table->string('rut', 20)->nullable();
            $table->string('nombre', 100);
            $table->string('apellido')->nullable();

            // Auth
            $table->string('email', 150)->unique();
            $table->string('contrasena');
            $table->timestamp('email_verificado_en')->nullable();
            $table->string('token_recordar', 100)->nullable();

            // Timestamps personalizados (como el SQL)
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes(); // deleted_at

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
