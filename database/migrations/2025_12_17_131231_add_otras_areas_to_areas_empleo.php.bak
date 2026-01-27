<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('areas_empleo')->insert([
            'nombre' => 'Otras Áreas'
        ]);
    }

    public function down(): void
    {
        DB::table('areas_empleo')
            ->where('nombre', 'Otras Áreas')
            ->delete();
    }
};
