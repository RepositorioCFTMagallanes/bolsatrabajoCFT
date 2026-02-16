<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class RecursoController extends Controller
{
    /**
     * Mostrar listado de recursos (vista admin)
     */
    public function index()
    {
        $recursos = Recurso::orderBy('creado_en', 'desc')->get();
        return view('admin.recursos.index', compact('recursos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.recursos.create');
    }

    /**
     * Guardar recurso nuevo
     */
    public function store(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'titulo'   => 'required|string|max:255',
            'resumen'  => 'nullable|string|max:250',
            'contenido' => 'nullable|string',
            'imagen'   => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'estado'   => 'required|boolean',
        ]);

        // NUEVO RECURSO
        $recurso = new Recurso();
        $recurso->titulo     = $request->titulo;
        $recurso->resumen    = $request->resumen;
        $recurso->contenido  = $request->contenido;
        $recurso->estado     = $request->estado;
        $recurso->creado_en  = now();
        $recurso->actualizado_en = now();

        // GUARDAR IMAGEN EN CLOUDINARY (si existe)
        if ($request->hasFile('imagen')) {

            $upload = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                [
                    'folder' => 'recursos_empleabilidad'
                ]
            );

            $recurso->imagen = $upload->getSecurePath();
        }


        // GUARDAR EN DB
        $recurso->save();

        return redirect()->route('admin.recursos.index')
            ->with('success', 'Recurso creado correctamente.');
    }


    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $recurso = Recurso::findOrFail($id);
        return view('admin.recursos.edit', compact('recurso'));
    }

    /**
     * Actualizar recurso
     */
    public function update(Request $request, $id)
    {
        // VALIDACIONES
        $request->validate([
            'titulo'   => 'required|string|max:255',
            'resumen'  => 'nullable|string|max:250',
            'contenido' => 'nullable|string',
            'imagen'   => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'estado'   => 'required|boolean',
        ]);

        $recurso = Recurso::findOrFail($id);

        // ACTUALIZAR CAMPOS
        $recurso->titulo        = $request->titulo;
        $recurso->resumen       = $request->resumen;
        $recurso->contenido     = $request->contenido;
        $recurso->estado        = $request->estado;
        $recurso->actualizado_en = now();

        // SI SUBE UNA NUEVA IMAGEN, SE REEMPLAZA EN CLOUDINARY
        if ($request->hasFile('imagen')) {

            $upload = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                [
                    'folder' => 'recursos_empleabilidad'
                ]
            );

            $recurso->imagen = $upload->getSecurePath();
        }


        // GUARDAR
        $recurso->save();

        return redirect()->route('admin.recursos.index')
            ->with('success', 'Recurso actualizado correctamente.');
    }


    /**
     * Eliminar recurso (soft delete)
     */
    public function destroy($id)
    {
        $recurso = Recurso::findOrFail($id);
        $recurso->delete();

        return redirect()->route('admin.recursos.index')
            ->with('success', 'Recurso eliminado correctamente');
    }

    /**
     * Cambiar estado Publicado / No publicado
     */
    public function toggleStatus($id)
    {
        $recurso = Recurso::findOrFail($id);
        $recurso->estado = !$recurso->estado;
        $recurso->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
