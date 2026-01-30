<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegistrarseController extends Controller
{
    public function registrarse()
    {
        $user = auth()->guard('usuarios')->user();
        return view('libros.registrarse', compact('user'));
    }
    
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:mongodb.usuarios,email',
            'password' => 'required|min:3',
            'user_tipo' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Usuarios::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'user_tipo' => $request->user_tipo,
        ]);

        return redirect()->back()->with('success', 'Usuario registrado correctamente');
    }
}
