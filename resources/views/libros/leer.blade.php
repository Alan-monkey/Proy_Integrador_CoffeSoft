@extends('layouts.app')
@section('content')

<div class="coffee-management-container">
    <!-- Header de la sección -->
    <div class="coffee-section-header">
        <div class="header-content">
            <i class="fas fa-book-open"></i>
            <h1>Gestión de Menú</h1>
            <p>Administra los productos disponibles en tu cafetería</p>
        </div>
        <div class="coffee-decoration">
            <div class="coffee-mug"></div>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="coffee-table-container">
        <div class="table-responsive">
            <table class="coffee-table">
                <thead class="coffee-table-header">
                    <tr>
                        <th scope="col">
                            <i class="fas fa-utensils"></i>
                            Producto
                        </th>
                        <th scope="col">
                            <i class="fas fa-align-left"></i>
                            Descripción
                        </th>
                        <th scope="col">
                            <i class="fas fa-user-chef"></i>
                            Creador
                        </th>
                        <th scope="col">
                            <i class="fas fa-cogs"></i>
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="coffee-table-body">
                    @foreach ($libros as $libro)
                    <tr class="coffee-table-row">
                        <td class="product-name">
                            <div class="name-wrapper">
                                <i class="fas fa-coffee product-icon"></i>
                                <span>{{ $libro->nombre }}</span>
                            </div>
                        </td>
                        <td class="product-description">
                            <span class="description-text">{{ $libro->descripcion }}</span>
                        </td>
                        <td class="product-author">
                            <div class="author-wrapper">
                                <i class="fas fa-user"></i>
                                <span>{{ $libro->autor }}</span>
                            </div>
                        </td>
                        <td class="product-actions">
                            <button type="button"
                                    class="btn btn-update"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal{{ $libro->id }}">
                                <i class="fas fa-edit"></i>
                                Actualizar
                            </button>
                            @include('libros.actualizar')
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estado vacío -->
    @if($libros->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-coffee"></i>
        </div>
        <h3>No hay productos en el menú</h3>
        <p>Comienza agregando nuevos productos a tu cafetería</p>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Agregar Producto
        </button>
    </div>
    @endif

    <!-- Alertas -->
    @if (session('success'))
    <div class="coffee-alert success">
        <div class="alert-content">
            <i class="fas fa-check-circle"></i>
            <div>
                <h4>¡Éxito!</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        <button class="alert-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
</div>

<style>
    .coffee-management-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        background: linear-gradient(135deg, #f8f4f0 0%, #f5f1e8 100%);
        min-height: 100vh;
    }

    /* Header de la sección */
    .coffee-section-header {
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        box-shadow: 0 10px 30px rgba(139, 69, 19, 0.3);
        position: relative;
        overflow: hidden;
    }

    .coffee-section-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.1"><path d="M30,30 Q50,10 70,30 T90,50 T70,70 T50,90 T30,70 T10,50 T30,30 Z" fill="%23ffffff"/></svg>');
        background-size: 150px;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-content i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #D4AF37;
    }

    .header-content h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .coffee-decoration {
        position: relative;
        z-index: 2;
    }

    .coffee-mug {
        width: 80px;
        height: 80px;
        background: #D4AF37;
        border-radius: 50%;
        position: relative;
    }

    .coffee-mug::before {
        content: "";
        position: absolute;
        width: 60px;
        height: 60px;
        background: #8B4513;
        border-radius: 50%;
        top: 10px;
        left: 10px;
    }

    /* Contenedor de la tabla */
    .coffee-table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Estilos de la tabla */
    .coffee-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .coffee-table-header {
        background: linear-gradient(135deg, #5D4037 0%, #3E2723 100%);
    }

    .coffee-table-header th {
        color: white;
        padding: 1.5rem 1rem;
        font-weight: 600;
        font-size: 1rem;
        text-align: left;
        border: none;
    }

    .coffee-table-header th i {
        margin-right: 8px;
        color: #D4AF37;
    }

    .coffee-table-body {
        background: white;
    }

    .coffee-table-row {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .coffee-table-row:hover {
        background: #f9f5f0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(139, 69, 19, 0.1);
    }

    .coffee-table-row:last-child {
        border-bottom: none;
    }

    .coffee-table-row td {
        padding: 1.5rem 1rem;
        border: none;
        vertical-align: middle;
    }

    /* Celdas específicas */
    .product-name {
        font-weight: 600;
        color: #5D4037;
    }

    .name-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-icon {
        color: #8B4513;
        font-size: 1.2rem;
    }

    .product-description {
        color: #666;
        max-width: 300px;
    }

    .description-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .product-author {
        color: #5D4037;
    }

    .author-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-wrapper i {
        color: #8B4513;
    }

    /* Botones de acción */
    .product-actions {
        text-align: center;
    }

    .btn-update {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        background: linear-gradient(135deg, #B8860B 0%, #D4AF37 100%);
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    .empty-icon {
        font-size: 4rem;
        color: #D4AF37;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #5D4037;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .empty-state .btn {
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .empty-state .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 69, 19, 0.3);
    }

    /* Alertas */
    .coffee-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 15px;
        max-width: 400px;
        z-index: 1000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid #4CAF50;
    }

    .coffee-alert.success {
        border-left-color: #4CAF50;
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .alert-content i {
        font-size: 1.5rem;
        color: #4CAF50;
    }

    .alert-content h4 {
        margin: 0 0 4px 0;
        color: #2E7D32;
        font-size: 1rem;
    }

    .alert-content p {
        margin: 0;
        color: #666;
        font-size: 0.9rem;
    }

    .alert-close {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .alert-close:hover {
        background: #f5f5f5;
        color: #666;
    }

    /* Animaciones */
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .coffee-management-container {
            padding: 1rem;
        }

        .coffee-section-header {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1rem;
        }

        .header-content h1 {
            font-size: 2rem;
        }

        .coffee-table-header {
            display: none;
        }

        .coffee-table-row {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1rem;
        }

        .coffee-table-row td {
            display: block;
            padding: 0.5rem 0;
            text-align: left;
        }

        .coffee-table-row td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #5D4037;
            display: block;
            margin-bottom: 0.25rem;
        }

        .product-actions {
            text-align: left;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            margin-top: 1rem;
        }
    }
</style>

<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // Cerrar alertas automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.coffee-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Cerrar alerta manualmente
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.coffee-alert');
                alert.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });
        });
    });

    // Agregar la animación de salida
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    `;
    document.head.appendChild(style);
</script>

@endsection