@extends('layouts.app')
@section('content')

<div class="coffeeshop-container">
    @if ($user)
        <div class="welcome-section text-center">
            <div class="coffee-cup-icon">
                <i class="fas fa-coffee"></i>
            </div>
            <h1 class="welcome-title">¡Hola, {{ $user->nombre }}!</h1>
            <h2 class="welcome-subtitle">Bienvenido a CoffeSoft</h2>
            
            <div class="alert alert-coffee">
                <i class="fas fa-mug-hot"></i> ¡Bienvenido a nuestra aplicación!
            </div>

            <div class="user-type-badge">
                <i class="fas fa-user-tag"></i> 
                {{ $user->user_tipo == '0' ? 'Administrador' : 'Invitado' }}
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle"></i> No has iniciado sesión.
        </div>
    @endif

@if ($user && $user->user_tipo == '0')
    <div class="mt-3">
        <button class="coffee-btn" data-bs-toggle="modal" data-bs-target="#backupModal">
            <i class="fas fa-database"></i> Respaldar Base de Datos
        </button>
    </div>
@endif

    <section class="coffee-hero">
        <div class="coffee-shop-scene">
            <div class="coffee-counter">
                <div class="coffee-machine">
                    <div class="machine-top"></div>
                    <div class="machine-body">
                        <div class="steam"></div>
                    </div>
                </div>
                <div class="coffee-cups">
                    <div class="cup"></div>
                    <div class="cup"></div>
                    <div class="cup"></div>
                </div>
            </div>
        </div>
        
        <div class="hero-content">
            <h1 class="hero-title">CoffeSoft</h1>
            <p class="hero-description">
                CoffeSoft es una herramienta tecnológica que permite automatizar los procesos principales de una cafetería, 
                centralizando la información en una plataforma digital moderna, segura y fácil de usar.
            </p>
            <button class="coffee-btn">
                <i class="fas fa-mug-hot"></i> Contáctanos
            </button>
        </div>
    </section>

    <section class="coffee-features">
        <h2 class="section-title">Nuestro Enfoque</h2>
        <p class="section-subtitle">
            Creamos soluciones adaptadas a las necesidades de tu cafetería, 
            utilizando las últimas tendencias en tecnología.
        </p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h3>Gestión de Pedidos</h3>
                <p>Controla y organiza todos los pedidos de manera eficiente</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gestión de Clientes</h3>
                <p>Mantén un registro completo de tu clientela</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Reportes y Análisis</h3>
                <p>Toma decisiones basadas en datos reales</p>
            </div>
        </div>
    </section>

    <section class="coffee-gallery">
        <h2 class="section-title">Nuestro Entorno</h2>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="{{ asset('Backend/assets/img/compu2.jpeg')}}" alt="Interfaz CoffeSoft">
                <div class="gallery-overlay">
                    <span>Sistema de Gestión</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="{{ asset('Backend/assets/img/compu.jpeg')}}" alt="Dashboard CoffeSoft">
                <div class="gallery-overlay">
                    <span>Panel de Control</span>
                </div>
            </div>
        </div>
    </section>

    <div class="floating-elements">
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
    </div>
</div>

<style>
    .coffeeshop-container {
        background: linear-gradient(135deg, #f5f1e8 0%, #e8dfce 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Welcome Section */
    .welcome-section {
        padding: 3rem 1rem;
        background: rgba(139, 69, 19, 0.1);
        border-radius: 0 0 30px 30px;
        margin-bottom: 2rem;
        position: relative;
    }

    .coffee-cup-icon {
        font-size: 3rem;
        color: #8B4513;
        margin-bottom: 1rem;
        animation: float 3s ease-in-out infinite;
    }

    .welcome-title {
        color: #5D4037;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .welcome-subtitle {
        color: #8B4513;
        font-size: 1.5rem;
        margin-bottom: 2rem;
        font-weight: 300;
    }

    .alert-coffee {
        background: rgba(139, 69, 19, 0.9);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 1rem 2rem;
        display: inline-block;
        margin: 1rem 0;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
    }

    .user-type-badge {
        background: linear-gradient(135deg, #D7CCC8 0%, #BCAAA4 100%);
        color: #5D4037;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        display: inline-block;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Coffee Hero Section */
    .coffee-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .coffee-shop-scene {
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .coffee-counter {
        position: relative;
        width: 300px;
        height: 200px;
        background: #A1887F;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .coffee-machine {
        position: relative;
        width: 80px;
        height: 120px;
    }

    .machine-top {
        width: 100%;
        height: 30px;
        background: #37474F;
        border-radius: 5px 5px 0 0;
    }

    .machine-body {
        width: 100%;
        height: 90px;
        background: #546E7A;
        border-radius: 0 0 5px 5px;
        position: relative;
    }

    .steam {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 20px;
        background: rgba(255,255,255,0.8);
        border-radius: 50%;
        animation: steam 2s infinite;
    }

    .coffee-cups {
        display: flex;
        gap: 10px;
        position: absolute;
        bottom: 10px;
    }

    .cup {
        width: 30px;
        height: 40px;
        background: #FFD54F;
        border-radius: 0 0 10px 10px;
        position: relative;
    }

    .cup::before {
        content: '';
        position: absolute;
        top: -5px;
        left: 0;
        width: 100%;
        height: 10px;
        background: #FFD54F;
        border-radius: 50%;
    }

    .hero-content {
        flex: 1;
        padding: 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        color: #5D4037;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .hero-description {
        font-size: 1.2rem;
        color: #6D4C41;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .coffee-btn {
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 25px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
    }

    .coffee-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
    }

    /* Features Section */
    .coffee-features {
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.7);
        margin: 2rem auto;
        border-radius: 30px;
        max-width: 1200px;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        color: #5D4037;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .section-subtitle {
        text-align: center;
        color: #6D4C41;
        font-size: 1.2rem;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    .feature-icon {
        font-size: 2.5rem;
        color: #8B4513;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        color: #5D4037;
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }

    .feature-card p {
        color: #6D4C41;
        line-height: 1.6;
    }

    /* Gallery Section */
    .coffee-gallery {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    .gallery-item img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(139, 69, 19, 0.9);
        color: white;
        padding: 1rem;
        text-align: center;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover .gallery-overlay {
        transform: translateY(0);
    }

    /* Floating Elements */
    .floating-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .coffee-bean {
        position: absolute;
        width: 20px;
        height: 10px;
        background: #5D4037;
        border-radius: 50%;
        opacity: 0.3;
    }

    .bean-1 {
        top: 20%;
        left: 10%;
        animation: float-bean 6s ease-in-out infinite;
    }

    .bean-2 {
        top: 60%;
        right: 15%;
        animation: float-bean 8s ease-in-out infinite 1s;
    }

    .bean-3 {
        bottom: 30%;
        left: 20%;
        animation: float-bean 7s ease-in-out infinite 2s;
    }

    /* Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes steam {
        0% { transform: translateX(-50%) scale(0.5); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateX(-50%) translateY(-30px) scale(1.2); opacity: 0; }
    }

    @keyframes float-bean {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .coffee-hero {
            flex-direction: column;
            text-align: center;
        }
        
        .hero-title {
            font-size: 2.5rem;
        }
        
        .welcome-title {
            font-size: 2rem;
        }
    }
</style>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="modal fade" id="backupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-database"></i> Respaldo de Base de Datos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p>¿Deseas descargar un respaldo completo de la base de datos?</p>
                <small class="text-muted">El archivo se descargará automáticamente</small>
            </div>

            <div class="modal-footer">
                <button class="coffee-btn" onclick="window.location.href='/backup/mongo'">
                    <i class="fas fa-download"></i> Descargar respaldo
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection