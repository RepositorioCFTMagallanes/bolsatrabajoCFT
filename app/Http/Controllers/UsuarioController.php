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

        if (!$usuarioId || !is_numeric($usuarioId)) {
            return redirect()->route('login');
        }

        $estudiante = Estudiante::where('usuario_id', (int)$usuarioId)->first();

        // ✅ Igual que empresas: si no existe, manda a editar
        if (!$estudiante) {
            return redirect()->route('usuarios.editar')
                ->with('error', 'Debes completar tu perfil para continuar.');
        }

        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        $totalPostulaciones = $postulaciones->count();

        $postulacionesEnAvance = $postulaciones
            ->where('estado_postulacion', '!=', 'Postulado')
            ->count();

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

        if (!$usuarioId || !is_numeric($usuarioId)) {
            Log::error('Sesion invalida en editar perfil', [
                'usuario_id' => $usuarioId,
                'session' => session()->all(),
            ]);
            return redirect()->route('login');
        }

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', (int) $usuarioId)
            ->first();

        if (!$estudiante) {
            Log::error('Estudiante no encontrado en editar perfil', [
                'usuario_id' => (int) $usuarioId,
            ]);
            return redirect()->route('usuarios.perfil')
                ->withErrors('No se pudo cargar el perfil del usuario.');
        }

        if (!$estudiante->usuario) {
            Log::error('Usuario asociado no encontrado en editar perfil', [
                'usuario_id' => (int) $usuarioId,
            ]);
            return redirect()->route('usuarios.perfil')
                ->withErrors('No se pudo cargar el usuario del perfil.');
        }

        return view('users.editar', compact('estudiante'));
    }


    public function update(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId || !is_numeric($usuarioId)) {
            Log::error('Sesion invalida en update perfil', [
                'usuario_id' => $usuarioId,
                'session' => session()->all(),
            ]);
            return redirect()->route('login');
        }

        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', (int)$usuarioId)
            ->first();

        if (!$estudiante || !$estudiante->usuario) {
            Log::error('Estudiante/Usuario no encontrado en update perfil', [
                'usuario_id' => (int)$usuarioId,
            ]);
            return redirect()->route('usuarios.perfil')
                ->withErrors('No se pudo actualizar el perfil.');
        }

        // ✅ NO obligatorios: "sometimes" valida solo si viene el campo
        $request->validate([
            'nombre'   => 'sometimes|nullable|string|max:150',
            'apellido' => 'sometimes|nullable|string|max:150',
            'email'    => 'sometimes|nullable|email|max:150',

            'run'         => 'sometimes|nullable|string|max:20',
            'estado'      => 'sometimes|nullable|string|max:50',
            'titulo'      => 'sometimes|nullable|string|max:255',
            'telefono'    => 'sometimes|nullable|string|max:50',
            'ciudad'      => 'sometimes|nullable|string|max:150',
            'resumen'     => 'sometimes|nullable|string|max:800',
            'institucion' => 'sometimes|nullable|string|max:255',
            'anio_egreso' => 'sometimes|nullable|integer|min:1990|max:2099',
            'cursos'      => 'sometimes|nullable|string',

            'linkedin'  => 'sometimes|nullable|url|max:255',
            'portfolio' => 'sometimes|nullable|url|max:255',

            // ✅ Evita FK violation
            'area'      => 'sometimes|nullable|integer|exists:areas_empleo,id',
            'jornada'   => 'sometimes|nullable|integer|exists:jornadas,id',
            'modalidad' => 'sometimes|nullable|integer|exists:modalidades,id',

            'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv'     => 'sometimes|nullable|mimes:pdf|max:4096',
        ]);

        $usuario = $estudiante->usuario;

        // ✅ Si no mandan nombre/apellido/email, no los tocamos
        $usuarioData = [];
        if ($request->filled('nombre'))   $usuarioData['nombre']   = $request->nombre;
        if ($request->filled('apellido')) $usuarioData['apellido'] = $request->apellido;
        if ($request->filled('email'))    $usuarioData['email']    = $request->email;

        if (!empty($usuarioData)) {
            $usuario->update($usuarioData);
        }

        // ✅ Normaliza "" -> null (para selects vacíos)
        $area      = $request->filled('area') ? (int)$request->area : null;
        $jornada   = $request->filled('jornada') ? (int)$request->jornada : null;
        $modalidad = $request->filled('modalidad') ? (int)$request->modalidad : null;

        $estudiante->fill([
            'run'                      => $request->input('run'),
            'estado_carrera'           => $request->input('estado'),
            'carrera'                  => $request->input('titulo'),
            'telefono'                 => $request->input('telefono'),
            'ciudad'                   => $request->input('ciudad'),
            'resumen'                  => $request->input('resumen'),
            'institucion'              => $request->input('institucion'),
            'anio_egreso'              => $request->input('anio_egreso'),
            'cursos'                   => $request->input('cursos'),
            'linkedin_url'             => $request->input('linkedin'),
            'portfolio_url'            => $request->input('portfolio'),
            'area_interes_id'          => $area,
            'jornada_preferencia_id'   => $jornada,
            'modalidad_preferencia_id' => $modalidad,
        ]);

        // ===========================
        // AVATAR
        // ===========================
        try {
            if ($request->hasFile('avatar')) {
                if ($estudiante->avatar) {
                    $ruta = public_path($estudiante->avatar);
                    if (is_file($ruta)) @unlink($ruta);
                }

                $destino = public_path('uploads/avatars');
                if (!is_dir($destino)) mkdir($destino, 0775, true);

                $archivo = $request->file('avatar');
                $nombre = uniqid('avatar_') . '.' . $archivo->getClientOriginalExtension();
                $archivo->move($destino, $nombre);

                $estudiante->avatar = 'uploads/avatars/' . $nombre;
            }
        } catch (\Throwable $e) {
            Log::error('Error subiendo avatar', ['error' => $e->getMessage()]);
            return back()->withErrors('Error al subir la foto de perfil');
        }

        // ===========================
        // CV
        // ===========================
        try {
            if ($request->hasFile('cv')) {
                if ($estudiante->ruta_cv) {
                    $ruta = public_path($estudiante->ruta_cv);
                    if (is_file($ruta)) @unlink($ruta);
                }

                $destino = public_path('uploads/cv');
                if (!is_dir($destino)) mkdir($destino, 0775, true);

                $archivo = $request->file('cv');
                $nombre = time() . '_' . preg_replace('/\s+/', '_', $archivo->getClientOriginalName());
                $archivo->move($destino, $nombre);

                $estudiante->ruta_cv = 'uploads/cv/' . $nombre;
            }
        } catch (\Throwable $e) {
            Log::error('Error subiendo CV', ['error' => $e->getMessage()]);
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
