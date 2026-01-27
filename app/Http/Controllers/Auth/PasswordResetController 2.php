<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PasswordResetController extends Controller
{
    /* =========================================================
       MOSTRAR FORMULARIO "OLVIDÉ MI CONTRASEÑA"
    ========================================================= */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /* =========================================================
       ENVIAR LINK DE RECUPERACIÓN
    ========================================================= */
    public function sendResetLink(Request $request)
    {
        // 1️⃣ Validar email
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // 2️⃣ Buscar usuario en tabla custom "usuarios"
        $usuario = DB::table('usuarios')->where('email', $email)->first();

        // Respuesta neutra (seguridad)
        if (!$usuario) {
            return back()->with('status', 'Si el correo existe, se enviará un enlace de recuperación.');
        }

        // 3️⃣ Eliminar tokens previos
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // 4️⃣ Generar token
        $tokenPlano = Str::random(64);

        // 5️⃣ Guardar token hasheado
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => hash('sha256', $tokenPlano),
            'created_at' => Carbon::now(),
        ]);

        // 6️⃣ Enviar correo vía BREVO API (NO SMTP)
        Http::withHeaders([
            'api-key' => config('services.brevo.key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => 'CFT Magallanes',
                'email' => config('mail.from.address'),
            ],
            'to' => [
                ['email' => $email]
            ],
            'subject' => 'Recuperación de contraseña – Portal Empleabilidad',
            'htmlContent' => view('emails.password-reset', [
                'token' => $tokenPlano,
                'email' => $email,
            ])->render(),
        ]);


        // 7️⃣ Mensaje final
        return back()->with('status', 'Si el correo existe, se enviará un enlace de recuperación.');
    }

    /* =========================================================
       MOSTRAR FORMULARIO DE RESET
    ========================================================= */
    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    /* =========================================================
       PROCESAR NUEVA CONTRASEÑA
    ========================================================= */
    public function resetPassword(Request $request)
    {
        // 1️⃣ Validar datos
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        $email = $request->email;
        $tokenPlano = $request->token;

        // 2️⃣ Buscar token en DB
        $registro = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$registro) {
            return back()->withErrors(['email' => 'El enlace de recuperación no es válido o ha expirado.']);
        }

        // 3️⃣ Validar token
        if (!hash_equals($registro->token, hash('sha256', $tokenPlano))) {
            return back()->withErrors([
                'token' => 'El enlace de recuperación no es válido o ya fue utilizado.'
            ]);
        }

        // 4️⃣ Validar expiración (60 minutos)
        $expira = Carbon::parse($registro->created_at)->addMinutes(60);

        if (Carbon::now()->greaterThan($expira)) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['token' => 'El enlace de recuperación ha expirado.']);
        }

        // 5️⃣ Actualizar contraseña en tabla "usuarios"
        DB::table('usuarios')
            ->where('email', $email)
            ->update([
                'contrasena' => Hash::make($request->password),
            ]);

        // 6️⃣ Eliminar token usado
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // 7️⃣ Redirigir a login
        return redirect()->route('login')
            ->with('status', 'Tu contraseña fue restablecida correctamente. Ya puedes iniciar sesión.');
    }
}
