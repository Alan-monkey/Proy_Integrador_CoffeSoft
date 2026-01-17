<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Usuarios;

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
        'user_name' => 'required|unique:usuarios,user_name',
        'user_pass' => 'required|min:3',
        'user_tipo' => 'required'
        ]);
        
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = new Usuarios();
        $user->user_name = $request->user_name;
        $user->user_pass = Hash::make($request->user_pass);
        $user->user_tipo = $request->user_tipo;
        $user->save();

        return redirect()->back()->with('success', 'Usuario registrado correctamente');
    }
}
