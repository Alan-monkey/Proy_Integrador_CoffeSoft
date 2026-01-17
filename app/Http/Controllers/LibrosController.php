<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Libros;



class LibrosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }
    public function inicio (){
        $user = auth()->guard('usuarios')->user();
        return view('libros.inicio', compact('user'));
    }
    
    public function crear()
    {
        $user = auth()->guard('usuarios')->user();
       return view('libros.crear', compact('user'));
    }
    public function store(Request $request){
        $request->validate([
            'nombre'=>'required|string|max:255',
            'descripcion'=>'required|string',
            'autor'=>'required|string|max:255',
        ]);
        $libro = new Libros();
        $libro->nombre = $request->nombre;
        $libro->descripcion = $request->descripcion;
        $libro->autor = $request->autor;

        $libro->save();
        return redirect()->back()->with('success', 'Libro creado con exito');
    }
    public function leer()
    {
        $libros = Libros::all();
        $user = auth()->guard('usuarios')->user();
        return view ('libros.leer', compact('libros', 'user'));
    }
        public function eliminar()
    {
        $libros = Libros::all();
        $user = auth()->guard('usuarios')->user();
        return view ('libros.eliminar', compact('libros', 'user'));
    }
    public function update(Request $request, Libros $libro)
    {
        $request->validate([
            'nombre'=>'required|string|max:255',
            'descripcion'=>'required|string',
            'autor'=>'required|string|max:255',
        ]);
        $libro->update($request->all());

        return redirect()->back()->with('success', 'Libro actualizado con exito');
    }
    public function destroy(Request $request )
    {
        $Id = $request->input('IdLibro');
        $libro = Libros::find($Id);
        if($libro){
            $libro->delete();
            return redirect()->back()->with('success', 'Libro borrado con exito');
        }else{
                    return redirect()->back()->with('error', 'Libro no encontrado');
        }
    }
    public function consultar (){
            return view('libros.consultar');
        }
}
