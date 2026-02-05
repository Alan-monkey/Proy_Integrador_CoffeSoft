<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido; // Si tienes modelo Pedido
use Barryvdh\DomPDF\Facade\Pdf;

class CarritoController extends Controller
{
    public function agregar(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return back()->with('error', 'Producto no encontrado');
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'];
        } else {
            $carrito[$id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen,
                'cantidad' => 1
            ];
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function ver()
    {
        $carrito = session()->get('carrito', []);
        $productos = \App\Models\Producto::all();
        return view('carrito.ver', compact('carrito', 'productos'));
    }

    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);

        unset($carrito[$id]);

        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto eliminado del carrito');
    }public function mostrarPago()
    {
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.ver')->with('error', 'El carrito está vacío');
        }
        
        $total = $this->calcularTotal($carrito);
        
        return view('carrito.pago', compact('total', 'carrito'));
    }
    
    public function procesarPago(Request $request)
{
    $request->validate([
        'efectivo_recibido' => 'required|numeric|min:0',
    ]);
    
    $carrito = session()->get('carrito', []);
    $total = $this->calcularTotal($carrito);
    $efectivo = $request->efectivo_recibido;
    
    if ($efectivo < $total) {
        return back()->with('error', 'El efectivo recibido es insuficiente');
    }
    
    $cambio = $efectivo - $total;
    
    // Guardar datos en sesión para el ticket
    session()->put('pago_total', $total);
    session()->put('pago_efectivo', $efectivo);
    session()->put('pago_cambio', $cambio);
    session()->put('pago_fecha', now());
    
    // Generar ticket inmediatamente y guardar en sesión
    $pedido = $this->guardarPedido($carrito, $total, $efectivo, $cambio);
    $pdf = $this->generarTicketPDF($pedido, $carrito, $total, $efectivo, $cambio);
    
    // Guardar el PDF en sesión como string
    session()->put('pdf_ticket', $pdf->output());
    session()->put('pdf_filename', 'ticket-' . $pedido['numero_pedido'] . '.pdf');
    
    // Limpiar carrito
    session()->forget('carrito');
    
    // Redirigir a página de éxito
    return redirect()->route('carrito.pago-exito')
        ->with('success', 'Pago procesado exitosamente')
        ->with('total', $total)
        ->with('efectivo', $efectivo)
        ->with('cambio', $cambio);
}
    
    private function calcularTotal($carrito)
    {
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
    
    private function guardarPedido($carrito, $total, $efectivo, $cambio)
    {
        // Si tienes modelo Pedido, guárdalo aquí
        // Ejemplo:
        /*
        $pedido = Pedido::create([
            'usuario_id' => auth()->guard('usuarios')->id(),
            'total' => $total,
            'efectivo_recibido' => $efectivo,
            'cambio' => $cambio,
            'productos' => json_encode($carrito),
            'fecha' => now(),
        ]);
        return $pedido;
        */
        
        // Por ahora, retornamos un array
        return [
            'fecha' => now()->format('Y-m-d H:i:s'),
            'numero_pedido' => 'PED-' . time(),
            'total' => $total,
            'efectivo' => $efectivo,
            'cambio' => $cambio,
        ];
    }
    
    private function generarTicketPDF($pedido, $carrito, $total, $efectivo, $cambio)
    {
        $pdf = Pdf::loadView('pdf.ticket', [
            'pedido' => $pedido,
            'carrito' => $carrito,
            'total' => $total,
            'efectivo' => $efectivo,
            'cambio' => $cambio,
            'usuario' => auth()->guard('usuarios')->user(),
        ]);
        
        return $pdf;
    }
    
    public function descargarTicket()
{
    // Recuperar datos de la sesión o generar nuevo ticket
    $carrito = session()->get('carrito', []);
    $total = session()->get('pago_total', 0);
    $efectivo = session()->get('pago_efectivo', 0);
    $cambio = session()->get('pago_cambio', 0);
    
    // Si no hay datos en sesión, crear datos de ejemplo
    if (empty($carrito)) {
        $carrito = ['ejemplo' => [
            'nombre' => 'Producto de Ejemplo',
            'precio' => 100,
            'cantidad' => 1
        ]];
        $total = 100;
        $efectivo = 150;
        $cambio = 50;
    }
    
    // Generar datos del pedido
    $pedido = [
        'fecha' => now()->format('d/m/Y H:i:s'),
        'numero_pedido' => 'PED-' . time() . '-' . rand(1000, 9999),
        'total' => $total,
        'efectivo' => $efectivo,
        'cambio' => $cambio,
    ];
    
    $usuario = auth()->guard('usuarios')->user();
    
    // Generar PDF
    $pdf = Pdf::loadView('pdf.ticket', [
        'pedido' => $pedido,
        'carrito' => $carrito,
        'total' => $total,
        'efectivo' => $efectivo,
        'cambio' => $cambio,
        'usuario' => $usuario,
    ]);
    
    // Nombre del archivo
    $filename = 'ticket-' . $pedido['numero_pedido'] . '.pdf';
    
    // Descargar PDF
    return $pdf->download($filename);
}
public function pagoExito()
{
    // Recuperar datos de la sesión
    $total = session()->get('pago_total', 0);
    $efectivo = session()->get('pago_efectivo', 0);
    $cambio = session()->get('pago_cambio', 0);
    
    // Si no hay datos, redirigir al carrito
    if ($total == 0) {
        return redirect()->route('carrito.ver')->with('error', 'No hay pago reciente');
    }
    
    return view('carrito.pago-exito', compact('total', 'efectivo', 'cambio'));
}
}