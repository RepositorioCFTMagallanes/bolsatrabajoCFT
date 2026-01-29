<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\BrevoMailService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /* ============================================================
       LOGIN – FORMULARIO
    ============================================================ */
    public function showLogin(Request $request)
    {
        // SIEMPRE limpiar intended previo
        session()->forget('url.intended');

        return view('auth.login');
    }


    /* ============================================================
       LOGIN – PROCESAR CREDENCIALES
    ============================================================ */
    public function login(Request $request)
    {
        session()->forget('url.intended');

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'El correo no está registrado.'])
                ->withInput();
        }

        if (!Hash::check($request->password, $usuario->contrasena)) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta.'])
                ->withInput();
        }

        // ============================================================
        // SESIÓN MANUAL
        // ============================================================
        session([
            'usuario_id'     => $usuario->id,
            'usuario_nombre' => $usuario->nombre,
            'usuario_rol'    => $usuario->rol_id,   // <-- FIX CORRECTO (NO CAMBIAR POR NOMBRE)
            'autenticado'    => true,
        ]);
        Auth::loginUsingId($usuario->id);


        // ============================================================
        // REDIRECCIÓN POR ROL
        // ============================================================
        $destino = match ((int)$usuario->rol_id) {
            1       => route('admin.dashboard'),
            2       => route('empresas.perfil'),
            default => route('usuarios.perfil'),
        };

        return redirect($destino);
    }


    /* ============================================================
       LOGOUT
    ============================================================ */
    
    public function logout()
    {
        Auth::logout();
        Session::flush();
        session()->forget('url.intended');
        return redirect('/login');
    }


    /* ============================================================
       REGISTRO – FORMULARIO
    ============================================================ */
    public function showRegister()
    {
        return view('auth.register');
    }


    /* ============================================================
       REGISTRO – GUARDAR
    ============================================================ */
    public function register(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:postulante,empresa',
            'name'         => 'required|string',
            'lastname'     => 'required|string',
            'email'        => 'required|email|unique:usuarios,email',
            'password'     => 'required|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'rol_id'     => $request->account_type === 'empresa' ? 2 : 3,
            'nombre'     => $request->name,
            'apellido'   => $request->lastname,
            'email'      => $request->email,
            'contrasena' => $request->password, // Mutator hashea automáticamente
        ]);

        if ($request->account_type === 'empresa') {
            Empresa::create([
                'usuario_id'        => $usuario->id,
                'nombre_comercial'  => $request->company_name,
                'rut'               => $request->company_rut,
                'correo_contacto'   => $usuario->email,
                'telefono_contacto' => 'No informado',
            ]);
        } else {
            Estudiante::create([
                'usuario_id' => $usuario->id,
            ]);
        }
        // CORREO DE BIENVENIDA
        BrevoMailService::send(
            $usuario->email,
            'Bienvenido/a al Portal de Empleabilidad CFT Magallanes',
            view('emails.welcome', [
                'nombre' => $usuario->nombre,
            ])->render()
        );

        return redirect('/login')->with('status', 'Cuenta creada con éxito.');
    }
}
