<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use App\Services\OfertaRecommendationService;


class UsuarioController extends Controller
{
    /**
     * PERFIL DEL POSTULANTE
     */
    public function perfil(OfertaRecommendationService $service)
    {
        $usuarioId = session('usuario_id');

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        // 🔥 Cargar postulaciones para la vista del perfil
        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        //  contadores de postulaciones
        $totalPostulaciones = $postulaciones->count();

        // OJO: ajusta 'estado' al nombre real de tu columna
        // (por ejemplo 'estado_postulacion' si así se llama en tu DB)
        $postulacionesEnAvance = $postulaciones
            ->where('estado_postulacion', '!=', 'Postulado')
            ->count();


        //  Obtener ofertas recomendadas usando el Service
        $ofertasRecomendadas = $service->getRecomendadas($estudiante);

        // contador de ofertas recomendadas
        $totalOfertasRecomendadas = $ofertasRecomendadas->count();

        //  Enviar todo a la vista
        return view('users.perfil', [
            'estudiante'               => $estudiante,
            'postulaciones'            => $postulaciones,
            'ofertasRecomendadas'      => $ofertasRecomendadas,
            'totalPostulaciones'       => $totalPostulaciones,       // NUEVO
            'postulacionesEnAvance'    => $postulacionesEnAvance,    // NUEVO
            'totalOfertasRecomendadas' => $totalOfertasRecomendadas, // NUEVO
        ]);
    }


    /**
     * FORMULARIO PARA EDITAR PERFIL
     */
    public function editar()
    {
        $usuarioId = session('usuario_id');
        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        return view('users.editar', [
            'estudiante' => $estudiante,
        ]);
    }

    public function update(Request $request)
    {
        $usuarioId = session('usuario_id');

        // ===========================
        // 1. OBTENER MODELOS
        // ===========================
        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();
        $usuario = $estudiante->usuario;

        // ===========================
        // 2. VALIDAR DATOS
        // ===========================
        $request->validate([
    'nombre'   => 'required|string|max:150',
    'apellido' => 'required|string|max:150',
    'email'    => 'required|email|max:150',

    'run'            => 'nullable|string|max:20',
    'estado'         => 'nullable|string|max:50',
    'titulo'         => 'nullable|string|max:255',
    'telefono'       => 'nullable|string|max:50',
    'ciudad'         => 'nullable|string|max:150',
    'resumen'        => 'nullable|string|max:2000',
    'institucion'    => 'nullable|string|max:255',
    'anio_egreso'    => 'nullable|integer|min:1990|max:2099',
    'cursos'         => 'nullable|string|max:2000',

    'linkedin'       => 'nullable|url|max:255',
    'portfolio'      => 'nullable|url|max:255',

    'area'           => 'nullable|integer',
    'jornada'        => 'nullable|integer',
    'modalidad'      => 'nullable|integer',

    'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    'cv'     => 'nullable|mimes:pdf|max:4096',
], [
    'resumen.max' => 'El resumen no puede superar los 2000 caracteres.',
    'cursos.max'  => 'La sección de cursos no puede superar los 2000 caracteres.',
]);


        // ===========================
        // 3. ACTUALIZAR USUARIO
        // ===========================
        $usuario->nombre   = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email    = $request->email;
        $usuario->save();

        // ===========================
        // 4. ACTUALIZAR ESTUDIANTE
        // ===========================
        $estudiante->run                     = $request->run;
        $estudiante->estado_carrera         = $request->estado;
        $estudiante->carrera                = $request->titulo;
        $estudiante->telefono               = $request->telefono;
        $estudiante->ciudad                 = $request->ciudad;
        $estudiante->resumen                = $request->resumen;
        $estudiante->institucion            = $request->institucion;
        $estudiante->anio_egreso            = $request->anio_egreso;
        $estudiante->cursos                 = $request->cursos;

        $estudiante->linkedin_url           = $request->linkedin;
        $estudiante->portfolio_url          = $request->portfolio;

        $estudiante->area_interes_id        = $request->area;
        $estudiante->jornada_preferencia_id = $request->jornada;
        $estudiante->modalidad_preferencia_id = $request->modalidad;

        // ===========================
        // 5. MANEJO DE AVATAR
        // ===========================
        if ($request->hasFile('avatar')) {

            // borrar avatar anterior
            if ($estudiante->avatar && file_exists(public_path($estudiante->avatar))) {
                unlink(public_path($estudiante->avatar));
            }

            $archivo = $request->file('avatar');
            $nombre = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('uploads/avatars'), $nombre);

            $estudiante->avatar = 'uploads/avatars/' . $nombre;
        }

        // ===========================
        // 6. MANEJO DE CV
        // ===========================
        if ($request->hasFile('cv')) {

            // borrar CV anterior
            if ($estudiante->ruta_cv && file_exists(public_path($estudiante->ruta_cv))) {
                unlink(public_path($estudiante->ruta_cv));
            }

            $archivo = $request->file('cv');
            $nombre = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/cv'), $nombre);

            $estudiante->ruta_cv = 'uploads/cv/' . $nombre;
        }

        $estudiante->save();

        // ===========================
        // 7. REDIRIGIR
        // ===========================
        return redirect('/usuarios/perfil')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * LISTA DE POSTULACIONES DEL USUARIO
     */
    public function postulaciones()
    {
        $usuarioId = session('usuario_id');
        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        // 🔥 Cargar postulaciones reales
        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('users.postulaciones', compact('postulaciones'));
    }
}