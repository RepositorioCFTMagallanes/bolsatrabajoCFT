<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\OfertaTrabajo;
use App\Models\Postulacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostulacionConfirmadaMail;



class PostulacionController extends Controller
{
    /**
     * Registrar una nueva postulación
     */
    public function store(Request $request, $id)
    {
        // 1. Obtener el usuario logueado (CORREGIDO)
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // 2. Obtener el estudiante asociado al usuario
        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        if (!$estudiante) {
            return back()->with('error', 'Debes completar tu perfil de estudiante antes de postular.');
        }

        // 3. Verificar que la oferta exista
        $oferta = OfertaTrabajo::find($id);

        if (!$oferta) {
            return back()->with('error', 'La oferta de trabajo no existe.');
        }

        // 4. Validar que la oferta esté publicada (1 = publicada)
        if ($oferta->estado != 1) {
            return back()->with('error', 'Esta oferta no está disponible para postulación.');
        }


        // Validar fecha de cierre
        if ($oferta->fecha_cierre && $oferta->fecha_cierre < now()) {
            return back()->with('error', 'La oferta ya cerró su proceso de postulación.');
        }

        // 5. Evitar postulaciones duplicadas
        $yaExiste = Postulacion::where('estudiante_id', $estudiante->id)
            ->where('oferta_id', $id)
            ->exists();

        if ($yaExiste) {
            return back()->with('error', 'Ya postulaste a esta oferta.');
        }

        // 6. Crear la nueva postulación
        $postulacion = Postulacion::create([
            'estudiante_id'      => $estudiante->id,
            'oferta_id'          => $id,
            'estado_postulacion' => 'pendiente',
            'fecha_postulacion'  => now(),
            'creado_en'          => now(),
            'actualizado_en'     => now(),
        ]);
        // ============================================================
        // 7. CORREOS AUTOMÁTICOS
        // ============================================================

        // Usuario estudiante
        $usuarioEstudiante = Usuario::find($usuarioId);

        // Correo al ESTUDIANTE
        Mail::to($usuarioEstudiante->email)
            ->send(new PostulacionConfirmadaMail(
                $usuarioEstudiante->nombre,
                $oferta->titulo
            ));

        // Correo a la EMPRESA (lo activamos después)
        // if ($oferta->empresa && $oferta->empresa->correo_contacto) {
        //     Mail::to($oferta->empresa->correo_contacto)
        //         ->send(new NuevaPostulacionEmpresaMail(
        //             $usuarioEstudiante->nombre . ' ' . $usuarioEstudiante->apellido,
        //             $oferta->titulo
        //         ));
        // }

        // 8. Devolver mensaje
        return back()->with('success', '¡Tu postulación fue enviada exitosamente!');
    }


    /**
     * Mostrar las postulaciones del estudiante
     */
    public function index(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        if (!$estudiante) {
            return back()->with('error', 'No se encontró tu perfil de estudiante.');
        }

        // 👉 filtros
        $estado = $request->get('estado');
        $orden  = $request->get('orden', 'recientes');
        $q      = $request->get('q');

        $postulaciones = Postulacion::with(['oferta.empresa'])
            ->where('estudiante_id', $estudiante->id);

        // FILTRO POR ESTADO
        if ($estado) {
            $postulaciones->where('estado_postulacion', $estado);
        }

        // BUSCADOR
        if ($q) {
            $postulaciones->whereHas('oferta', function ($query) use ($q) {
                $query->where('titulo', 'like', "%{$q}%")
                    ->orWhere('ciudad', 'like', "%{$q}%")
                    ->orWhereHas('empresa', function ($q2) use ($q) {
                        $q2->where('nombre_comercial', 'like', "%{$q}%");
                    });
            });
        }

        // ORDEN
        if ($orden === 'antiguas') {
            $postulaciones->orderBy('fecha_postulacion', 'asc');
        } else {
            $postulaciones->orderBy('fecha_postulacion', 'desc');
        }

        $postulaciones = $postulaciones->get();

        return view('users.mis-postulaciones', compact('postulaciones'));
    }


    /**
     * Mostrar detalle de una postulación específica
     */
    public function show($id)
    {
        // 1. Usuario logueado
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // 2. Obtener estudiante
        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        if (!$estudiante) {
            return back()->with('error', 'No se encontró tu perfil de estudiante.');
        }

        // 3. Obtener la postulación
        $postulacion = Postulacion::with(['oferta.empresa'])
            ->where('id', $id)
            ->where('estudiante_id', $estudiante->id)
            ->first();

        if (!$postulacion) {
            return back()->with('error', 'No se encontró esta postulación.');
        }

        return view('users.detalle-postulacion', compact('postulacion'));
    }
    public function modal($id)
    {
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return response()->json(['error' => 'No autenticado'], 403);
        }

        $estudiante = Estudiante::where('usuario_id', $usuarioId)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Perfil no encontrado'], 404);
        }

        $postulacion = Postulacion::with(['oferta.empresa'])
            ->where('id', $id)
            ->where('estudiante_id', $estudiante->id)
            ->first();

        if (!$postulacion) {
            return response()->json(['error' => 'Postulación no encontrada'], 404);
        }

        $html = view('partials.modal-postulacion', compact('postulacion'))->render();

        return response()->json(['html' => $html]);
    }
    public function retirar($id)
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        $estudiante = \App\Models\Estudiante::where('usuario_id', $usuarioId)->first();
        if (!$estudiante) {
            return back()->with('error', 'No se encontró tu perfil de estudiante.');
        }

        $postulacion = \App\Models\Postulacion::where('id', $id)
            ->where('estudiante_id', $estudiante->id)
            ->first();

        if (!$postulacion) {
            return back()->with('error', 'Postulación no encontrada.');
        }

        // Cambiar estado (recomendado: NO borrar)
        $postulacion->estado_postulacion = 'retirada';
        $postulacion->actualizado_en = now();
        $postulacion->save();

        return back()->with('success', 'Postulación retirada correctamente.');
    }
}
