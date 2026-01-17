@extends('layouts.app')
@section('content')

<div class="coffee-search-container">
    <!-- Elementos decorativos de fondo -->
    <div class="coffee-elements">
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
        <div class="coffee-bean bean-4"></div>
        <div class="search-magnifier">
            <div class="magnifier-glass">
                <div class="glass-handle"></div>
            </div>
        </div>
    </div>

    <div class="search-wrapper">
        <!-- Tarjeta principal de búsqueda -->
        <div class="coffee-search-card">
            <!-- Header con icono -->
            <div class="search-header">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h1>Buscar Producto</h1>
                <p>Encuentra cualquier producto del menú por su ID</p>
            </div>

            <!-- Formulario de búsqueda -->
            <form method="POST" action="{{ route('libros.store') }}" class="search-form">
                @csrf
                
                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-hashtag input-icon"></i>
                        <input type="text" id="id" name="id" class="form-control" required placeholder=" ">
                        <label for="id" class="input-label">ID del Producto</label>
                        <div class="input-helper">
                            <i class="fas fa-info-circle"></i>
                            Ingresa el código único del producto
                        </div>
                    </div>
                </div>

                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    Buscar Producto
                </button>
            </form>

            <!-- Información adicional -->
            <div class="search-info">
                <div class="info-item">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <h4>¿No conoces el ID?</h4>
                        <p>Revisa la lista completa de productos para encontrar el código que necesitas</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-list"></i>
                    <div>
                        <h4>Ver todos los productos</h4>
                        <p>Accede al menú completo para explorar todos los productos disponibles</p>
                    </div>
                </div>
            </div>

            <!-- Enlaces de navegación -->
            <div class="search-footer">
                <a href="{{ url()->previous() }}" class="nav-link">
                    <i class="fas fa-arrow-left"></i>
                    Volver Atrás
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-list"></i>
                    Ver Menú Completo
                </a>
            </div>
        </div>

        <!-- Panel de resultados (se muestra dinámicamente) -->
        <div class="results-panel">
            <div class="results-placeholder">
                <div class="placeholder-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <h3>Resultados de Búsqueda</h3>
                <p>Los detalles del producto aparecerán aquí después de realizar la búsqueda</p>
                <div class="placeholder-features">
                    <div class="feature">
                        <i class="fas fa-check"></i>
                        <span>Información completa del producto</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check"></i>
                        <span>Detalles de precio y disponibilidad</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check"></i>
                        <span>Opciones de edición rápida</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
    <div class="coffee-alert success">
        <div class="alert-content">
            <i class="fas fa-check-circle"></i>
            <div>
                <h4>¡Búsqueda Exitosa!</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        <button class="alert-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="coffee-alert error">
        <div class="alert-content">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <h4>Error en la Búsqueda</h4>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button class="alert-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
</div>

<style>
    .coffee-search-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8f4f0 0%, #f5f1e8 50%, #f0e6d6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    /* Elementos decorativos */
    .coffee-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .coffee-bean {
        position: absolute;
        width: 20px;
        height: 10px;
        background: #8B4513;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 15s infinite linear;
    }

    .bean-1 {
        top: 15%;
        left: 8%;
        animation-delay: 0s;
    }

    .bean-2 {
        top: 65%;
        right: 12%;
        animation-delay: 3s;
    }

    .bean-3 {
        bottom: 25%;
        left: 12%;
        animation-delay: 6s;
    }

    .bean-4 {
        bottom: 60%;
        right: 8%;
        animation-delay: 9s;
    }

    .search-magnifier {
        position: absolute;
        top: 20%;
        right: 15%;
        transform: rotate(15deg);
        opacity: 0.05;
    }

    .magnifier-glass {
        width: 60px;
        height: 60px;
        border: 8px solid #8B4513;
        border-radius: 50%;
        position: relative;
    }

    .glass-handle {
        position: absolute;
        bottom: -25px;
        right: -25px;
        width: 40px;
        height: 8px;
        background: #8B4513;
        border-radius: 4px;
        transform: rotate(45deg);
    }

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(-100vh) rotate(360deg);
        }
    }

    /* Contenedor principal */
    .search-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 1200px;
        width: 100%;
        position: relative;
        z-index: 2;
    }

    /* Tarjeta de búsqueda */
    .coffee-search-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Header de búsqueda */
    .search-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .search-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
    }

    .search-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .search-header h1 {
        color: #5D4037;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .search-header p {
        color: #8D6E63;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Formulario */
    .search-form {
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .input-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #8B4513;
        font-size: 1.1rem;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .form-control {
        width: 100%;
        padding: 15px 15px 15px 50px;
        border: 2px solid #E0E0E0;
        border-radius: 12px;
        font-size: 1.1rem;
        background: white;
        transition: all 0.3s ease;
        color: #5D4037;
        font-weight: 600;
        text-align: center;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        transform: translateY(-2px);
    }

    .form-control:focus + .input-label,
    .form-control:not(:placeholder-shown) + .input-label {
        top: -8px;
        left: 45px;
        font-size: 0.8rem;
        background: white;
        padding: 0 8px;
        color: #8B4513;
    }

    .input-label {
        position: absolute;
        left: 50px;
        top: 50%;
        transform: translateY(-50%);
        color: #9E9E9E;
        font-size: 1rem;
        transition: all 0.3s ease;
        pointer-events: none;
        background: transparent;
    }

    .input-helper {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #8D6E63;
        font-size: 0.85rem;
        margin-top: 8px;
        padding: 8px 12px;
        background: #FFF8E1;
        border-radius: 8px;
        border-left: 3px solid #FFD54F;
    }

    .input-helper i {
        color: #FFA000;
    }

    /* Botón de búsqueda */
    .search-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 1rem;
        box-shadow: 0 5px 20px rgba(139, 69, 19, 0.3);
    }

    .search-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.4);
        background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
    }

    .search-btn:active {
        transform: translateY(-1px);
    }

    /* Información adicional */
    .search-info {
        background: #F5F5F5;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-item i {
        color: #8B4513;
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .info-item h4 {
        color: #5D4037;
        margin: 0 0 4px 0;
        font-size: 0.95rem;
    }

    .info-item p {
        color: #8D6E63;
        margin: 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    /* Footer de búsqueda */
    .search-footer {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .nav-link {
        color: #8B4513;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .nav-link:hover {
        color: #5D4037;
        background: #F5F5F5;
        transform: translateY(-2px);
    }

    /* Panel de resultados */
    .results-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .results-placeholder {
        text-align: center;
        color: #8D6E63;
    }

    .placeholder-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #D7CCC8 0%, #BCAAA4 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .placeholder-icon i {
        font-size: 3rem;
        color: #8D6E63;
    }

    .results-placeholder h3 {
        color: #5D4037;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .results-placeholder p {
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    .placeholder-features {
        text-align: left;
        max-width: 250px;
        margin: 0 auto;
    }

    .feature {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0.8rem;
        color: #8D6E63;
    }

    .feature i {
        color: #8B4513;
        font-size: 0.9rem;
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
        align-items: flex-start;
        gap: 15px;
        max-width: 400px;
        z-index: 1000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid;
    }

    .coffee-alert.success {
        border-left-color: #4CAF50;
    }

    .coffee-alert.error {
        border-left-color: #F44336;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content i {
        font-size: 1.5rem;
        margin-top: 2px;
    }

    .success .alert-content i {
        color: #4CAF50;
    }

    .error .alert-content i {
        color: #F44336;
    }

    .alert-content h4 {
        margin: 0 0 8px 0;
        font-size: 1rem;
    }

    .success .alert-content h4 {
        color: #2E7D32;
    }

    .error .alert-content h4 {
        color: #C62828;
    }

    .alert-content p,
    .error-list {
        margin: 0;
        color: #666;
        font-size: 0.9rem;
    }

    .error-list {
        padding-left: 15px;
    }

    .error-list li {
        margin-bottom: 3px;
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

    /* Responsive */
    @media (max-width: 968px) {
        .search-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .results-panel {
            min-height: 300px;
        }
    }

    @media (max-width: 768px) {
        .coffee-search-container {
            padding: 1rem;
        }

        .coffee-search-card,
        .results-panel {
            padding: 2rem 1.5rem;
        }

        .search-header h1 {
            font-size: 1.7rem;
        }

        .search-icon {
            width: 60px;
            height: 60px;
        }

        .search-icon i {
            font-size: 2rem;
        }

        .search-footer {
            flex-direction: column;
            gap: 0.5rem;
        }

        .placeholder-icon {
            width: 80px;
            height: 80px;
        }

        .placeholder-icon i {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cerrar alertas automáticamente
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

        // Efecto de focus en el input
        const searchInput = document.getElementById('id');
        if (searchInput) {
            searchInput.setAttribute('placeholder', ' ');
            
            searchInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            searchInput.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });

            // Auto-focus al cargar la página
            setTimeout(() => {
                searchInput.focus();
            }, 500);
        }

        // Efecto de búsqueda simulada (solo visual)
        const searchForm = document.querySelector('.search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const searchBtn = this.querySelector('.search-btn');
                const originalText = searchBtn.innerHTML;
                
                // Efecto de carga
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
                searchBtn.disabled = true;
                
                // Restaurar después de 2 segundos (solo para demo)
                setTimeout(() => {
                    searchBtn.innerHTML = originalText;
                    searchBtn.disabled = false;
                }, 2000);
            });
        }
    });
</script>

@endsection