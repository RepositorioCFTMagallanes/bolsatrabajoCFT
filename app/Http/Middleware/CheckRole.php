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
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
