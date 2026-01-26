<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            // FK
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->unsignedBigInteger('rubro_id')->nullable();
            $table->unsignedBigInteger('tamano_id')->nullable();

            // Datos empresa
            $table->string('ruta_logo')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->string('razon_social', 150)->nullable();
            $table->string('rut', 20)->nullable();

            // Contacto
            $table->string('correo_contacto', 150);
            $table->string('telefono_contacto', 50);
            $table->string('sitio_web')->nullable();

            // Ubicación
            $table->string('region', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('direccion')->nullable();

            // Redes / descripción
            $table->text('descripcion')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();

            // Postulaciones
            $table->enum('recepcion_postulaciones', ['plataforma', 'correo', 'url'])
                  ->default('plataforma');

            $table->string('correo_postulaciones', 150)->nullable();
            $table->string('url_postulaciones')->nullable();

            // Configuración visual
            $table->boolean('mostrar_sueldo')->default(true);
            $table->boolean('mostrar_logo')->default(true);

            // Representante
            $table->string('nombre_representante', 150)->nullable();
            $table->string('cargo_representante', 150)->nullable();
            $table->string('correo_representante', 150)->nullable();

            // Timestamps personalizados
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Índices
            $table->index('rubro_id');
            $table->index('tamano_id');

            // Foreign keys
            $table->foreign('usuario_id')
                  ->references('id')->on('usuarios')
                  ->onDelete('cascade');

            $table->foreign('rubro_id')
                  ->references('id')->on('rubros');

            $table->foreign('tamano_id')
                  ->references('id')->on('tamanos_empresa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
