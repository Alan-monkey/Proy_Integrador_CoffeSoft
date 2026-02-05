<!-- resources/views/carrito/ver.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Sección del Carrito -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-shopping-cart"></i> Mi Carrito de Compras</h3>
                </div>
                <div class="card-body">
                    @if(empty($carrito))
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-cart-arrow-down fa-3x mb-3"></i>
                            <h4>Tu carrito está vacío</h4>
                            <p class="mb-0">¡Agrega algunos productos para comenzar a comprar!</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Precio Unitario</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($carrito as $id => $item)
                                        @php 
                                            $subtotal = $item['precio'] * $item['cantidad'];
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(isset($item['imagen']) && $item['imagen'])
                                                        <img src="{{ asset('storage/' . $item['imagen']) }}" 
                                                             class="img-thumbnail me-3" 
                                                             style="width: 80px; height: 80px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $item['nombre'] }}</h6>
                                                        <small class="text-muted">ID: {{ $id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold text-success">${{ number_format($item['precio'], 2) }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <span class="mx-3 fw-bold">{{ $item['cantidad'] }}</span>
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <form action="{{ route('carrito.eliminar', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            title="Eliminar del carrito">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Resumen del Pedido -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Resumen del Pedido</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>${{ number_format($total, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Envío:</span>
                                            <span class="text-success">Gratis</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold fs-5">
                                            <span>Total:</span>
                                            <span class="text-primary">${{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-end">
                                <button class="btn btn-success btn-lg mb-2">
                                    <i class="fas fa-credit-card"></i> Proceder al Pago
                                </button>
                                <button class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-shopping-bag"></i> Continuar Comprando
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sección de Productos Disponibles -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-boxes"></i> Productos Disponibles</h4>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if(isset($productos) && count($productos) > 0)
                        @foreach($productos as $producto)
                            <div class="card mb-3 border">
                                <div class="row g-0">
                                    @if($producto->imagen)
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                                 class="img-fluid rounded-start h-100" 
                                                 style="object-fit: cover; height: 120px;"
                                                 alt="{{ $producto->nombre }}">
                                        </div>
                                    @endif
                                    <div class="{{ $producto->imagen ? 'col-md-8' : 'col-md-12' }}">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-1">{{ $producto->nombre }}</h6>
                                            <p class="card-text text-muted small mb-2">
                                                {{ Str::limit($producto->descripcion, 50) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-success">${{ number_format($producto->precio, 2) }}</span>
                                                <form action="{{ route('carrito.agregar', $producto->_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-cart-plus"></i> Agregar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning text-center py-3">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p class="mb-0">No hay productos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contador del Carrito -->
            @if(!empty($carrito))
<div class="d-grid gap-2 mt-4">
    <a href="{{ route('carrito.mostrar-pago') }}" class="btn btn-success btn-lg">
        <i class="fas fa-cash-register"></i> Proceder al Pago
    </a>
</div>
@endif
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    .card {
        border-radius: 10px;
        border: none;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .img-thumbnail {
        border-radius: 8px;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    
    .progress {
        border-radius: 5px;
    }
</style>

<!-- Agrega FontAwesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Script para animaciones -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Animación al agregar producto
        $('form').on('submit', function(e) {
            if ($(this).find('button').hasClass('btn-primary')) {
                $(this).find('button').html('<i class="fas fa-spinner fa-spin"></i> Agregando...');
            }
        });
        
        // Efecto hover en tarjetas de productos
        $('.card.mb-3').hover(
            function() {
                $(this).css('transform', 'translateY(-5px)');
                $(this).css('box-shadow', '0 5px 15px rgba(0,0,0,0.1)');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
                $(this).css('box-shadow', '0 2px 5px rgba(0,0,0,0.05)');
            }
        );
    });
</script>   
@endsection