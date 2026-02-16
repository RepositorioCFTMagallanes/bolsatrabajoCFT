<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleabilidadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\AdminEstudianteController;
use App\Http\Controllers\AdminEmpresaController;
use App\Http\Controllers\AdminOfertaApprovalController;
use App\Http\Controllers\AdminPostulanteController;
use App\Http\Controllers\OfertaPublicaController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\RecursoPublicoController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;

/*
|--------------------------------------------------------------------------
| WEB ROUTES – CFT Empleabilidad
|--------------------------------------------------------------------------
| Estructura:
| 1) Rutas públicas (landing, login, registro, recursos)
| 2) Rutas protegidas por sesión manual  (auth.custom)
| 3) Rutas protegidas por rol           (role:admin/empresa/postulante)
|
| La autenticación se maneja en AuthController usando la tabla "usuarios".
|--------------------------------------------------------------------------
*/


/* ============================================================
   1) RUTA PRINCIPAL / LANDING (Pública)
============================================================ */

Route::get('/', [OfertaPublicaController::class, 'landing'])->name('home');

Route::get('/empleos', [OfertaPublicaController::class, 'index'])
    ->name('empleos.index');

Route::get('/ofertas/{id}', [OfertaController::class, 'show'])
    ->name('ofertas.detalle');



/* ============================================================
   2) AUTENTICACIÓN REAL – AuthController (Público)
============================================================ */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/registrarse', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registrarse', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
/* ============================
   OLVIDÉ / RESET CONTRASEÑA
============================ */

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');


/* ============================================================
   3) RUTAS PROTEGIDAS POR SESIÓN MANUAL – auth.custom
============================================================ */
Route::middleware('auth.custom')->group(function () {

    /* ------------------------- ADMIN (rol: admin) ------------------------- */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function () {

            /* ===============================
              MÓDULO ADMIN: RECURSOS EMPLEABILIDAD (BLOG)
            =============================== */

            Route::get('/recursos', [RecursoController::class, 'index'])
                ->name('admin.recursos.index');

            Route::get('/recursos/crear', [RecursoController::class, 'create'])
                ->name('admin.recursos.create');

            Route::post('/recursos', [RecursoController::class, 'store'])
                ->name('admin.recursos.store');

            Route::get('/recursos/{id}/editar', [RecursoController::class, 'edit'])
                ->name('admin.recursos.edit');

            Route::put('/recursos/{id}', [RecursoController::class, 'update'])
                ->name('admin.recursos.update');

            Route::delete('/recursos/{id}', [RecursoController::class, 'destroy'])
                ->name('admin.recursos.destroy');

            Route::post('/recursos/{id}/toggle', [RecursoController::class, 'toggleStatus'])
                ->name('admin.recursos.toggle');


            // DASHBOARD ADMIN
            Route::get('/', [AdminController::class, 'dashboard'])
                ->name('admin.dashboard');

            /* ===============================
              MÓDULO ADMIN: GESTIÓN ESTUDIANTES
              =============================== */

            Route::get('/estudiantes', [AdminEstudianteController::class, 'index'])
                ->name('admin.estudiantes.index');

            Route::get('/estudiantes/crear', [AdminEstudianteController::class, 'create'])
                ->name('admin.estudiantes.create');

            Route::post('/estudiantes', [AdminEstudianteController::class, 'store'])
                ->name('admin.estudiantes.store');

            Route::get('/estudiantes/{id}/editar', [AdminEstudianteController::class, 'edit'])
                ->name('admin.estudiantes.edit');

            Route::put('/estudiantes/{id}', [AdminEstudianteController::class, 'update'])
                ->name('admin.estudiantes.update');

            Route::delete('/estudiantes/{id}', [AdminEstudianteController::class, 'destroy'])
                ->name('admin.estudiantes.destroy');

            Route::patch('/estudiantes/{id}/restaurar', [AdminEstudianteController::class, 'restore'])
                ->name('admin.estudiantes.restore');
            /* ===============================
            MÓDULO ADMIN: GESTIÓN ADMINISTRADORES
               =============================== */

            Route::get('/administradores', [\App\Http\Controllers\AdminAdministradorController::class, 'index'])
                ->name('admin.administradores.index');

            Route::get('/administradores/crear', [\App\Http\Controllers\AdminAdministradorController::class, 'create'])
                ->name('admin.administradores.create');

            Route::post('/administradores', [\App\Http\Controllers\AdminAdministradorController::class, 'store'])
                ->name('admin.administradores.store');


            /* ===============================
              MÓDULO ADMIN: GESTIÓN EMPRESAS
             =============================== */

            Route::get('/empresas', [AdminEmpresaController::class, 'index'])
                ->name('admin.empresas.index');

            Route::get('/empresas/crear', [AdminEmpresaController::class, 'create'])
                ->name('admin.empresas.create');

            Route::post('/empresas', [AdminEmpresaController::class, 'store'])
                ->name('admin.empresas.store');

            Route::get('/empresas/{id}/editar', [AdminEmpresaController::class, 'edit'])
                ->name('admin.empresas.edit');

            Route::put('/empresas/{id}', [AdminEmpresaController::class, 'update'])
                ->name('admin.empresas.update');

            Route::delete('/empresas/{id}', [AdminEmpresaController::class, 'destroy'])
                ->name('admin.empresas.destroy');

            Route::patch('/empresas/{id}/restaurar', [AdminEmpresaController::class, 'restore'])
                ->name('admin.empresas.restore');

            /* ===============================
              MÓDULO ADMIN: VALIDACIÓN OFERTAS
            =============================== */

            Route::get('/ofertas', [AdminOfertaApprovalController::class, 'index'])
                ->name('admin.ofertas.index');

            Route::get('/ofertas/{id}', [AdminOfertaApprovalController::class, 'show'])
                ->name('admin.ofertas.show');

            Route::patch('/ofertas/{id}/aprobar', [AdminOfertaApprovalController::class, 'approve'])
                ->name('admin.ofertas.approve');

            Route::patch('/ofertas/{id}/rechazar', [AdminOfertaApprovalController::class, 'reject'])
                ->name('admin.ofertas.reject');
            Route::patch('/ofertas/{id}/resubmit', [AdminOfertaApprovalController::class, 'resubmit'])
                ->name('admin.ofertas.resubmit');

            /* ===============================
              MÓDULO ADMIN: POSTULANTES
            =============================== */

            Route::get('/postulantes', [AdminPostulanteController::class, 'index'])
                ->name('admin.postulantes.index');

            Route::get('/postulantes/{id}', [AdminPostulanteController::class, 'show'])
                ->name('admin.postulantes.show');
            /* ===============================
               MÓDULO ADMIN: POSTULACIONES
            =============================== */

            Route::get('/postulaciones', [\App\Http\Controllers\AdminPostulacionesController::class, 'index'])
                ->name('admin.postulaciones.index');
            /* ===============================
               MÓDULO ADMIN: REPORTES
            =============================== */

            Route::get('/reportes', [\App\Http\Controllers\AdminReporteController::class, 'index'])
                ->name('admin.reportes.index');

            Route::get('/reportes/export/excel', [\App\Http\Controllers\AdminReporteController::class, 'exportExcel'])
                ->name('admin.reportes.export.excel');

            Route::get('/reportes/export/pdf', [\App\Http\Controllers\AdminReporteController::class, 'exportPdf'])
                ->name('admin.reportes.export.pdf');
        });

    /* ------------------------- EMPRESAS (rol: empresa) ------------------------- */
    Route::middleware('role:empresa')
        ->prefix('empresas')
        ->group(function () {
            Route::get('/postulaciones', [EmpresaController::class, 'verPostulaciones'])
                ->name('empresas.postulaciones.index');
            Route::get('/postulante/{id}', [EmpresaController::class, 'verPostulante'])
                ->name('empresas.postulante');
            Route::get('/perfil', [EmpresaController::class, 'perfil'])
                ->name('empresas.perfil');

            Route::get('/editar', [EmpresaController::class, 'editar'])
                ->name('empresas.editar');

            Route::post('/perfil/update', [EmpresaController::class, 'updatePerfil'])
                ->name('empresas.perfil.update');

            Route::get('/crear', [EmpresaController::class, 'crearOferta'])
                ->name('empresas.crear');

            Route::post('/ofertas', [EmpresaController::class, 'storeOferta'])
                ->name('empresas.ofertas.store');
            Route::put('/ofertas/{id}', [EmpresaController::class, 'updateOferta'])
                ->name('empresas.ofertas.update');
            Route::get('/ofertas/{id}/editar', [EmpresaController::class, 'editarOferta'])
                ->name('empresas.ofertas.editar');
            Route::delete('/ofertas/{id}', [EmpresaController::class, 'destroyOferta'])
                ->name('empresas.ofertas.destroy');
            Route::get('/ofertas', [EmpresaController::class, 'misOfertas'])
                ->name('empresas.ofertas.index');
            Route::post('/ofertas/{id}/enviar', [EmpresaController::class, 'enviarRevision'])
                ->name('empresas.ofertas.enviarRevision');
            Route::post('/ofertas/{id}/finalizar', [EmpresaController::class, 'finalizarOferta'])
                ->name('empresas.ofertas.finalizar');
        });


    /* -------------------- POSTULANTES / USUARIOS (rol: postulante) -------------------- */
    Route::middleware('role:postulante')
        ->prefix('usuarios')
        ->group(function () {

            Route::get('/perfil', [UsuarioController::class, 'perfil'])
                ->name('usuarios.perfil');

            Route::get('/editar', [UsuarioController::class, 'editar'])
                ->name('usuarios.editar');
            Route::post('/editar', [UsuarioController::class, 'update'])
                ->name('usuarios.update');

            // POSTULAR A UNA OFERTA
            Route::post('/postular/{id}', [PostulacionController::class, 'store'])
                ->name('postulaciones.store');

            // VER MIS POSTULACIONES
            Route::get('/mis-postulaciones', [PostulacionController::class, 'index'])
                ->name('postulaciones.index');
            // VER DETALLE DE UNA POSTULACIÓN
            Route::get('/mis-postulaciones/{id}', [PostulacionController::class, 'show'])
                ->name('postulaciones.show');
            // Ruta AJAX para cargar modal de detalle de postulación
            Route::get('/postulacion-detalle/{id}', [PostulacionController::class, 'modal'])
                ->middleware(['auth.custom', 'role:postulante'])
                ->name('postulaciones.modal');
            Route::post('/mis-postulaciones/{id}/retirar', [PostulacionController::class, 'retirar'])
                ->name('postulaciones.retirar');
        });
});



// DESACTIVADO: ruta mock antigua
// Route::get('/empleos', function () {
//     return view('jobs.index');
// })->name('jobs.index');


/* ============================================================
   5) RECURSOS DE EMPLEABILIDAD (PÚBLICO)
============================================================ */


Route::get('/recursos-empleabilidad', [RecursoPublicoController::class, 'index'])
    ->name('recursos.index');

Route::get('/recursos-empleabilidad/{id}', [RecursoPublicoController::class, 'show'])
    ->name('recursos.show');

Route::view('/terminos-y-condiciones', 'terminos.condiciones')->name('terminos.condiciones');
Route::view('/terminos-difusion-marca', 'terminos.marca')->name('terminos.marca');



Route::get('/test-gcs', function () {
    $path = 'debug/test.txt';
    $content = 'OK ' . now()->toDateTimeString();

    Storage::disk('gcs')->put($path, $content);

    return response()->json([
        'ok' => true,
        'bucket' => config('filesystems.disks.gcs.bucket'),
        'path' => $path,
    ], 200);
});


Route::get('/test-cloudinary', function () {

    try {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud.cloud_name'),
                'api_key'    => config('cloudinary.cloud.api_key'),
                'api_secret' => config('cloudinary.cloud.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        $upload = $cloudinary->uploadApi()->upload(
            public_path('img/default-avatar.png'),
            ['folder' => 'debug']
        );

        return [
            'ok' => true,
            'url' => $upload['secure_url'],
        ];

    } catch (\Throwable $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
        ];
    }
});


Route::get('/debug-config-cloudinary', function () {
    return response()->json([
        'config_cloudinary' => config('cloudinary'),
        'config_exists' => file_exists(config_path('cloudinary.php')),
        'config_path' => config_path('cloudinary.php'),
        'files_in_config' => scandir(config_path()),
    ]);
});
