<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\OfertaTrabajo;
use App\Models\Postulacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | CONTADORES PRINCIPALES
        |--------------------------------------------------------------------------
        */
        $totalEmpresas      = Empresa::count();
        $totalOfertas       = OfertaTrabajo::where('estado', 1)->count();
        $totalPostulaciones = Postulacion::count();

        /*
        |--------------------------------------------------------------------------
        | KPI: TASA PROMEDIO POSTULACIONES POR OFERTA
        |--------------------------------------------------------------------------
        */
        $tasaPromedio = $totalOfertas > 0
            ? round(($totalPostulaciones / $totalOfertas) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TABLAS RESUMIDAS
        |--------------------------------------------------------------------------
        */
        $ultimasEmpresas = Empresa::orderByDesc('creado_en')
            ->limit(6)
            ->get();

        $ultimosPostulantes = Usuario::where('rol_id', 3)
            ->orderByDesc('creado_en')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | OFERTAS CREADAS POR MES (PostgreSQL / MySQL)
        |--------------------------------------------------------------------------
        */
        $driver = DB::getDriverName();
        $year   = now()->year;

        if ($driver === 'pgsql') {
            $ofertasPorMes = OfertaTrabajo::selectRaw(
                    'EXTRACT(MONTH FROM creado_en)::int AS mes, COUNT(*) AS total'
                )
                ->whereRaw('EXTRACT(YEAR FROM creado_en) = ?', [$year])
                ->groupByRaw('EXTRACT(MONTH FROM creado_en)')
                ->orderBy('mes')
                ->pluck('total', 'mes')
                ->toArray();
        } else {
            $ofertasPorMes = OfertaTrabajo::selectRaw(
                    'MONTH(creado_en) AS mes, COUNT(*) AS total'
                )
                ->whereYear('creado_en', $year)
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes')
                ->toArray();
        }

        // Normalizar a 12 meses
        $ofertasMesArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $ofertasMesArray[] = $ofertasPorMes[$i] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | POSTULACIONES POR ÁREA (LEFT JOIN SAFE)
        |--------------------------------------------------------------------------
        */
        $postPorArea = Postulacion::selectRaw(
                'areas_empleo.nombre AS area, COUNT(postulaciones.id) AS total'
            )
            ->leftJoin('ofertas_trabajo', 'postulaciones.oferta_id', '=', 'ofertas_trabajo.id')
            ->leftJoin('areas_empleo', 'ofertas_trabajo.area_id', '=', 'areas_empleo.id')
            ->whereNotNull('areas_empleo.nombre')
            ->groupBy('areas_empleo.nombre')
            ->orderByRaw('COUNT(postulaciones.id) DESC')
            ->pluck('total', 'area')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | TOP 5 CARRERAS CON MÁS POSTULACIONES (LEFT JOIN SAFE)
        |--------------------------------------------------------------------------
        */
        $topCarreras = Postulacion::selectRaw(
                'estudiantes.carrera AS carrera, COUNT(postulaciones.id) AS total'
            )
            ->leftJoin('estudiantes', 'postulaciones.estudiante_id', '=', 'estudiantes.id')
            ->whereNotNull('estudiantes.carrera')
            ->groupBy('estudiantes.carrera')
            ->orderByRaw('COUNT(postulaciones.id) DESC')
            ->limit(5)
            ->pluck('total', 'carrera')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | RETORNO A LA VISTA (SESIONES BLINDADAS)
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', [
            'adminName'          => session('usuario_nombre') ?? 'Administrador',
            'totalEmpresas'      => $totalEmpresas,
            'totalOfertas'       => $totalOfertas,
            'totalPostulaciones' => $totalPostulaciones,
            'tasaPromedio'       => $tasaPromedio,
            'ultimasEmpresas'    => $ultimasEmpresas,
            'ultimosPostulantes' => $ultimosPostulantes,
            'ofertasMesArray'    => $ofertasMesArray,
            'postPorArea'        => $postPorArea,
            'topCarreras'        => $topCarreras,
        ]);
    }
}
