<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\OfertaTrabajo;
use App\Models\Postulacion;
use App\Models\Usuario;
use App\Models\Estudiante; // <- agrega esto
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // === COUNTERS ===
        $totalEmpresas      = Empresa::count();
        $totalOfertas       = OfertaTrabajo::where('estado', 1)->count();
        $totalPostulaciones = Postulacion::count();

        // === KPI: TASA PROMEDIO POSTULACIONES POR OFERTA ===
        $tasaPromedio = $totalOfertas > 0
            ? round(($totalPostulaciones / $totalOfertas) * 100, 2)
            : 0;

        // === TABLAS RESUMIDAS ===
        $ultimasEmpresas = Empresa::orderByDesc('creado_en')
            ->take(6)
            ->get();

        $ultimosPostulantes = Usuario::where('rol_id', 3)
            ->orderByDesc('creado_en')
            ->take(6)
            ->get();

        // === 1) OFERTAS CREADAS POR MES (MySQL + PostgreSQL) ===
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL
            $ofertasPorMes = OfertaTrabajo::selectRaw('EXTRACT(MONTH FROM creado_en) as mes, COUNT(*) as total')
                ->whereYear('creado_en', now()->year)
                ->groupByRaw('EXTRACT(MONTH FROM creado_en)')
                ->orderBy('mes')
                ->pluck('total', 'mes')
                ->toArray();
        } else {
            // MySQL / MariaDB
            $ofertasPorMes = OfertaTrabajo::selectRaw('MONTH(creado_en) as mes, COUNT(*) as total')
                ->whereYear('creado_en', now()->year)
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes')
                ->toArray();
        }

        // Normalizar a los 12 meses
        $ofertasMesArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $ofertasMesArray[] = $ofertasPorMes[$i] ?? 0;
        }

        // === 2) POSTULACIONES POR ÁREA ===
        // postulaciones → ofertas_trabajo → areas_empleo
        $postPorArea = Postulacion::selectRaw('areas_empleo.nombre as area, COUNT(postulaciones.id) as total')
            ->join('ofertas_trabajo', 'postulaciones.oferta_id', '=', 'ofertas_trabajo.id')
            ->join('areas_empleo', 'ofertas_trabajo.area_id', '=', 'areas_empleo.id')
            ->groupBy('areas_empleo.nombre')
            ->orderByDesc('total')
            ->pluck('total', 'area')
            ->toArray();

        // === 3) CARRERAS CON MÁS POSTULACIONES ===
        // postulaciones → estudiantes.carrera
        $topCarreras = Postulacion::selectRaw('estudiantes.carrera as carrera, COUNT(postulaciones.id) as total')
            ->join('estudiantes', 'postulaciones.estudiante_id', '=', 'estudiantes.id')
            ->whereNotNull('estudiantes.carrera')
            ->groupBy('estudiantes.carrera')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'carrera')
            ->toArray();

        return view('admin.dashboard', [
            'adminName'          => session('usuario_nombre'),
            'totalEmpresas'      => $totalEmpresas,
            'totalOfertas'       => $totalOfertas,
            'totalPostulaciones' => $totalPostulaciones,
            'tasaPromedio'       => $tasaPromedio,
            'ultimasEmpresas'    => $ultimasEmpresas,
            'ultimosPostulantes' => $ultimosPostulantes,

            // Datos para los gráficos
            'ofertasMesArray'    => $ofertasMesArray,
            'postPorArea'        => $postPorArea,
            'topCarreras'        => $topCarreras,
        ]);
    }
}