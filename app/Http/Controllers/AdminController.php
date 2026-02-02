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
        | KPI: TASA PROMEDIO DE POSTULACIONES POR OFERTA
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
        | OFERTAS CREADAS POR MES (COMPATIBLE PGSQL / MYSQL)
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
        for ($mes = 1; $mes <= 12; $mes++) {
            $ofertasMesArray[] = $ofertasPorMes[$mes] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | POSTULACIONES POR ÁREA
        |--------------------------------------------------------------------------
        */
        $postPorArea = Postulacion::selectRaw(
                'areas_empleo.nombre AS area, COUNT(postulaciones.id) AS total'
            )
            ->join('ofertas_trabajo', 'postulaciones.oferta_id', '=', 'ofertas_trabajo.id')
            ->join('areas_empleo', 'ofertas_trabajo.area_id', '=', 'areas_empleo.id')
            ->groupBy('areas_empleo.nombre')
            ->orderByDesc('total')
            ->pluck('total', 'area')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | TOP 5 CARRERAS CON MÁS POSTULACIONES
        |--------------------------------------------------------------------------
        */
        $topCarreras = Postulacion::selectRaw(
                'estudiantes.carrera AS carrera, COUNT(postulaciones.id) AS total'
            )
            ->join('estudiantes', 'postulaciones.estudiante_id', '=', 'estudiantes.id')
            ->whereNotNull('estudiantes.carrera')
            ->groupBy('estudiantes.carrera')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'carrera')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | RETORNO A LA VISTA
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', [
            'adminName'          => session('usuario_nombre'),
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
