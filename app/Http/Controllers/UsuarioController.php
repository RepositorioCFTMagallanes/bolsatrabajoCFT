<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use App\Services\OfertaRecommendationService;
use Illuminate\Support\Facades\Log;



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

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->firstOrFail();
        $usuario = $estudiante->usuario;

        $request->validate([
            'nombre'   => 'required|string|max:150',
            'apellido' => 'required|string|max:150',
            'email'    => 'required|email|max:150',

            'run'         => 'nullable|string|max:20',
            'estado'      => 'nullable|string|max:50',
            'titulo'      => 'nullable|string|max:255',
            'telefono'    => 'nullable|string|max:50',
            'ciudad'      => 'nullable|string|max:150',
            'resumen'     => 'nullable|string|max:800',
            'institucion' => 'nullable|string|max:255',
            'anio_egreso' => 'nullable|integer|min:1990|max:2099',
            'cursos'      => 'nullable|string',

            'linkedin'  => 'nullable|url|max:255',
            'portfolio' => 'nullable|url|max:255',

            'area'      => 'nullable|integer',
            'jornada'   => 'nullable|integer',
            'modalidad' => 'nullable|integer',

            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv'     => 'nullable|mimes:pdf|max:4096',
        ]);

        // ===========================
        // USUARIO
        // ===========================
        $usuario->update([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
        ]);

        // ===========================
        // ESTUDIANTE
        // ===========================
        $estudiante->fill([
            'run'                     => $request->run,
            'estado_carrera'          => $request->estado,
            'carrera'                 => $request->titulo,
            'telefono'                => $request->telefono,
            'ciudad'                  => $request->ciudad,
            'resumen'                 => $request->resumen,
            'institucion'             => $request->institucion,
            'anio_egreso'             => $request->anio_egreso,
            'cursos'                  => $request->cursos,
            'linkedin_url'            => $request->linkedin,
            'portfolio_url'           => $request->portfolio,
            'area_interes_id'         => $request->area,
            'jornada_preferencia_id'  => $request->jornada,
            'modalidad_preferencia_id' => $request->modalidad,
        ]);

        // ===========================
        // AVATAR
        // ===========================
        try {
            if ($request->hasFile('avatar')) {

                // eliminar avatar previo (seguro)
                if ($estudiante->avatar) {
                    $ruta = public_path($estudiante->avatar);
                    if (is_file($ruta)) {
                        @unlink($ruta);
                    }
                }

                $destino = public_path('uploads/avatars');
                if (!is_dir($destino)) {
                    mkdir($destino, 0775, true);
                }

                $archivo = $request->file('avatar');
                $nombre = uniqid('avatar_') . '.' . $archivo->getClientOriginalExtension();
                $archivo->move($destino, $nombre);

                $estudiante->avatar = 'uploads/avatars/' . $nombre;
            }
        } catch (\Throwable $e) {
            Log::error('Error subiendo avatar', [
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors('Error al subir la foto de perfil');
        }

        // ===========================
        // CV
        // ===========================
        try {
            if ($request->hasFile('cv')) {

                if ($estudiante->ruta_cv) {
                    $ruta = public_path($estudiante->ruta_cv);
                    if (is_file($ruta)) {
                        @unlink($ruta);
                    }
                }

                $destino = public_path('uploads/cv');
                if (!is_dir($destino)) {
                    mkdir($destino, 0775, true);
                }

                $archivo = $request->file('cv');
                $nombre = time() . '_' . preg_replace('/\s+/', '_', $archivo->getClientOriginalName());
                $archivo->move($destino, $nombre);

                $estudiante->ruta_cv = 'uploads/cv/' . $nombre;
            }
        } catch (\Throwable $e) {
            Log::error('Error subiendo CV', [
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors('Error al subir el CV');
        }

        $estudiante->save();

        return redirect('/usuarios/perfil')
            ->with('success', 'Perfil actualizado correctamente.');
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
