<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\OfertaTrabajo;
use Illuminate\Support\Facades\Storage;
use App\Models\Estudiante;
use App\Models\Postulacion;
use App\Services\BrevoMailService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class EmpresaController extends Controller
{
    /**
     * Muestra el perfil de la empresa logueada.
     * Más adelante cargaremos datos reales desde la BD.
     */
    public function perfil()
    {
        $usuarioId = session('usuario_id');

        // Obtener empresa del usuario
        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        if (!$empresa) {
            return redirect()->route('empresas.editar')
                ->with('error', 'Debe completar su perfil de empresa.');
        }

        // === Ofertas activas (máximo 4) ===
        $ofertas = OfertaTrabajo::where('empresa_id', $empresa->id)
            ->where('estado', \App\Models\OfertaTrabajo::ESTADO_APROBADA)
            ->orderBy('creado_en', 'desc')
            ->take(4)
            ->get();

        $totalOfertas = OfertaTrabajo::where('empresa_id', $empresa->id)
            ->where('estado', \App\Models\OfertaTrabajo::ESTADO_APROBADA)
            ->count();

        // === Postulaciones recientes ===
        $postulaciones = \App\Models\Postulacion::with(['estudiante.usuario', 'oferta'])
            ->whereHas('oferta', function ($q) use ($empresa) {
                $q->where('empresa_id', $empresa->id);
            })
            ->orderBy('fecha_postulacion', 'desc')
            ->take(3)
            ->get();

        $totalPostulaciones = \App\Models\Postulacion::whereHas('oferta', function ($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id);
        })->count();

        return view('empresas.perfil', compact(
            'empresa',
            'ofertas',
            'totalOfertas',
            'postulaciones',
            'totalPostulaciones'
        ));
    }


    /**
     * Formulario para editar el perfil de la empresa.
     */
    public function editar()
    {
        $usuarioId = session('usuario_id');
        $empresa   = Empresa::where('usuario_id', $usuarioId)->first();

        return view('empresas.editar', [
            'empresa' => $empresa,
        ]);
    }

    /**
     * Actualiza datos básicos del perfil.
     * De momento solo validamos campos simples y redirigimos.
     * Más adelante definimos exactamente qué campos se pueden editar.
     */
    public function updatePerfil(Request $request)
    {
        $usuarioId = session('usuario_id');

        $request->validate([
            'nombre_comercial' => 'nullable|string|max:150',
            'rut'              => 'nullable|string|max:20',
            'correo_contacto'  => 'nullable|email|max:150',
            'telefono_contacto' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $empresa = Empresa::firstOrCreate(
            ['usuario_id' => $usuarioId],
            [
                'nombre_comercial'  => $request->input('nombre_comercial', 'Sin nombre'),
                'rut'               => $request->input('rut'),
                'correo_contacto'   => $request->input('correo_contacto', session('usuario_email')),
                'telefono_contacto' => $request->input('telefono_contacto', 'No informado'),
            ]
        );

        // Actualizar si ya existe
        $empresa->update($request->only([
            'nombre_comercial',
            'rut',
            'correo_contacto',
            'telefono_contacto',
        ]));
        // ===========================
        // LOGO EMPRESA (Cloudinary)
        // ===========================
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');

            if ($logoFile->isValid()) {

                $result = Cloudinary::upload(
                    $logoFile->getRealPath()
                );

                $url = $result->getSecurePath();

                if (!$url) {
                    throw new \Exception('Cloudinary no devolvió URL de logo');
                }

                $empresa->ruta_logo = $url;
                $empresa->save();
            }
        }



        return redirect()->route('empresas.perfil')
            ->with('ok', 'Perfil de empresa actualizado correctamente.');
    }

    /**
     * Formulario para crear una nueva oferta laboral.
     */
    public function crearOferta()
    {
        return view('empresas.crear_oferta', [
            'areas'         => \App\Models\AreaEmpleo::orderBy('nombre')->get(),
            'tiposContrato' => \App\Models\TipoContrato::orderBy('nombre')->get(),
            'modalidades'   => \App\Models\Modalidad::orderBy('nombre')->get(),
            'jornadas'      => \App\Models\Jornada::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Guarda una nueva oferta laboral.
     * Por ahora es un stub: solo muestra mensaje de éxito.
     * Más adelante lo conectamos a la tabla ofertas_trabajo.
     */
    public function storeOferta(Request $request)
    {
        $usuarioId = session('usuario_id');

        // Buscar empresa asociada al usuario
        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        if (!$empresa) {
            return redirect()->route('empresas.perfil')
                ->with('error', 'Debe completar su perfil de empresa antes de publicar ofertas.');
        }

        // Validación de campos obligatorios
        $request->validate([
            'titulo'             => 'required|string|max:255',
            'area_id'            => 'required|integer',
            'tipo_contrato_id'   => 'required|integer',
            'modalidad_id'       => 'required|integer',
            'descripcion'        => 'required|string',
            'requisitos'         => 'required|string',
            'nombre_contacto'    => 'required|string|max:150',
            'correo_contacto'    => 'required|email|max:150',
        ]);

        /* -----------------------------
       1) Guardar archivo adjunto
    ------------------------------ */
        $rutaArchivo = null;

        if ($request->hasFile('ruta_archivo')) {
            $rutaArchivo = $request->file('ruta_archivo')->store('ofertas', 'public');
        }

        /* -----------------------------
       2) Crear la oferta en BD
    ------------------------------ */
        $oferta = OfertaTrabajo::create([
            'empresa_id'         => $empresa->id,
            'titulo'             => $request->titulo,
            'area_id'            => $request->area_id,
            'tipo_contrato_id'   => $request->tipo_contrato_id,
            'modalidad_id'       => $request->modalidad_id,
            'jornada_id'         => $request->jornada_id,
            'vacantes'           => $request->vacantes,
            'region'             => $request->region,
            'ciudad'             => $request->ciudad,
            'direccion'          => $request->direccion,
            'sueldo_min'         => $request->sueldo_min,
            'sueldo_max'         => $request->sueldo_max,
            'mostrar_sueldo'     => $request->mostrar_sueldo,
            'beneficios'         => $request->beneficios,
            'requisitos'         => $request->requisitos,
            'descripcion'        => $request->descripcion,
            'habilidades_deseadas' => $request->habilidades_deseadas,
            'ruta_archivo'       => $rutaArchivo,
            'nombre_contacto'    => $request->nombre_contacto,
            'correo_contacto'    => $request->correo_contacto,
            'telefono_contacto'  => $request->telefono_contacto,
            'fecha_cierre'       => $request->fecha_cierre,
            'estado' => \App\Models\OfertaTrabajo::ESTADO_PENDIENTE,
        ]);
        // ================================
        // 📧 CORREO A ADMIN – NUEVA OFERTA
        // ================================
        BrevoMailService::send(
            config('mail.from.address'), // o correo del admin
            'Nueva oferta pendiente de aprobación',
            view('emails.nueva-oferta-admin', [
                'empresa' => $empresa->nombre_comercial ?? 'Empresa',
                'oferta'  => $oferta->titulo,
            ])->render()
        );

        /* -----------------------------
       3) Redirección correcta
    ------------------------------ */
        return redirect()
            ->route('empresas.ofertas.index')
            ->with('ok', 'Oferta creada correctamente.');
    }

    /**
     * Actualiza una oferta laboral existente.
     */
    public function updateOferta(Request $request, $id)
    {
        $usuarioId = session('usuario_id');

        // Obtener empresa del usuario
        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        if (!$empresa) {
            return redirect()->route('empresas.perfil')
                ->with('error', 'Debe completar su perfil de empresa antes de editar ofertas.');
        }

        // Obtener oferta que pertenece a la empresa
        $oferta = OfertaTrabajo::where('empresa_id', $empresa->id)->findOrFail($id);

        /* -----------------------------
           Validación
        ------------------------------ */
        $request->validate([
            'titulo'             => 'required|string|max:255',
            'area_id'            => 'required|integer',
            'tipo_contrato_id'   => 'required|integer',
            'modalidad_id'       => 'required|integer',
            'descripcion'        => 'required|string',
            'requisitos'         => 'required|string',
            'nombre_contacto'    => 'required|string|max:150',
            'correo_contacto'    => 'required|email|max:150',
        ]);

        /* -----------------------------
           Si hay archivo nuevo → reemplazar
        ------------------------------ */
        if ($request->hasFile('ruta_archivo')) {

            // borrar archivo anterior si existe
            if ($oferta->ruta_archivo && Storage::disk('public')->exists($oferta->ruta_archivo)) {
                Storage::disk('public')->delete($oferta->ruta_archivo);
            }

            // guardar nuevo archivo
            $rutaArchivo = $request->file('ruta_archivo')->store('ofertas', 'public');
            $oferta->ruta_archivo = $rutaArchivo;
        }

        /* -----------------------------
           Actualizar oferta
        ------------------------------ */
        $oferta->update([
            'titulo'             => $request->titulo,
            'area_id'            => $request->area_id,
            'tipo_contrato_id'   => $request->tipo_contrato_id,
            'modalidad_id'       => $request->modalidad_id,
            'jornada_id'         => $request->jornada_id,
            'vacantes'           => $request->vacantes,
            'region'             => $request->region,
            'ciudad'             => $request->ciudad,
            'direccion'          => $request->direccion,
            'sueldo_min'         => $request->sueldo_min,
            'sueldo_max'         => $request->sueldo_max,
            'mostrar_sueldo'     => $request->mostrar_sueldo,
            'beneficios'         => $request->beneficios,
            'requisitos'         => $request->requisitos,
            'descripcion'        => $request->descripcion,
            'habilidades_deseadas' => $request->habilidades_deseadas,
            'nombre_contacto'    => $request->nombre_contacto,
            'correo_contacto'    => $request->correo_contacto,
            'telefono_contacto'  => $request->telefono_contacto,
            'fecha_cierre'       => $request->fecha_cierre,
        ]);

        return redirect()
            ->route('empresas.ofertas.index')
            ->with('ok', 'Oferta actualizada correctamente.');
    }

    /**
     * Formulario para editar una oferta específica.
     */
    public function editarOferta($id)
    {
        $usuarioId = session('usuario_id');

        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        if (!$empresa) {
            return redirect()->route('empresas.perfil')
                ->with('error', 'Debe completar su perfil de empresa antes de editar ofertas.');
        }

        $oferta = OfertaTrabajo::where('empresa_id', $empresa->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('empresas.ofertas.editar_oferta', [
            'empresa'       => $empresa,
            'oferta'        => $oferta,
            'areas'         => \App\Models\AreaEmpleo::orderBy('nombre')->get(),
            'tiposContrato' => \App\Models\TipoContrato::orderBy('nombre')->get(),
            'modalidades'   => \App\Models\Modalidad::orderBy('nombre')->get(),
            'jornadas'      => \App\Models\Jornada::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Lista todas las ofertas laborales creadas por la empresa logueada.
     */
    public function misOfertas()
    {
        $usuarioId = session('usuario_id');

        // Obtener empresa asociada al usuario
        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        // Si no tiene empresa registrada, no debería estar aquí
        if (!$empresa) {
            return redirect()->route('empresas.perfil')
                ->with('error', 'No se encontró el perfil de la empresa.');
        }

        // Cargar todas las ofertas de esta empresa
        $ofertas = $empresa->ofertas()->orderBy('creado_en', 'desc')->get();

        return view('empresas.ofertas.index', [
            'empresa' => $empresa,
            'ofertas' => $ofertas,
        ]);
    }
    /**
     * Elimina una oferta laboral perteneciente a la empresa.
     */
    public function destroyOferta($id)
    {
        $usuarioId = session('usuario_id');

        // Obtener empresa del usuario
        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        if (!$empresa) {
            return redirect()->route('empresas.perfil')
                ->with('error', 'Debe completar su perfil antes de realizar esta acción.');
        }

        // Validar que la oferta pertenezca a esta empresa
        $oferta = OfertaTrabajo::where('empresa_id', $empresa->id)
            ->where('id', $id)
            ->firstOrFail();

        // Eliminar archivo adjunto si existe
        if ($oferta->ruta_archivo && Storage::disk('public')->exists($oferta->ruta_archivo)) {
            Storage::disk('public')->delete($oferta->ruta_archivo);
        }

        // Eliminar oferta
        $oferta->delete();

        return redirect()
            ->route('empresas.ofertas.index')
            ->with('ok', 'La oferta fue eliminada correctamente.');
    }
    public function verPostulaciones()
    {
        $usuarioId = session('usuario_id');
        $empresa = Empresa::where('usuario_id', $usuarioId)->first();

        if (!$empresa) {
            return redirect()->route('empresas.perfil')
                ->with('error', 'Debe completar su perfil de empresa.');
        }

        $postulaciones = \App\Models\Postulacion::with(['estudiante.usuario', 'oferta'])
            ->whereHas('oferta', function ($q) use ($empresa) {
                $q->where('empresa_id', $empresa->id);
            })
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('empresas.postulaciones.index', compact('empresa', 'postulaciones'));
    }
    public function verPostulante($id)
    {
        $usuarioId = session('usuario_id');

        // Empresa logueada
        $empresa = Empresa::where('usuario_id', $usuarioId)->firstOrFail();

        // Estudiante
        $estudiante = Estudiante::with('usuario')->findOrFail($id);

        // SOLO postulaciones del estudiante a ofertas de ESTA empresa
        $postulaciones = Postulacion::with('oferta')
            ->where('estudiante_id', $id)
            ->whereHas('oferta', function ($q) use ($empresa) {
                $q->where('empresa_id', $empresa->id);
            })
            ->orderBy('fecha_postulacion', 'desc')
            ->get();

        return view('empresas.postulaciones.ver', compact(
            'estudiante',
            'postulaciones'
        ));
    }

    public function enviarRevision($id)
    {
        $usuarioId = session('usuario_id');
        $empresa = Empresa::where('usuario_id', $usuarioId)->firstOrFail();

        $oferta = OfertaTrabajo::where('empresa_id', $empresa->id)
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array((int)$oferta->estado, [
            OfertaTrabajo::ESTADO_RECHAZADA,
            OfertaTrabajo::ESTADO_REENVIADA,
        ])) {
            return back()->with('error', 'Esta oferta no puede enviarse a revisión.');
        }

        $oferta->estado = OfertaTrabajo::ESTADO_REENVIADA;
        $oferta->save();

        return redirect()
            ->route('empresas.ofertas.index')
            ->with('success', 'Tu oferta fue enviada a revisión nuevamente.');
    }

    public function finalizarOferta($id)
    {
        $usuarioId = session('usuario_id');
        $empresa = Empresa::where('usuario_id', $usuarioId)->firstOrFail();

        $oferta = OfertaTrabajo::where('empresa_id', $empresa->id)
            ->where('id', $id)
            ->firstOrFail();

        // Solo finalizar si está aprobada
        if ((int)$oferta->estado !== OfertaTrabajo::ESTADO_APROBADA) {
            return back()->with('error', 'Solo puedes finalizar ofertas publicadas.');
        }

        $oferta->estado = OfertaTrabajo::ESTADO_FINALIZADA;
        $oferta->fecha_cierre = now();
        $oferta->save();

        return redirect()
            ->route('empresas.ofertas.index')
            ->with('ok', 'Oferta finalizada correctamente.');
    }
}
