<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        DB::table('roles')->insert([
            ['id' => 1, 'nombre' => 'administrador', 'descripcion' => 'Acceso completo al sistema'],
            ['id' => 2, 'nombre' => 'empresa', 'descripcion' => 'Usuario empresa que publica ofertas'],
            ['id' => 3, 'nombre' => 'estudiante', 'descripcion' => 'Usuario postulante/estudiante'],
        ]);

        // 2. Áreas de empleo
        DB::table('areas_empleo')->insert([
            ['id' => 1, 'nombre' => 'Industrial'],
            ['id' => 2, 'nombre' => 'Salud'],
            ['id' => 3, 'nombre' => 'Turismo'],
            ['id' => 4, 'nombre' => 'Administración'],
            ['id' => 5, 'nombre' => 'TI / Informática'],
            ['id' => 6, 'nombre' => 'Educación'],
            ['id' => 7, 'nombre' => 'Logística'],
        ]);

        // 3. Rubros
        DB::table('rubros')->insert([
            ['id' => 1, 'nombre' => 'Construcción'],
            ['id' => 2, 'nombre' => 'Industrial'],
            ['id' => 3, 'nombre' => 'Salud'],
            ['id' => 4, 'nombre' => 'Educación'],
            ['id' => 5, 'nombre' => 'Servicios'],
            ['id' => 6, 'nombre' => 'Turismo'],
            ['id' => 7, 'nombre' => 'Administración'],
            ['id' => 8, 'nombre' => 'TI / Informática'],
        ]);

        // 4. Tamaños empresa
        DB::table('tamanos_empresa')->insert([
            ['id' => 1, 'nombre' => '1-10'],
            ['id' => 2, 'nombre' => '11-50'],
            ['id' => 3, 'nombre' => '51-200'],
            ['id' => 4, 'nombre' => '201-500'],
            ['id' => 5, 'nombre' => '500+'],
        ]);

        // 5. Jornadas
        DB::table('jornadas')->insert([
            ['id' => 1, 'nombre' => 'Tiempo completo'],
            ['id' => 2, 'nombre' => 'Part-time'],
            ['id' => 3, 'nombre' => 'Turnos'],
        ]);

        // 6. Modalidades
        DB::table('modalidades')->insert([
            ['id' => 1, 'nombre' => 'Presencial'],
            ['id' => 2, 'nombre' => 'Remoto'],
            ['id' => 3, 'nombre' => 'Híbrido'],
        ]);

        // 7. Tipos de contrato
        DB::table('tipos_contrato')->insert([
            ['id' => 1, 'nombre' => 'Plazo fijo'],
            ['id' => 2, 'nombre' => 'Indefinido'],
            ['id' => 3, 'nombre' => 'Práctica'],
            ['id' => 4, 'nombre' => 'Honorarios'],
        ]);

        // 8. Usuario admin (usando las credenciales del backup)
        DB::table('usuarios')->insert([
            [
                'id' => 7,
                'rol_id' => 1,
                'rut' => null,
                'nombre' => 'admin',
                'apellido' => 'admin',
                'email' => 'admin@cft.cl',
                'contrasena' => Hash::make('12345678'), // Contraseña del usuario
                'email_verificado_en' => null,
                'token_recordar' => null,
            ],
        ]);
    }
}
