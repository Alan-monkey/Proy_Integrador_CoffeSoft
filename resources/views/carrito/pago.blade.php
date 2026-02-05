@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center"><i class="fas fa-cash-register"></i> Pago en Efectivo</h3>
                </div>
                <div class="card-body">
                    <!-- Resumen del pedido -->
                    <div class="alert alert-info">
                        <h5 class="mb-3"><i class="fas fa-receipt"></i> Resumen del Pedido</h5>
                        @php
                            $total = 0;
                            $itemsCount = 0;
                        @endphp
                        
                        @foreach($carrito as $item)
                            @php
                                $subtotal = $item['precio'] * $item['cantidad'];
                                $total += $subtotal;
                                $itemsCount += $item['cantidad'];
                            @endphp
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>
                                    <span class="fw-bold">{{ $item['nombre'] }}</span>
                                    <small class="text-muted">x{{ $item['cantidad'] }}</small>
                                </div>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                        @endforeach
                        
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between fw-bold fs-4">
                                <span>TOTAL A PAGAR:</span>
                                <span class="text-success">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de pago -->
                    <form action="{{ route('carrito.procesar-pago') }}" method="POST" id="pagoForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="efectivoRecibido" class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave"></i> Efectivo Recibido
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">$</span>
                                <input type="text" 
                                       class="form-control text-center fs-3" 
                                       id="efectivoRecibido" 
                                       name="efectivo_recibido" 
                                       required
                                       readonly
                                       placeholder="0.00">
                            </div>
                        </div>

                        <!-- Calculadora -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-calculator"></i> Calculadora</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-3">
                                    @foreach([20, 50, 100, 200, 500, 1000] as $billete)
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-success w-100 billete-btn" 
                                                data-value="{{ $billete }}">
                                            ${{ $billete }}
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <!-- Teclado numérico -->
                                <div class="row g-2">
                                    @for($i = 1; $i <= 9; $i++)
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 numero-btn" 
                                                data-value="{{ $i }}">
                                            {{ $i }}
                                        </button>
                                    </div>
                                    @endfor
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-3" 
                                                onclick="agregarDecimal()">
                                            .
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 numero-btn" 
                                                data-value="0">
                                            0
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-danger w-100 py-3" 
                                                onclick="limpiarCalculadora()">
                                            C
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Botones de cantidad rápida -->
                                <div class="row g-2 mt-3">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-info w-100" onclick="calcularVuelto()">
                                            <i class="fas fa-calculator"></i> Calcular Vuelto
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-warning w-100" onclick="insertarTotal()">
                                            <i class="fas fa-dollar-sign"></i> Insertar Total
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg py-3">
                                <i class="fas fa-check-circle"></i> PROCESAR PAGO
                            </button>
                            <a href="{{ route('carrito.ver') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Volver al Carrito
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-cash-register fa-3x text-success mb-3"></i>
                    <h4>¿Confirmar pago?</h4>
                </div>
                <div class="alert alert-info">
                    <p class="mb-1"><strong>Total a pagar:</strong> $<span id="modalTotal">{{ number_format($total, 2) }}</span></p>
                    <p class="mb-1"><strong>Efectivo recibido:</strong> $<span id="modalEfectivo">0.00</span></p>
                    <p class="mb-0"><strong>Cambio a entregar:</strong> $<span id="modalCambio">0.00</span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmarPagoBtn">
                    <i class="fas fa-check"></i> Confirmar Pago
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .billete-btn:hover, .numero-btn:hover {
        transform: scale(1.05);
        transition: transform 0.2s;
    }
    .btn-lg {
        font-size: 1.1rem;
    }
    .input-group-lg .form-control {
        font-weight: bold;
        color: #28a745;
    }
</style>

<script>
let efectivoActual = 0;
const totalPagar = {{ $total }};

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Botones numéricos
    document.querySelectorAll('.numero-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            agregarNumero(this.dataset.value);
        });
    });
    
    // Botones de billetes
    document.querySelectorAll('.billete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            agregarBillete(parseFloat(this.dataset.value));
        });
    });
    
    // Confirmar pago
    document.getElementById('confirmarPagoBtn').addEventListener('click', function() {
        document.getElementById('pagoForm').submit();
    });
    
    // Mostrar confirmación al enviar formulario
    document.getElementById('pagoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (efectivoActual >= totalPagar) {
            actualizarModal();
            new bootstrap.Modal(document.getElementById('confirmacionModal')).show();
        } else {
            alert('El efectivo recibido es insuficiente');
        }
    });
});

function agregarNumero(numero) {
    let valor = document.getElementById('efectivoRecibido').value;
    if (valor === '0.00' || valor === '0') {
        valor = numero;
    } else {
        // Si ya tiene decimal, agregar después del punto
        if (valor.includes('.')) {
            const partes = valor.split('.');
            if (partes[1].length < 2) {
                valor = valor + numero;
            }
        } else {
            valor = valor + numero;
        }
    }
    actualizarEfectivo(valor);
}

function agregarBillete(monto) {
    const actual = parseFloat(document.getElementById('efectivoRecibido').value) || 0;
    const nuevo = actual + monto;
    actualizarEfectivo(nuevo.toFixed(2));
}

function agregarDecimal() {
    let valor = document.getElementById('efectivoRecibido').value;
    if (!valor.includes('.')) {
        valor = valor + '.';
        actualizarEfectivo(valor);
    }
}

function limpiarCalculadora() {
    actualizarEfectivo('0.00');
}

function insertarTotal() {
    actualizarEfectivo(totalPagar.toFixed(2));
}

function calcularVuelto() {
    const vuelto = efectivoActual - totalPagar;
    if (vuelto > 0) {
        alert(`Cambio a entregar: $${vuelto.toFixed(2)}`);
    } else {
        alert('Efectivo insuficiente');
    }
}

function actualizarEfectivo(valor) {
    // Limpiar valor
    valor = valor.replace(/[^0-9.]/g, '');
    
    // Formatear como número con dos decimales
    let numero = parseFloat(valor);
    if (isNaN(numero)) numero = 0;
    
    const valorFormateado = numero.toFixed(2);
    document.getElementById('efectivoRecibido').value = valorFormateado;
    efectivoActual = numero;
    
    // Cambiar color según si es suficiente
    const input = document.getElementById('efectivoRecibido');
    if (numero >= totalPagar) {
        input.classList.remove('text-danger');
        input.classList.add('text-success');
    } else {
        input.classList.remove('text-success');
        input.classList.add('text-danger');
    }
}

function actualizarModal() {
    const efectivo = efectivoActual;
    const cambio = efectivo - totalPagar;
    
    document.getElementById('modalEfectivo').textContent = efectivo.toFixed(2);
    document.getElementById('modalCambio').textContent = cambio.toFixed(2);
}
</script>
@endsection