<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1️⃣ Validación básica
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2️⃣ Buscar usuario en MongoDB
        $user = Usuarios::where('email', $request->email)->first();

        // 3️⃣ Verificar contraseña
        if ($user && Hash::check($request->password, $user->password)) {

            // 4️⃣ Autenticación con guard Mongo
            Auth::guard('usuarios')->login($user);

            return redirect()->intended('/libros/inicio');
        }

        // 5️⃣ Error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('usuarios')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
