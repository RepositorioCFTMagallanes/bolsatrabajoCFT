<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\OfertaRecommendationService;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * PERFIL DEL POSTULANTE
     */
    public function perfil(OfertaRecommendationService $service)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect('/login')->with('error', 'Sesión expirada. Inicia sesión nuevamente.');
        }

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$estudiante) {
            return redirect('/usuarios/editar')
                ->with('error', 'Debes completar tu perfil antes de continuar.');
        }

        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        $ofertasRecomendadas = $service->getRecomendadas($estudiante);

        return view('users.perfil', [
            'estudiante'               => $estudiante,
            'postulaciones'            => $postulaciones,
            'ofertasRecomendadas'      => $ofertasRecomendadas,
            'totalPostulaciones'       => $postulaciones->count(),
            'postulacionesEnAvance'    => $postulaciones->where('estado_postulacion', '!=', 'Postulado')->count(),
            'totalOfertasRecomendadas' => $ofertasRecomendadas->count(),
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
            ->firstOrFail();

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
            ->firstOrFail();

        $usuario = $estudiante->usuario;

        if (!$usuario) {
            return redirect('/usuarios/editar')
                ->with('error', 'Error al cargar el usuario.');
        }

        // VALIDACIÓN
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
        ]);

        DB::beginTransaction();

        try {

            // ACTUALIZAR USUARIO
            $usuario->update([
                'nombre'   => $request->nombre,
                'apellido' => $request->apellido,
                'email'    => $request->email,
            ]);

            // ACTUALIZAR ESTUDIANTE
            $estudiante->fill([
                'run'                      => $request->run,
                'estado_carrera'           => $request->estado,
                'carrera'                  => $request->titulo,
                'telefono'                 => $request->telefono,
                'ciudad'                   => $request->ciudad,
                'resumen'                  => $request->resumen,
                'institucion'              => $request->institucion,
                'anio_egreso'              => $request->anio_egreso,
                'cursos'                   => $request->cursos,
                'linkedin_url'             => $request->linkedin,
                'portfolio_url'            => $request->portfolio,
                'area_interes_id'          => $request->area,
                'jornada_preferencia_id'   => $request->jornada,
                'modalidad_preferencia_id' => $request->modalidad,
            ]);

            // ===========================
            // AVATAR EN GCS
            // ===========================
            $avatarFile = $request->file('avatar');

            if ($avatarFile) {
                if (!$avatarFile->isValid()) {
                    Log::warning('Avatar upload inválido', [
                        'error' => $avatarFile->getError(),
                        'error_message' => $avatarFile->getErrorMessage(),
                        'size' => $avatarFile->getSize(),
                    ]);
                } else {
                    if (!empty($estudiante->avatar) && !Str::startsWith($estudiante->avatar, ['http://', 'https://'])) {
                        Storage::disk('gcs')->delete($estudiante->avatar);
                    }

                    $path = Storage::disk('gcs')->putFile('avatars', $avatarFile);

                    Log::info('Avatar subido a GCS', [
                        'path' => $path,
                    ]);

                    $estudiante->avatar = $path;
                }
            }

            // ===========================
            // CV EN GCS
            // ===========================
            $cvFile = $request->file('cv');

            if ($cvFile) {
                if (!$cvFile->isValid()) {
                    Log::warning('CV upload inválido', [
                        'error' => $cvFile->getError(),
                        'error_message' => $cvFile->getErrorMessage(),
                        'size' => $cvFile->getSize(),
                    ]);
                } else {
                    if (!empty($estudiante->ruta_cv) && !Str::startsWith($estudiante->ruta_cv, ['http://', 'https://'])) {
                        Storage::disk('gcs')->delete($estudiante->ruta_cv);
                    }

                    $path = Storage::disk('gcs')->putFile('cv', $cvFile);

                    Log::info('CV subido a GCS', [
                        'path' => $path,
                    ]);

                    $estudiante->ruta_cv = $path;
                }
            }

            // GUARDAR CAMBIOS
            $estudiante->save();

            DB::commit();

            return redirect('/usuarios/perfil')
                ->with('success', 'Perfil actualizado correctamente.');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Error al actualizar perfil estudiante', [
                'error' => $e->getMessage(),
            ]);

            return redirect('/usuarios/editar')
                ->with('error', 'Ocurrió un error al actualizar el perfil.');
        }
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
