@extends ('layouts.app2')
@section('content')
<div class="login-container">
    <!-- Elementos decorativos de café -->
    <div class="coffee-elements">
        <div class="coffee-cup cup-1">
            <div class="cup-top"></div>
            <div class="cup-body"></div>
            <div class="cup-handle"></div>
            <div class="steam s1"></div>
            <div class="steam s2"></div>
            <div class="steam s3"></div>
        </div>
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header con logo de cafetería -->
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-coffee"></i>
                    <h1>Café Sofft</h1>
                </div>
                <p class="welcome-text">Bienvenido de nuevo</p>
            </div>

            <!-- Formulario de login -->
            <form method="POST" action="{{ route('login') }}" class="login-form">
    @csrf
    
    <div class="form-group">
        <div class="input-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-input" placeholder="Correo electrónico" required>
        </div>
    </div>

    <div class="form-group">
        <div class="input-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="password" name="password" class="form-input" placeholder="Contraseña" required>
        </div>
    </div>

    <button type="submit" class="login-btn">
        <i class="fas fa-sign-in-alt"></i>
        Iniciar Sesión
    </button>
</form>


            <!-- Mensajes de error -->
            @if ($errors->any())
            <div class="error-alert">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="error-content">
                    <h4>Error de acceso</h4>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Footer del login -->
            <div class="login-footer">
                <p>¿Primera vez aquí? <a href="#" class="footer-link">Contáctanos</a></p>
            </div>
        </div>
    </div>
</div>

<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #8B4513 0%, #5D4037 50%, #3E2723 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Elementos decorativos de café */
    .coffee-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    /* Taza de café animada */
    .coffee-cup {
        position: absolute;
        top: 10%;
        right: 10%;
        transform: scale(0.8);
    }

    .cup-top {
        width: 80px;
        height: 20px;
        background: #6F4E37;
        border-radius: 50%;
        position: relative;
        z-index: 2;
    }

    .cup-body {
        width: 70px;
        height: 60px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B5A2B 100%);
        border-radius: 0 0 35px 35px;
        position: relative;
        top: -10px;
    }

    .cup-handle {
        width: 25px;
        height: 40px;
        border: 8px solid #6F4E37;
        border-left: none;
        border-radius: 0 20px 20px 0;
        position: absolute;
        right: -20px;
        top: 15px;
    }

    .steam {
        position: absolute;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        animation: steam-animation 3s infinite ease-in-out;
    }

    .s1 {
        width: 15px;
        height: 15px;
        top: -25px;
        left: 20px;
        animation-delay: 0s;
    }

    .s2 {
        width: 12px;
        height: 12px;
        top: -35px;
        left: 32px;
        animation-delay: 0.5s;
    }

    .s3 {
        width: 10px;
        height: 10px;
        top: -30px;
        left: 45px;
        animation-delay: 1s;
    }

    @keyframes steam-animation {
        0%, 100% {
            transform: translateY(0) scale(1);
            opacity: 0.8;
        }
        50% {
            transform: translateY(-20px) scale(1.2);
            opacity: 0.4;
        }
    }

    /* Granos de café */
    .coffee-bean {
        position: absolute;
        width: 20px;
        height: 10px;
        background: #3E2723;
        border-radius: 50%;
        opacity: 0.3;
        animation: float-bean 8s infinite linear;
    }

    .bean-1 {
        top: 20%;
        left: 5%;
        animation-delay: 0s;
    }

    .bean-2 {
        top: 60%;
        left: 8%;
        animation-delay: 2s;
    }

    .bean-3 {
        top: 80%;
        right: 5%;
        animation-delay: 4s;
    }

    @keyframes float-bean {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(-100vh) rotate(360deg);
        }
    }

    /* Contenedor principal del login */
    .login-wrapper {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 420px;
        padding: 20px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Header del login */
    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .logo i {
        font-size: 2.5rem;
        color: #8B4513;
    }

    .logo h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #5D4037;
        margin: 0;
    }

    .welcome-text {
        color: #6D4C41;
        font-size: 1.1rem;
        margin: 0;
        opacity: 0.8;
    }

    /* Formulario */
    .login-form {
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        color: #8B4513;
        font-size: 1.1rem;
        z-index: 2;
    }

    .form-input {
        width: 100%;
        padding: 15px 15px 15px 45px;
        border: 2px solid #E0E0E0;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        color: #5D4037;
    }

    .form-input:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        transform: translateY(-2px);
    }

    .form-input::placeholder {
        color: #9E9E9E;
    }

    /* Botón de login */
    .login-btn {
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
        margin-top: 10px;
    }

    .login-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
    }

    .login-btn:active {
        transform: translateY(-1px);
    }

    /* Alertas de error */
    .error-alert {
        background: linear-gradient(135deg, #FF5252 0%, #D32F2F 100%);
        color: white;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    .error-alert i {
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .error-content h4 {
        margin: 0 0 8px 0;
        font-size: 1rem;
    }

    .error-list {
        margin: 0;
        padding-left: 15px;
    }

    .error-list li {
        font-size: 0.9rem;
        margin-bottom: 3px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Footer del login */
    .login-footer {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #E0E0E0;
    }

    .login-footer p {
        color: #6D4C41;
        margin: 0;
        font-size: 0.95rem;
    }

    .footer-link {
        color: #8B4513;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .footer-link:hover {
        color: #5D4037;
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-wrapper {
            padding: 15px;
        }
        
        .login-card {
            padding: 30px 20px;
        }
        
        .coffee-cup {
            transform: scale(0.6);
            right: 5%;
        }
        
        .logo h1 {
            font-size: 1.7rem;
        }
        
        .logo i {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 25px 15px;
        }
        
        .coffee-cup {
            display: none;
        }
    }
</style>

<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection