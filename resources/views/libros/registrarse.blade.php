@extends('layouts.app')
@section('content')

<div class="coffee-register-container">
    <!-- Elementos decorativos -->
    <div class="coffee-elements">
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
        <div class="coffee-bean bean-4"></div>
        <div class="coffee-cup">
            <div class="cup-top"></div>
            <div class="cup-body"></div>
            <div class="cup-handle"></div>
        </div>
    </div>

    <div class="register-wrapper">
        <!-- Tarjeta principal -->
        <div class="coffee-register-card">
            <!-- Header con icono -->
            <div class="register-header">
                <div class="register-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Registro de Usuario</h1>
                <p>Crea una nueva cuenta para acceder al sistema</p>
            </div>

            <!-- Alertas -->
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

            @if (session('success'))
            <div class="coffee-alert success">
                <div class="alert-content">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <h4>¡Registro Exitoso!</h4>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                <button class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <!-- Formulario -->
            <form method="POST" action="{{ route('libros.registrar') }}" class="register-form" id="registerForm">
                @csrf
                
                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="user_name" name="user_name" class="form-control" value="abi" required placeholder=" ">
                        <label for="user_name" class="input-label">Nombre de Usuario</label>
                    </div>
                    <div class="input-helper">
                        <i class="fas fa-info-circle"></i>
                        El nombre de usuario debe ser único y tener al menos 3 caracteres
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="user_pass" name="user_pass" class="form-control" value="abi" required placeholder=" ">
                        <label for="user_pass" class="input-label">Contraseña</label>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Seguridad de la contraseña</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="user_pass_confirmation" name="user_pass_confirmation" class="form-control" value="abi" required placeholder=" ">
                        <label for="user_pass_confirmation" class="input-label">Confirmar Contraseña</label>
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-match" id="passwordMatch">
                        <i class="fas fa-check"></i>
                        <span>Las contraseñas coinciden</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-user-tag input-icon"></i>
                        <select id="user_tipo" name="user_tipo" class="form-control select-control" required>
                            <option value="0">Administrador</option>
                            <option value="1">Invitado</option>
                        </select>
                        <label for="user_tipo" class="select-label">Tipo de Usuario</label>
                    </div>
                    <div class="role-info">
                        <div class="role-option" data-role="0">
                            <i class="fas fa-crown"></i>
                            <div>
                                <strong>Administrador</strong>
                                <span>Acceso completo al sistema</span>
                            </div>
                        </div>
                        <div class="role-option" data-role="1">
                            <i class="fas fa-user"></i>
                            <div>
                                <strong>Invitado</strong>
                                <span>Acceso limitado a funciones básicas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i>
                    Registrar Usuario
                </button>
            </form>

            <!-- Información adicional -->
            <div class="register-info">
                <div class="info-item">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h4>Seguridad Garantizada</h4>
                        <p>Tus datos están protegidos con encriptación avanzada</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-coffee"></i>
                    <div>
                        <h4>Acceso Inmediato</h4>
                        <p>Podrás acceder al sistema inmediatamente después del registro</p>
                    </div>
                </div>
            </div>

            <!-- Enlaces de navegación -->
            <div class="register-footer">
                <a href="{{ url()->previous() }}" class="nav-link">
                    <i class="fas fa-arrow-left"></i>
                    Volver Atrás
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    Ver Usuarios Existentes
                </a>
            </div>
        </div>

        <!-- Panel lateral informativo -->
        <div class="info-panel">
            <div class="info-content">
                <div class="info-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <h2>Café Sofft</h2>
                <p class="welcome-text">Sistema de Gestión de Cafetería</p>
                
                <div class="features-list">
                    <div class="feature">
                        <i class="fas fa-cogs"></i>
                        <div>
                            <h4>Gestión Completa</h4>
                            <p>Control total sobre productos, inventario y ventas</p>
                        </div>
                    </div>
                    <div class="feature">
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <h4>Reportes Detallados</h4>
                            <p>Análisis y estadísticas en tiempo real</p>
                        </div>
                    </div>
                    <div class="feature">
                        <i class="fas fa-mobile-alt"></i>
                        <div>
                            <h4>Acceso Móvil</h4>
                            <p>Gestiona tu cafetería desde cualquier dispositivo</p>
                        </div>
                    </div>
                </div>

                <div class="user-types">
                    <h4>Tipos de Usuario Disponibles</h4>
                    <div class="type-card admin">
                        <i class="fas fa-crown"></i>
                        <div>
                            <h5>Administrador</h5>
                            <ul>
                                <li>Gestión completa de usuarios</li>
                                <li>Control de inventario</li>
                                <li>Reportes avanzados</li>
                                <li>Configuración del sistema</li>
                            </ul>
                        </div>
                    </div>
                    <div class="type-card guest">
                        <i class="fas fa-user"></i>
                        <div>
                            <h5>Invitado</h5>
                            <ul>
                                <li>Consulta de productos</li>
                                <li>Realización de pedidos</li>
                                <li>Historial personal</li>
                                <li>Acceso básico</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .coffee-register-container {
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

    .coffee-cup {
        position: absolute;
        top: 20%;
        right: 15%;
        transform: scale(0.8);
        opacity: 0.05;
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
    .register-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 1400px;
        width: 100%;
        position: relative;
        z-index: 2;
    }

    /* Tarjeta de registro */
    .coffee-register-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Header */
    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .register-icon {
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

    .register-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .register-header h1 {
        color: #5D4037;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .register-header p {
        color: #8D6E63;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Formulario */
    .register-form {
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .input-container {
        position: relative;
        margin-bottom: 0.5rem;
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
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        color: #5D4037;
        font-family: inherit;
    }

    .select-control {
        appearance: none;
        cursor: pointer;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        transform: translateY(-2px);
    }

    .form-control:focus + .input-label,
    .form-control:not(:placeholder-shown) + .input-label,
    .select-control:focus + .select-label,
    .select-control:valid + .select-label {
        top: -8px;
        left: 45px;
        font-size: 0.8rem;
        background: white;
        padding: 0 8px;
        color: #8B4513;
    }

    .input-label,
    .select-label {
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

    /* Toggle de contraseña */
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #8B4513;
        cursor: pointer;
        padding: 5px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .password-toggle:hover {
        background: #f5f5f5;
    }

    /* Indicador de fortaleza de contraseña */
    .password-strength {
        margin-top: 8px;
    }

    .strength-bar {
        width: 100%;
        height: 6px;
        background: #E0E0E0;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    .strength-fill.weak {
        width: 33%;
        background: #F44336;
    }

    .strength-fill.medium {
        width: 66%;
        background: #FF9800;
    }

    .strength-fill.strong {
        width: 100%;
        background: #4CAF50;
    }

    .strength-text {
        font-size: 0.8rem;
        color: #9E9E9E;
    }

    /* Indicador de coincidencia de contraseñas */
    .password-match {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4CAF50;
        font-size: 0.85rem;
        margin-top: 8px;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .password-match.visible {
        opacity: 1;
    }

    .password-match i {
        font-size: 0.8rem;
    }

    /* Información de roles */
    .role-info {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .role-option {
        flex: 1;
        padding: 1rem;
        border: 2px solid #E0E0E0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .role-option:hover {
        border-color: #8B4513;
        transform: translateY(-2px);
    }

    .role-option.active {
        border-color: #8B4513;
        background: #FFF8E1;
    }

    .role-option i {
        color: #8B4513;
        font-size: 1.2rem;
    }

    .role-option strong {
        color: #5D4037;
        font-size: 0.9rem;
    }

    .role-option span {
        color: #8D6E63;
        font-size: 0.8rem;
    }

    /* Botón de registro */
    .register-btn {
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

    .register-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.4);
        background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
    }

    /* Información adicional */
    .register-info {
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

    /* Footer */
    .register-footer {
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

    /* Panel de información */
    .info-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        overflow-y: auto;
        max-height: 80vh;
    }

    .info-content {
        text-align: center;
    }

    .info-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .info-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .info-content h2 {
        color: #5D4037;
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }

    .welcome-text {
        color: #8D6E63;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .features-list {
        text-align: left;
        margin-bottom: 2rem;
    }

    .feature {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .feature:hover {
        background: #e8f5e8;
        transform: translateX(5px);
    }

    .feature i {
        color: #8B4513;
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .feature h4 {
        color: #5D4037;
        margin: 0 0 4px 0;
        font-size: 0.95rem;
    }

    .feature p {
        color: #8D6E63;
        margin: 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .user-types h4 {
        color: #5D4037;
        margin-bottom: 1rem;
        text-align: center;
    }

    .type-card {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .type-card.admin {
        background: linear-gradient(135deg, #FFF8E1 0%, #FFECB3 100%);
        border-left: 4px solid #FFA000;
    }

    .type-card.guest {
        background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
        border-left: 4px solid #2196F3;
    }

    .type-card i {
        font-size: 1.5rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .type-card.admin i {
        color: #FFA000;
    }

    .type-card.guest i {
        color: #2196F3;
    }

    .type-card h5 {
        margin: 0 0 8px 0;
        color: #5D4037;
    }

    .type-card ul {
        margin: 0;
        padding-left: 15px;
        color: #8D6E63;
        font-size: 0.8rem;
    }

    .type-card li {
        margin-bottom: 2px;
    }

    /* Alertas */
    .coffee-alert {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 2rem;
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

    /* Responsive */
    @media (max-width: 968px) {
        .register-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .info-panel {
            max-height: none;
        }
    }

    @media (max-width: 768px) {
        .coffee-register-container {
            padding: 1rem;
        }

        .coffee-register-card,
        .info-panel {
            padding: 2rem 1.5rem;
        }

        .register-header h1 {
            font-size: 1.7rem;
        }

        .register-icon {
            width: 60px;
            height: 60px;
        }

        .register-icon i {
            font-size: 2rem;
        }

        .register-footer {
            flex-direction: column;
            gap: 0.5rem;
        }

        .role-info {
            flex-direction: column;
        }

        .info-icon {
            width: 60px;
            height: 60px;
        }

        .info-icon i {
            font-size: 2rem;
        }
    }
</style>

<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('user_pass');
        const confirmPasswordInput = document.getElementById('user_pass_confirmation');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        const passwordMatch = document.getElementById('passwordMatch');
        const userTypeSelect = document.getElementById('user_tipo');
        const roleOptions = document.querySelectorAll('.role-option');

        // Toggle visibilidad de contraseña
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        toggleConfirmPasswordBtn.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        // Verificar fortaleza de contraseña
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = 'Débil';
            let className = 'weak';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            if (strength >= 4) {
                text = 'Fuerte';
                className = 'strong';
            } else if (strength >= 2) {
                text = 'Media';
                className = 'medium';
            }

            strengthFill.className = 'strength-fill ' + className;
            strengthText.textContent = 'Seguridad: ' + text;
        });

        // Verificar coincidencia de contraseñas
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword === '') {
                passwordMatch.classList.remove('visible');
            } else if (password === confirmPassword) {
                passwordMatch.classList.add('visible');
                passwordMatch.innerHTML = '<i class="fas fa-check"></i><span>Las contraseñas coinciden</span>';
                passwordMatch.style.color = '#4CAF50';
            } else {
                passwordMatch.classList.add('visible');
                passwordMatch.innerHTML = '<i class="fas fa-times"></i><span>Las contraseñas no coinciden</span>';
                passwordMatch.style.color = '#F44336';
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Selección de rol visual
        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                const role = this.getAttribute('data-role');
                userTypeSelect.value = role;
                
                roleOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Sincronizar selección inicial
        userTypeSelect.addEventListener('change', function() {
            roleOptions.forEach(opt => opt.classList.remove('active'));
            document.querySelector(`.role-option[data-role="${this.value}"]`).classList.add('active');
        });

        // Inicializar selección
        userTypeSelect.dispatchEvent(new Event('change'));

        // Cerrar alertas manualmente
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.coffee-alert');
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        });

        // Auto-close alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.coffee-alert').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    });
</script>

@endsection