<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        // Log explícito para Cloud Run
        \Log::error('EXCEPTION CAPTURADA', [
            'type' => get_class($e),
            'message' => $e->getMessage(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        // Validaciones
        if ($e instanceof ValidationException) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // No autenticado
        if ($e instanceof AuthenticationException) {
            return redirect()->route('login');
        }

        // Errores HTTP (403, 404, etc)
        if ($e instanceof HttpException) {
            return response()->view('errors.generic', [
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }

        // Fallback seguro
        return response()->view('errors.generic', [
            'message' => 'Ocurrió un error inesperado.'
        ], 500);
    }
}
