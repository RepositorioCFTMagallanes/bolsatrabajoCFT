<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOfertasTrabajoWorkflow extends Migration
{
    /**
     * Agrega nuevos campos para soporte del flujo de aprobación/rechazo.
     */
    public function up()
    {
        Schema::table('ofertas_trabajo', function (Blueprint $table) {

            // Motivo opcional cuando una oferta es rechazada
            if (!Schema::hasColumn('ofertas_trabajo', 'motivo_rechazo')) {
                $table->text('motivo_rechazo')
                    ->nullable()
                    ->after('estado');
            }

            // Marca cuándo la oferta fue revisada por un administrador
            if (!Schema::hasColumn('ofertas_trabajo', 'revisada_en')) {
                $table->timestamp('revisada_en')
                    ->nullable()
                    ->after('motivo_rechazo');
            }
        });
    }

    /**
     * Reversión segura sin afectar otros campos existentes.
     */
    public function down()
    {
        Schema::table('ofertas_trabajo', function (Blueprint $table) {

            // Eliminamos solo si existen (evita errores en rollback)
            if (Schema::hasColumn('ofertas_trabajo', 'motivo_rechazo')) {
                $table->dropColumn('motivo_rechazo');
            }

            if (Schema::hasColumn('ofertas_trabajo', 'revisada_en')) {
                $table->dropColumn('revisada_en');
            }

        });
    }
}
