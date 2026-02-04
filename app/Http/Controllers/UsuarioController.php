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

        // Cargar estudiante
        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        // ✅ Si no existe estudiante, NO hacemos loop: lo mandamos a editar
        // (editar() se encarga de crear el registro si no existe)
        if (!$estudiante) {
            return redirect()->route('usuarios.editar')
                ->with('error', 'Debes completar tu perfil para continuar.');
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

        // Recomendadas
        $ofertasRecomendadas = $service->getRecomendadas($estudiante);
        $totalOfertasRecomendadas = $ofertasRecomendadas->count();

        // ===========================
        // Resolver URLs públicas (GCS)
        // ===========================
        $estudiante->avatar_url = $estudiante->avatar
            ? Storage::disk('gcs')->url($estudiante->avatar)
            : asset('img/testimonios/test (2).png');

        $estudiante->cv_url = $estudiante->ruta_cv
            ? Storage::disk('gcs')->url($estudiante->ruta_cv)
            : null;


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
            Log::error('editar(): sesión inválida', [
                'usuario_id' => $usuarioId,
                'session' => session()->all(),
            ]);
            return redirect()->route('login');
        }

        $usuarioId = (int) $usuarioId;

        // ✅ Intentar cargar estudiante con usuario
        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        // ✅ Si NO existe, lo creamos (evita loop + deja el sistema estable)
        if (!$estudiante) {
            Log::warning('editar(): estudiante no existe, creando vacío', ['usuario_id' => $usuarioId]);

            $estudiante = new Estudiante();
            $estudiante->usuario_id = $usuarioId;

            // Si tienes defaults/constraints en DB, acá puedes setearlos:
            // $estudiante->visibilidad = $estudiante->visibilidad ?? 'publico';
            // $estudiante->frecuencia_alertas = $estudiante->frecuencia_alertas ?? 'diario';

            $estudiante->save();

            // recarga con relación
            $estudiante->load('usuario');
        }

        // ✅ Asegurar usuario asociado (sin loops)
        if (!$estudiante->usuario) {
            // Intentar recuperar usuario directo (por si el with() falló o la relación no se resolvió)
            $usuario = Usuario::find($usuarioId);

            if (!$usuario) {
                Log::error('editar(): usuario asociado no existe', ['usuario_id' => $usuarioId]);
                return redirect()->route('login')
                    ->withErrors('No se pudo cargar el usuario. Vuelve a iniciar sesión.');
            }

            // Si la relación existe, esto ya queda bien al volver a cargar vista
            // (No es necesario guardar nada aquí)
            $estudiante->setRelation('usuario', $usuario);
        }
        // ===========================
        // Resolver URLs públicas (GCS) para la vista editar
        // ===========================
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
            Log::error('update(): sesión inválida', [
                'usuario_id' => $usuarioId,
                'session' => session()->all(),
            ]);
            return redirect()->route('login');
        }

        $usuarioId = (int) $usuarioId;

        // Cargar estudiante + usuario
        $estudiante = Estudiante::with('usuario')
            ->where('usuario_id', $usuarioId)
            ->first();

        // ✅ Si no existe estudiante, lo creamos (blindaje total)
        if (!$estudiante) {
            Log::warning('update(): estudiante no existe, creando vacío', ['usuario_id' => $usuarioId]);
            $estudiante = new Estudiante();
            $estudiante->usuario_id = $usuarioId;
            $estudiante->save();
            $estudiante->load('usuario');
        }

        // ✅ Usuario asociado
        $usuario = $estudiante->usuario ?: Usuario::find($usuarioId);

        if (!$usuario) {
            Log::error('update(): usuario no encontrado', ['usuario_id' => $usuarioId]);
            return redirect()->route('login')
                ->withErrors('No se pudo actualizar el perfil. Inicia sesión nuevamente.');
        }

        // ✅ Validación NO obligatoria
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

            // ✅ Evita FK violations (y deja null si viene vacío)
            'area'      => 'sometimes|nullable|integer|exists:areas_empleo,id',
            'jornada'   => 'sometimes|nullable|integer|exists:jornadas,id',
            'modalidad' => 'sometimes|nullable|integer|exists:modalidades,id',

            'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv'     => 'sometimes|nullable|mimes:pdf|max:4096',
        ]);

        // ===========================
        // USUARIO: actualizar solo si viene
        // ===========================
        $usuarioData = [];

        if ($request->has('nombre'))   $usuarioData['nombre']   = $request->input('nombre');
        if ($request->has('apellido')) $usuarioData['apellido'] = $request->input('apellido');
        if ($request->has('email'))    $usuarioData['email']    = $request->input('email');

        // OJO: has() permite setear null si envías vacío; si quieres NO tocar cuando viene vacío,
        // cambia a filled(). Pero tú pediste no obligatorios, así que esto está bien.
        if (!empty($usuarioData)) {
            $usuario->update($usuarioData);
        }

        // ===========================
        // ESTUDIANTE
        // ===========================
        $area      = $request->filled('area') ? (int) $request->input('area') : null;
        $jornada   = $request->filled('jornada') ? (int) $request->input('jornada') : null;
        $modalidad = $request->filled('modalidad') ? (int) $request->input('modalidad') : null;

        $estudiante->fill([
            'run'                       => $request->input('run'),
            'estado_carrera'            => $request->input('estado'),
            'carrera'                   => $request->input('titulo'),
            'telefono'                  => $request->input('telefono'),
            'ciudad'                    => $request->input('ciudad'),
            'resumen'                   => $request->input('resumen'),
            'institucion'               => $request->input('institucion'),
            'anio_egreso'               => $request->input('anio_egreso'),
            'cursos'                    => $request->input('cursos'),
            'linkedin_url'              => $request->input('linkedin'),
            'portfolio_url'             => $request->input('portfolio'),
            'area_interes_id'           => $area,
            'jornada_preferencia_id'    => $jornada,
            'modalidad_preferencia_id'  => $modalidad,
        ]);

        // Si tienes defaults por constraints (si aplican en tu DB), descomenta:
        // $estudiante->visibilidad = $estudiante->visibilidad ?? 'publico';
        // $estudiante->frecuencia_alertas = $estudiante->frecuencia_alertas ?? 'diario';

        // ===========================
        // AVATAR (Google Cloud Storage)
        // ===========================
        try {
            if ($request->hasFile('avatar')) {

                $archivo = $request->file('avatar');

                $nombre = 'estudiantes/avatars/'
                    . $usuarioId
                    . '_'
                    . time()
                    . '.'
                    . $archivo->getClientOriginalExtension();

                // Subir a GCS
                Storage::disk('gcs')->put(
                    $nombre,
                    file_get_contents($archivo),
                    'public'
                );

                // Guardar SOLO el path en BD
                $estudiante->avatar = $nombre;
            }
        } catch (\Throwable $e) {
            Log::error('Error subiendo avatar a GCS', ['error' => $e->getMessage()]);
            return back()->withErrors('Error al subir la foto de perfil');
        }


        // ===========================
        // CV (Google Cloud Storage)
        // ===========================
        try {
            if ($request->hasFile('cv')) {

                $archivo = $request->file('cv');

                $nombre = 'estudiantes/cv/'
                    . $usuarioId
                    . '_'
                    . time()
                    . '.pdf';

                Storage::disk('gcs')->put(
                    $nombre,
                    file_get_contents($archivo),
                    'public'
                );

                // Guardar SOLO el path en BD
                $estudiante->ruta_cv = $nombre;
            }
        } catch (\Throwable $e) {
            Log::error('Error subiendo CV a GCS', ['error' => $e->getMessage()]);
            return back()->withErrors('Error al subir el CV');
        }


        $estudiante->save();

        return redirect()->route('usuarios.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * LISTA DE POSTULACIONES DEL USUARIO
     */
    public function postulaciones()
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId || !is_numeric($usuarioId)) {
            return redirect()->route('login');
        }

        $usuarioId = (int) $usuarioId;

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        // Si no existe estudiante, enviarlo a editar (sin loop)
        if (!$estudiante) {
            return redirect()->route('usuarios.editar')
                ->with('error', 'Debes completar tu perfil para continuar.');
        }

        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('users.postulaciones', compact('postulaciones'));
    }
}
