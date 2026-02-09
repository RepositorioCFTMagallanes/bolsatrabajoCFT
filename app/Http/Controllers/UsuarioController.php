<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\OfertaRecommendationService;

class UsuarioController extends Controller
{
    /**
     * PERFIL DEL POSTULANTE
     */
    public function perfil(OfertaRecommendationService $service)
    {
        $usuarioId = session('usuario_id');

        // Protección de sesión
        if (!$usuarioId) {
            return redirect('/login')->with('error', 'Sesión expirada. Inicia sesión nuevamente.');
        }

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        // Protección si no existe perfil estudiante
        if (!$estudiante) {
            return redirect('/usuarios/editar')
                ->with('error', 'Debes completar tu perfil antes de continuar.');
        }

        // Cargar postulaciones
        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        $totalPostulaciones = $postulaciones->count();

        $postulacionesEnAvance = $postulaciones
            ->where('estado_postulacion', '!=', 'Postulado')
            ->count();

        // Ofertas recomendadas
        $ofertasRecomendadas = $service->getRecomendadas($estudiante);
        $totalOfertasRecomendadas = $ofertasRecomendadas->count();

        return view('users.perfil', [
            'estudiante'               => $estudiante,
            'postulaciones'            => $postulaciones,
            'ofertasRecomendadas'      => $ofertasRecomendadas,
            'totalPostulaciones'       => $totalPostulaciones,
            'postulacionesEnAvance'    => $postulacionesEnAvance,
            'totalOfertasRecomendadas' => $totalOfertasRecomendadas,
        ]);
    }

    /**
     * FORMULARIO PARA EDITAR PERFIL
     */
    public function editar()
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect('/login');
        }

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        return view('users.editar', [
            'estudiante' => $estudiante,
        ]);
    }


    /**
     * ACTUALIZAR PERFIL
     */
    public function update(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect('/login');
        }

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();


        if (!$estudiante) {
            return redirect('/usuarios/editar');
        }

        $usuario = $estudiante->usuario;

        if (!$usuario) {
            return redirect('/usuarios/editar')
                ->with('error', 'Error al cargar el usuario.');
        }


        // ===========================
        // VALIDACIÓN
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
        // ACTUALIZAR USUARIO
        // ===========================
        $usuario->nombre   = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email    = $request->email;
        $usuario->save();

        // ===========================
        // ACTUALIZAR ESTUDIANTE
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
        // AVATAR EN GCS
        // ===========================
        if ($request->hasFile('avatar')) {
            try {
                if (!empty($estudiante->avatar)) {
                    Storage::disk('gcs')->delete($estudiante->avatar);
                }

                $file = $request->file('avatar');
                $filename = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();

                Storage::disk('gcs')->put(
                    'avatars/' . $filename,
                    file_get_contents($file->getRealPath())
                );

                $estudiante->avatar = 'avatars/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Error subiendo avatar: ' . $e->getMessage());
            }
        }


        // ===========================
        // CV EN GCS
        // ===========================
        if ($request->hasFile('cv')) {
            try {
                if (!empty($estudiante->ruta_cv)) {
                    Storage::disk('gcs')->delete($estudiante->ruta_cv);
                }

                $file = $request->file('cv');
                $filename = 'cv_' . time() . '.' . $file->getClientOriginalExtension();

                Storage::disk('gcs')->put(
                    'cv/' . $filename,
                    file_get_contents($file->getRealPath())
                );

                $estudiante->ruta_cv = 'cv/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Error subiendo CV: ' . $e->getMessage());
            }
        }


        $estudiante->save();

        return redirect('/usuarios/perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * LISTA DE POSTULACIONES
     */
    public function postulaciones()
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect('/login');
        }

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        if (!$estudiante) {
            return redirect('/usuarios/editar');
        }

        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('users.postulaciones', compact('postulaciones'));
    }
}
