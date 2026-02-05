@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h2 class="mb-0">¡Pago Exitoso!</h2>
                    <p class="mb-0">Gracias por su compra</p>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-success">
                        <h4><i class="fas fa-receipt"></i> Detalles del Pago</h4>
                        <div class="row justify-content-center mt-4">
                            <div class="col-md-6">
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span><strong>Total de la compra:</strong></span>
                                        <span class="text-success fw-bold">${{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span><strong>Efectivo recibido:</strong></span>
                                        <span class="text-primary">${{ number_format($efectivo, 2) }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span><strong>Cambio entregado:</strong></span>
                                        <span class="text-warning fw-bold">${{ number_format($cambio, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="my-4">
                        <i class="fas fa-gift fa-4x text-primary mb-3"></i>
                        <h3>¡Gracias por su preferencia!</h3>
                        <p class="lead">Su compra ha sido procesada exitosamente.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-download"></i> 
                            <strong>El ticket se ha descargado automáticamente.</strong>
                            <p class="mb-0 mt-2">Si no se descargó, haga clic en el botón abajo.</p>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('productos.leer') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-shopping-bag"></i> Seguir Comprando
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('carrito.descargar-ticket') }}" 
                               class="btn btn-success btn-lg w-100" 
                               id="descargarBtn">
                                <i class="fas fa-download"></i> Descargar Ticket
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            <small>
                                <i class="fas fa-clock"></i> 
                                {{ now()->format('d/m/Y H:i:s') }}
                            </small>
                        </p>
                    </div>
                </div>
                <div class="card-footer text-center bg-light">
                    <p class="mb-0">
                        <i class="fas fa-phone"></i> Para consultas: 01-800-COMPRAS<br>
                        <i class="fas fa-envelope"></i> contacto@tienda.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Descargar PDF automáticamente al cargar la página
window.onload = function() {
    // Descargar automáticamente
    setTimeout(function() {
        window.location.href = "{{ route('carrito.descargar-ticket') }}";
    }, 1000); // Esperar 1 segundo antes de descargar
    
    // Deshabilitar el botón después de hacer clic
    document.getElementById('descargarBtn').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Descargando...';
        this.classList.add('disabled');
    });
    
    // Mostrar confeti (opcional)
    if (typeof confetti === 'function') {
        confetti({
            particleCount: 150,
            spread: 100,
            origin: { y: 0.6 }
        });
    }
};
</script>
@endsection