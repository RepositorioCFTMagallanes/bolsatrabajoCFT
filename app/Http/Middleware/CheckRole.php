<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Maneja el acceso según el rol (ID) almacenado en sesión.
     *
     * Uso: ->middleware('role:admin')
     * 
     * Roles:
     *  admin      → 1
     *  empresa    → 2
     *  postulante → 3
     */
    public function handle(Request $request, Closure $next, $roleName)
    {
        if (!session('autenticado')) {
            return redirect()->route('login');
        }

        $roles = [
            'admin'      => 1,
            'empresa'    => 2,
            'postulante' => 3,
        ];

        $requiredRoleId = $roles[$roleName] ?? null;

        // 🔒 CAST EXPLÍCITO (CRÍTICO PARA CLOUD)
        $userRoleId = (int) session('usuario_rol');

        if ($requiredRoleId === null || $userRoleId !== (int) $requiredRoleId) {
            \Log::warning('Acceso denegado por rol', [
                'required_role' => $requiredRoleId,
                'user_role' => $userRoleId,
                'url' => $request->path(),
                'method' => $request->method(),
            ]);

            return redirect()->route('login')
                ->withErrors('No tienes permiso para acceder a esta sección.');
        }


        return $next($request);
    }
}
