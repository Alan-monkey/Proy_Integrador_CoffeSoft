<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = Usuarios::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'No encontramos un usuario con ese email.']);
        }
        
        // Generar token de recuperación
        $token = Str::random(64);
        
        // Guardar en la base de datos (sin manejo complejo de fechas por ahora)
        $expiresAt = Carbon::now()->addHours(1)->toDateTimeString();
        
        $user->update([
            'reset_token' => $token,
            'reset_token_expires_at' => $expiresAt
        ]);
        
        Log::info('Token generado para ' . $user->email . ': ' . $token);
        
        $resetUrl = route('password.reset', ['token' => $token]);
        
        // Mostrar directamente en la vista
        return view('auth.forgot-password', [
            'email' => $request->email,
            'reset_link' => $resetUrl,
            'show_link' => true,
            'token' => $token
        ]);
    }

    public function showResetForm($token)
    {
        Log::info('Mostrando formulario reset para token: ' . $token);
        
        // Buscar usuario con el token
        $user = Usuarios::where('reset_token', $token)->first();
        
        if (!$user) {
            Log::error('Token no encontrado: ' . $token);
            return redirect()->route('password.forgot')->withErrors([
                'error' => 'El enlace es inválido o ha expirado.'
            ]);
        }
        
        // Verificar si el token ha expirado (manejo simple)
        if ($user->reset_token_expires_at) {
            try {
                $expiration = Carbon::parse($user->reset_token_expires_at);
                if ($expiration->isPast()) {
                    Log::warning('Token expirado para: ' . $user->email);
                    return redirect()->route('password.forgot')->withErrors([
                        'error' => 'El enlace ha expirado.'
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Error al parsear fecha: ' . $e->getMessage());
            }
        }
        
        Log::info('Token válido para: ' . $user->email);
        
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $user->email
        ]);
    }

    // ¡ESTO ES LO QUE FALTA! - El método resetPassword
    public function resetPassword(Request $request)
    {
        Log::info('=== INICIANDO RESET PASSWORD ===');
        Log::info('Token recibido: ' . $request->token);
        Log::info('Email: ' . ($request->email ?? 'No proporcionado'));
        
        // Validar los datos
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:3|confirmed',
        ]);
        
        Log::info('Validación pasada');
        
        // Buscar usuario con el token
        $user = Usuarios::where('reset_token', $request->token)->first();
        
        if (!$user) {
            Log::error('Usuario no encontrado con token: ' . $request->token);
            return back()->withErrors([
                'error' => 'El token es inválido o ha expirado.'
            ])->withInput();
        }
        
        Log::info('Usuario encontrado: ' . $user->email);
        
        // Verificar si el token ha expirado
        if ($user->reset_token_expires_at) {
            try {
                $expiration = Carbon::parse($user->reset_token_expires_at);
                if ($expiration->isPast()) {
                    Log::warning('Token expirado para: ' . $user->email);
                    
                    // Limpiar token expirado
                    $user->update([
                        'reset_token' => null,
                        'reset_token_expires_at' => null
                    ]);
                    
                    return redirect()->route('password.forgot')->withErrors([
                        'error' => 'El enlace ha expirado. Por favor, solicita uno nuevo.'
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Error al verificar expiración: ' . $e->getMessage());
            }
        }
        
        // Actualizar contraseña y limpiar token
        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);
        
        Log::info('Contraseña actualizada para: ' . $user->email);
        
        return redirect()->route('login')->with([
            'success' => '¡Contraseña actualizada correctamente! Ahora puedes iniciar sesión con tu nueva contraseña.'
        ]);
    }
}