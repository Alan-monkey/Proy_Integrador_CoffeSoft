<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProductosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function crear()
    {
        $user = auth()->guard('usuarios')->user();
        return view('productos.crear', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;

        // Guardar imagen si se subió
        if ($request->hasFile('imagen')) {
            $path = $request->imagen->store('productos', 'public');
            $producto->imagen = $path;
        }

        $producto->save();

        return redirect()->back()->with('success', 'Producto creado con éxito');
    }

    public function leer()
    {
        $productos = Producto::all();
        $user = auth()->guard('usuarios')->user();
        return view('productos.leer', compact('productos', 'user'));
    }

    public function eliminar()
    {
        $productos = Producto::all();
        $user = auth()->guard('usuarios')->user();
        return view('productos.eliminar', compact('productos', 'user'));
    }

    public function update(Request $request, Productos $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;

        if ($request->hasFile('imagen')) {
            $path = $request->imagen->store('productos', 'public');
            $producto->imagen = $path;
        }

        $producto->save();

        return redirect()->back()->with('success', 'Producto actualizado con éxito');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('IdProducto');
        $producto = Producto::find($id);

        if ($producto) {
            $producto->delete();
            return redirect()->back()->with('success', 'Producto eliminado con éxito');
        } else {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }
    }
}
