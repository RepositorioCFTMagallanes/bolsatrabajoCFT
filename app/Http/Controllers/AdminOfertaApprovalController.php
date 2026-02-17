<?php

namespace App\Http\Controllers;

use App\Models\OfertaTrabajo;
use Illuminate\Http\Request;
use App\Services\AlertMessageService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OfertaAprobadaEmpresaMail;
use App\Mail\OfertaRechazadaEmpresaMail;
use App\Mail\OfertaReenvioEmpresaMail;



class AdminOfertaApprovalController extends Controller
{
    public function index(Request $request)
    {
        // 1) Mapa de estados permitidos
        $estadoFiltro = $request->query('estado', 'pending'); // por defecto: pendientes

        $mapaEstados = [
            'pending'     => OfertaTrabajo::ESTADO_PENDIENTE,
            'approved'    => OfertaTrabajo::ESTADO_APROBADA,
            'rejected'    => OfertaTrabajo::ESTADO_RECHAZADA,
            'resubmitted' => OfertaTrabajo::ESTADO_REENVIADA,
            'finalized'   => OfertaTrabajo::ESTADO_FINALIZADA,
            'all'         => null,
        ];


        // Si viene algo raro en la URL, forzamos a 'pending'
        if (! array_key_exists($estadoFiltro, $mapaEstados)) {
            $estadoFiltro = 'pending';
        }

        // 2) Query base: con empresa asociada, ordenado por fecha
        $query = OfertaTrabajo::with('empresa')->orderByDesc('creado_en');

        // 3) Aplicar filtro de estado si corresponde
        $estadoValor = $mapaEstados[$estadoFiltro];

        if ($estadoValor !== null) {
            $query->where('estado', $estadoValor);
        }

        // 4) Ejecutar con paginación
        $ofertas = $query->paginate(10)->withQueryString();

        // 5) Contadores rápidos (para mostrar en la UI)
        $stats = [
            'pending'     => OfertaTrabajo::where('estado', OfertaTrabajo::ESTADO_PENDIENTE)->count(),
            'approved'    => OfertaTrabajo::where('estado', OfertaTrabajo::ESTADO_APROBADA)->count(),
            'rejected'    => OfertaTrabajo::where('estado', OfertaTrabajo::ESTADO_RECHAZADA)->count(),
            'resubmitted' => OfertaTrabajo::where('estado', OfertaTrabajo::ESTADO_REENVIADA)->count(),
            'finalized'   => OfertaTrabajo::where('estado', OfertaTrabajo::ESTADO_FINALIZADA)->count(),
        ];


        return view('admin.ofertas.index', [
            'ofertas'       => $ofertas,
            'estadoFiltro'  => $estadoFiltro,
            'stats'         => $stats,
        ]);
    }
    public function show($id)
    {
        // Buscar oferta
        $oferta = OfertaTrabajo::findOrFail($id);

        // Retornar la vista duplicada para admins
        return view('admin.ofertas.show', compact('oferta'));
    }
    public function approve($id)
    {
        $oferta = OfertaTrabajo::with('empresa')->findOrFail($id);

        $oferta->estado = OfertaTrabajo::ESTADO_APROBADA;
        $oferta->revisada_en = now();
        $oferta->save();
        // ================================
        // CORREO A EMPRESA (OFERTA APROBADA)
        // ================================
        if ($oferta->empresa && $oferta->empresa->correo_contacto) {
            try {
                Mail::to($oferta->empresa->correo_contacto)
                    ->send(new OfertaAprobadaEmpresaMail(
                        $oferta->empresa->razon_social
                            ?? $oferta->empresa->nombre_comercial
                            ?? 'Empresa',
                        $oferta->titulo
                    ));
            } catch (\Throwable $e) {
                Log::error('Error enviando correo oferta aprobada', [
                    'error' => $e->getMessage(),
                    'oferta_id' => $oferta->id,
                ]);
            }
        }


        $mensaje = AlertMessageService::mensaje('APROBADA');

        return redirect()
            ->route('admin.ofertas.show', $id)
            ->with($mensaje['type'], $mensaje['text']);
    }

    public function reject(Request $request, $id)
    {
        // 🔹 Cargar oferta CON empresa
        $oferta = OfertaTrabajo::with('empresa')->findOrFail($id);

        // 🔹 Motivo de rechazo
        $motivo = $request->motivo_rechazo ?? 'Oferta rechazada por el administrador.';

        // 🔹 Actualizar estado
        $oferta->estado = OfertaTrabajo::ESTADO_RECHAZADA;
        $oferta->motivo_rechazo = $motivo;
        $oferta->revisada_en = now();
        $oferta->save();

        // ================================
        // 📧 CORREO A EMPRESA (RECHAZO)
        // ================================
        if ($oferta->empresa && $oferta->empresa->correo_contacto) {
            try {
                Mail::to($oferta->empresa->correo_contacto)
                    ->send(new OfertaRechazadaEmpresaMail(
                        $oferta->empresa->razon_social
                            ?? $oferta->empresa->nombre_comercial
                            ?? 'Empresa',
                        $oferta->titulo,
                        $motivo
                    ));
            } catch (\Throwable $e) {
                Log::error('Error enviando correo oferta rechazada', [
                    'error' => $e->getMessage(),
                    'oferta_id' => $oferta->id,
                ]);
            }
        }


        $mensaje = AlertMessageService::mensaje('RECHAZADA');

        return redirect()
            ->route('admin.ofertas.show', $id)
            ->with($mensaje['type'], $mensaje['text']);
    }


    public function resubmit(Request $request, $id)
    {
        // 🔹 Cargar oferta CON empresa
        $oferta = OfertaTrabajo::with('empresa')->findOrFail($id);

        // 🔹 Motivo de reenvío
        $motivo = $request->motivo_rechazo ?? 'La oferta necesita correcciones.';

        // 🔹 Actualizar estado
        $oferta->estado = OfertaTrabajo::ESTADO_REENVIADA;
        $oferta->motivo_rechazo = $motivo;
        $oferta->revisada_en = now();
        $oferta->save();

        // ================================
        // 📧 CORREO A EMPRESA (REENVÍO)
        // ================================
        if ($oferta->empresa && $oferta->empresa->correo_contacto) {
            try {
                Mail::to($oferta->empresa->correo_contacto)
                    ->send(new OfertaReenvioEmpresaMail(
                        $oferta->empresa->razon_social
                            ?? $oferta->empresa->nombre_comercial
                            ?? 'Empresa',
                        $oferta->titulo,
                        $motivo
                    ));
            } catch (\Throwable $e) {
                Log::error('Error enviando correo oferta reenviada', [
                    'error' => $e->getMessage(),
                    'oferta_id' => $oferta->id,
                ]);
            }
        }


        $mensaje = AlertMessageService::mensaje('REENVIADA');

        return redirect()
            ->route('admin.ofertas.show', $id)
            ->with($mensaje['type'], $mensaje['text']);
    }
}
