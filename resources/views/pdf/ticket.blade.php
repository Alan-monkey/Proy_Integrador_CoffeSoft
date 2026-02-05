<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Compra - {{ $pedido['numero_pedido'] }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .title {
            font-weight: bold;
            font-size: 14px;
            margin: 0;
        }
        .subtitle {
            font-size: 10px;
            margin: 2px 0;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .thank-you {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">MI TIENDA</p>
        <p class="subtitle">Dirección: Av. Principal #123</p>
        <p class="subtitle">Tel: 555-1234</p>
        <p class="subtitle">RFC: XXXX010101XXX</p>
    </div>
    
    <div class="line"></div>
    
    <p><strong>Ticket:</strong> {{ $pedido['numero_pedido'] }}</p>
    <p><strong>Fecha:</strong> {{ $pedido['fecha'] }}</p>
    <p><strong>Cajero:</strong> {{ $usuario->nombre ?? 'Sistema' }}</p>
    
    <div class="line"></div>
    
    <p><strong>PRODUCTOS:</strong></p>
    @foreach($carrito as $id => $item)
    <div class="item">
        <div>
            {{ $item['nombre'] }} x{{ $item['cantidad'] }}
        </div>
        <div>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
    </div>
    @endforeach
    
    <div class="line"></div>
    
    <div class="item total">
        <div>TOTAL:</div>
        <div>${{ number_format($total, 2) }}</div>
    </div>
    
    <div class="item">
        <div>EFECTIVO:</div>
        <div>${{ number_format($efectivo, 2) }}</div>
    </div>
    
    <div class="item total">
        <div>CAMBIO:</div>
        <div>${{ number_format($cambio, 2) }}</div>
    </div>
    
    <div class="line"></div>
    
    <div class="thank-you">
        ¡GRACIAS POR SU COMPRA!
    </div>
    
    <div class="footer">
        <p>** Ticket válido como comprobante **</p>
        <p>Conserve este ticket para cualquier aclaración</p>
        <p>--------------------------------</p>
        <p>Powered by Laravel POS System</p>
    </div>
</body>
</html>