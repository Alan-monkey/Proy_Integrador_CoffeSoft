@extends('layouts.app')
@section('content')

<div class="coffee-form-container">
    <!-- Elementos decorativos -->
    <div class="coffee-elements">
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
        <div class="coffee-steam steam-1"></div>
        <div class="coffee-steam steam-2"></div>
        <div class="coffee-steam steam-3"></div>
    </div>

    <div class="form-wrapper">
        <!-- Tarjeta del formulario -->
        <div class="coffee-form-card">
            <!-- Header con icono -->
            <div class="form-header">
                <div class="header-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <h1>Añadir Nuevo Producto</h1>
                <p>Agrega un nuevo item al menú de tu cafetería</p>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('libros.store') }}" class="coffee-form">
                @csrf
                
                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-utensils input-icon"></i>
                        <input type="text" id="nombre" name="nombre" class="form-control" required placeholder=" ">
                        <label for="nombre" class="input-label">Nombre del Producto</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-align-left input-icon"></i>
                        <textarea id="descripcion" name="descripcion" class="form-control textarea-control" required placeholder=" " rows="3"></textarea>
                        <label for="descripcion" class="input-label">Descripción</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-user-chef input-icon"></i>
                        <input type="text" id="autor" name="autor" class="form-control" required placeholder=" ">
                        <label for="autor" class="input-label">Creador/Chef</label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-plus-circle"></i>
                    Agregar al Menú
                </button>
            </form>

            <!-- Enlace de regreso -->
            <div class="form-footer">
                <a href="{{ url()->previous() }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Menú Principal
                </a>
            </div>
        </div>

        <!-- Imagen decorativa -->
        <div class="form-decoration">
            <div class="decoration-content">
                <div class="coffee-mug">
                    <div class="mug-body">
                        <div class="coffee-liquid"></div>
                    </div>
                    <div class="mug-handle"></div>
                </div>
                <div class="floating-ingredients">
                    <div class="ingredient bean"></div>
                    <div class="ingredient sugar"></div>
                    <div class="ingredient cinnamon"></div>
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
                <h4>¡Producto Agregado!</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        <button class="alert-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
    <div class="coffee-alert error">
        <div class="alert-content">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <h4>Error de Validación</h4>
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
    .coffee-form-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8f4f0 0%, #f5f1e8 50%, #f0e6d6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    /* Elementos decorativos de fondo */
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
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .bean-2 {
        top: 60%;
        right: 10%;
        animation-delay: 5s;
    }

    .bean-3 {
        bottom: 20%;
        left: 15%;
        animation-delay: 10s;
    }

    .coffee-steam {
        position: absolute;
        background: rgba(139, 69, 19, 0.1);
        border-radius: 50%;
        animation: steam-float 8s infinite ease-in-out;
    }

    .steam-1 {
        width: 30px;
        height: 30px;
        top: 20%;
        right: 20%;
        animation-delay: 0s;
    }

    .steam-2 {
        width: 25px;
        height: 25px;
        top: 40%;
        left: 10%;
        animation-delay: 2s;
    }

    .steam-3 {
        width: 20px;
        height: 20px;
        bottom: 30%;
        right: 15%;
        animation-delay: 4s;
    }

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(-100vh) rotate(360deg);
        }
    }

    @keyframes steam-float {
        0%, 100% {
            transform: translateY(0) scale(1);
            opacity: 0.1;
        }
        50% {
            transform: translateY(-20px) scale(1.2);
            opacity: 0.05;
        }
    }

    /* Contenedor principal */
    .form-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 1000px;
        width: 100%;
        position: relative;
        z-index: 2;
    }

    /* Tarjeta del formulario */
    .coffee-form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Header del formulario */
    .form-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .header-icon {
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

    .header-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .form-header h1 {
        color: #5D4037;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: #8D6E63;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Grupos del formulario */
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

    .textarea-control + .input-icon {
        top: 25px;
        transform: none;
    }

    .form-control {
        width: 100%;
        padding: 15px 15px 15px 50px;
        border: 2px solid #E0E0E0;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        color: #5D4037;
        font-family: inherit;
    }

    .textarea-control {
        resize: vertical;
        min-height: 100px;
        padding-top: 20px;
        line-height: 1.5;
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

    .textarea-control + .input-label {
        top: 25px;
        transform: none;
    }

    /* Botón de envío */
    .submit-btn {
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

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.4);
        background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
    }

    .submit-btn:active {
        transform: translateY(-1px);
    }

    /* Footer del formulario */
    .form-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E0E0E0;
    }

    .back-link {
        color: #8B4513;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: #5D4037;
        transform: translateX(-5px);
    }

    /* Decoración lateral */
    .form-decoration {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .decoration-content {
        position: relative;
    }

    .coffee-mug {
        position: relative;
        width: 150px;
        height: 180px;
    }

    .mug-body {
        width: 120px;
        height: 140px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B5A2B 100%);
        border-radius: 0 0 60px 60px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .coffee-liquid {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        bottom: 10px;
        background: linear-gradient(135deg, #4E342E 0%, #3E2723 100%);
        border-radius: 0 0 50px 50px;
    }

    .mug-handle {
        position: absolute;
        right: -20px;
        top: 40px;
        width: 40px;
        height: 60px;
        border: 12px solid #6F4E37;
        border-left: none;
        border-radius: 0 20px 20px 0;
    }

    .floating-ingredients {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .ingredient {
        position: absolute;
        border-radius: 50%;
        animation: float-ingredient 6s infinite ease-in-out;
    }

    .bean {
        width: 12px;
        height: 6px;
        background: #3E2723;
        top: 20px;
        left: 30px;
        animation-delay: 0s;
    }

    .sugar {
        width: 8px;
        height: 8px;
        background: white;
        top: 50px;
        right: 40px;
        animation-delay: 2s;
    }

    .cinnamon {
        width: 10px;
        height: 10px;
        background: #D2691E;
        bottom: 30px;
        left: 50px;
        animation-delay: 4s;
    }

    @keyframes float-ingredient {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
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
    @media (max-width: 768px) {
        .coffee-form-container {
            padding: 1rem;
        }

        .form-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .coffee-form-card {
            padding: 2rem 1.5rem;
        }

        .form-decoration {
            display: none;
        }

        .form-header h1 {
            font-size: 1.7rem;
        }

        .header-icon {
            width: 60px;
            height: 60px;
        }

        .header-icon i {
            font-size: 2rem;
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

        // Mejorar los placeholders con labels flotantes
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            // Configurar placeholder vacío para la animación
            input.setAttribute('placeholder', ' ');
            
            // Verificar si hay valor al cargar (para casos de edición)
            if (input.value) {
                input.parentElement.classList.add('has-value');
            }
            
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    });
</script>

@endsection