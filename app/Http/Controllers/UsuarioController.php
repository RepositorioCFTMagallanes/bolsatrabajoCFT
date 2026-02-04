<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Postulacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Services\OfertaRecommendationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    /**
     * PERFIL DEL POSTULANTE
     */
    public function perfil(OfertaRecommendationService $service)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId || !is_numeric($usuarioId)) {
            Log::warning('perfil(): sesión inválida', ['usuario_id' => $usuarioId]);
            return redirect()->route('login');
        }

        $usuarioId = (int) $usuarioId;

        // 🔒 Cargar estudiante + usuario (CLAVE)
        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$estudiante) {
            return redirect()->route('usuarios.editar')
                ->with('error', 'Debes completar tu perfil para continuar.');
        }

        // Postulaciones
        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        $totalPostulaciones = $postulaciones->count();
        $postulacionesEnAvance = $postulaciones
            ->where('estado_postulacion', '!=', 'Postulado')
            ->count();

        // Recomendadas
        $ofertasRecomendadas = $service->getRecomendadas($estudiante);
        $totalOfertasRecomendadas = $ofertasRecomendadas->count();

        // URLs públicas GCS
        $estudiante->avatar_url = $estudiante->avatar
            ? Storage::disk('gcs')->url($estudiante->avatar)
            : asset('img/testimonios/test (2).png');

        $estudiante->cv_url = $estudiante->ruta_cv
            ? Storage::disk('gcs')->url($estudiante->ruta_cv)
            : null;

        return view('users.perfil', compact(
            'estudiante',
            'postulaciones',
            'ofertasRecomendadas',
            'totalPostulaciones',
            'postulacionesEnAvance',
            'totalOfertasRecomendadas'
        ));
    }

    /**
     * FORMULARIO EDITAR PERFIL
     */
    public function editar()
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId || !is_numeric($usuarioId)) {
            Log::error('editar(): sesión inválida', ['usuario_id' => $usuarioId]);
            return redirect()->route('login');
        }

        $usuarioId = (int) $usuarioId;

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$estudiante) {
            $estudiante = new Estudiante();
            $estudiante->usuario_id = $usuarioId;
            $estudiante->save();
            $estudiante->load('usuario');
        }

        if (!$estudiante->usuario) {
            $usuario = Usuario::find($usuarioId);
            if (!$usuario) {
                return redirect()->route('login')
                    ->withErrors('No se pudo cargar el usuario.');
            }
            $estudiante->setRelation('usuario', $usuario);
        }

        // URLs públicas
        $estudiante->avatar_url = $estudiante->avatar
            ? Storage::disk('gcs')->url($estudiante->avatar)
            : asset('img/testimonios/test (2).png');

        $estudiante->cv_url = $estudiante->ruta_cv
            ? Storage::disk('gcs')->url($estudiante->ruta_cv)
            : null;

        return view('users.editar', compact('estudiante'));
    }

    /**
     * UPDATE PERFIL
     */
    public function update(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId || !is_numeric($usuarioId)) {
            return redirect()->route('login');
        }

        $usuarioId = (int) $usuarioId;

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$estudiante) {
            $estudiante = new Estudiante();
            $estudiante->usuario_id = $usuarioId;
            $estudiante->save();
            $estudiante->load('usuario');
        }

        $usuario = $estudiante->usuario ?: Usuario::find($usuarioId);
        if (!$usuario) {
            return redirect()->route('login');
        }

        $request->validate([
            'nombre'   => 'sometimes|nullable|string|max:150',
            'apellido' => 'sometimes|nullable|string|max:150',
            'email'    => 'sometimes|nullable|email|max:150',
            'run'      => 'sometimes|nullable|string|max:20',
            'telefono' => 'sometimes|nullable|string|max:50',
            'avatar'   => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv'       => 'sometimes|nullable|mimes:pdf|max:4096',
        ]);

        $usuario->update($request->only(['nombre', 'apellido', 'email']));

        $estudiante->fill($request->except([
            'nombre', 'apellido', 'email', 'avatar', 'cv'
        ]));

        // Avatar
        if ($request->hasFile('avatar')) {
            $path = 'estudiantes/avatars/' . $usuarioId . '_' . time() . '.' .
                $request->file('avatar')->getClientOriginalExtension();

            Storage::disk('gcs')->put($path, file_get_contents($request->file('avatar')), 'public');
            $estudiante->avatar = $path;
        }

        // CV
        if ($request->hasFile('cv')) {
            $path = 'estudiantes/cv/' . $usuarioId . '_' . time() . '.pdf';
            Storage::disk('gcs')->put($path, file_get_contents($request->file('cv')), 'public');
            $estudiante->ruta_cv = $path;
        }

        $estudiante->save();

        return redirect()->route('usuarios.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * POSTULACIONES
     */
    public function postulaciones()
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId || !is_numeric($usuarioId)) {
            return redirect()->route('login');
        }

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', (int)$usuarioId)
            ->first();

        if (!$estudiante) {
            return redirect()->route('usuarios.editar');
        }

        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('users.postulaciones', compact('postulaciones'));
    }
}
