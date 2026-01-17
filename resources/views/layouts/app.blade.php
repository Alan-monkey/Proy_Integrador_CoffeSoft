<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Café Sofft - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --cafe-oscuro: #8B4513;
            --cafe-medio: #A0522D;
            --cafe-claro: #D2691E;
            --beige: #F5F5DC;
            --crema: #FFF8DC;
            --marron: #5D4037;
            --dorado: #D4AF37;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--beige) 0%, var(--crema) 100%);
            color: var(--marron);
            min-height: 100vh;
        }

        /* Navegación estilo cafetería */
        .navbar {
            background: linear-gradient(135deg, var(--cafe-oscuro) 0%, var(--cafe-medio) 100%);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--dorado) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .navbar-nav {
            gap: 15px;
        }

        .nav-link {
            color: var(--beige) !important;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--dorado) !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background-color: var(--dorado);
            color: var(--cafe-oscuro) !important;
            font-weight: 600;
        }

        .navbar-toggler {
            border: 2px solid var(--beige);
            color: var(--beige) !important;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }

        /* Botón de cerrar sesión */
        .nav-link button {
            background: none;
            border: none;
            color: inherit;
            font-weight: inherit;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link button:hover {
            background: none;
            transform: none;
        }

        /* Contenedor principal */
        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(139, 69, 19, 0.1);
            margin-top: 2rem;
            margin-bottom: 2rem;
            padding: 2rem;
            min-height: 70vh;
        }

        /* Sección de inicio */
        .inicio {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1rem;
        }

        .inicio .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1200px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .inicio .content {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .inicio .text {
                flex: 1;
                text-align: left;
                padding-right: 3rem;
            }

            .inicio img {
                max-width: 45%;
                height: auto;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(139, 69, 19, 0.2);
            }
        }

        .inicio img:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease;
        }

        .inicio h1 {
            color: var(--cafe-oscuro);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .inicio p {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--marron);
            margin-bottom: 2rem;
        }

        .inicio button {
            padding: 12px 30px;
            background: linear-gradient(135deg, var(--cafe-oscuro) 0%, var(--cafe-medio) 100%);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .inicio button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
        }

        /* Sección de servicios */
        .services {
            padding: 4rem 1rem;
            text-align: center;
            background: rgba(139, 69, 19, 0.05);
            border-radius: 15px;
            margin: 2rem 0;
        }

        .services h2 {
            color: var(--cafe-oscuro);
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 2.2rem;
        }

        .services p {
            color: var(--marron);
            margin-bottom: 3rem;
            font-size: 1.1rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .services img {
            max-width: 100%;
            border-radius: 12px;
            margin-top: 20px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .services img:hover {
            transform: scale(1.05);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        /* Galería */
        .gallery-dulce {
            padding: 4rem 1rem;
            background: linear-gradient(135deg, var(--cafe-oscuro) 0%, var(--marron) 100%);
            border-radius: 15px;
            margin: 2rem 0;
        }

        .gallery-dulce h2 {
            color: var(--dorado);
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 2.2rem;
        }

        .gallery-dulce p {
            margin-bottom: 3rem;
            color: var(--beige);
            font-size: 1.1rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .gallery-dulce img {
            max-width: 100%;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .gallery-dulce img:hover {
            transform: scale(1.05);
        }

        /* Efectos de transición para las imágenes */
        .gallery-dulce img,
        .services img {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            object-fit: cover;
            width: 100%;
        }

        .gallery-dulce img:hover,
        .services img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Uniformidad en Galería */
        .gallery-dulce .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .gallery-dulce {
            padding: 4rem 1rem;
            text-align: center;
        }

        .gallery-dulce .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            justify-items: center;
        }

        .gallery-item {
            text-align: center;
            max-width: 300px;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .gallery-description {
            margin-top: 15px;
            font-size: 1rem;
            color: var(--beige);
            font-weight: 500;
        }

        /* Ajustar espaciado general de imágenes */
        .gallery-dulce .grid,
        .services .grid {
            padding: 0 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Espaciado adicional entre las imágenes */
        .gallery-item img,
        .services img {
            margin: 10px 0;
        }

        /* Espaciado del contenido adicional */
        .additional-info {
            padding: 4rem 2rem;
            background: linear-gradient(135deg, var(--cafe-claro) 0%, var(--cafe-medio) 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(139, 69, 19, 0.2);
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .additional-info::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.1"><path d="M30,30 Q50,10 70,30 T90,50 T70,70 T50,90 T30,70 T10,50 T30,30 Z" fill="%23D4AF37"/></svg>');
            background-size: 200px;
            opacity: 0.1;
        }

        .additional-info h2 {
            color: var(--beige);
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .list .list-group-item {
            color: var(--beige);
            background-color: rgba(93, 64, 55, 0.7);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            margin-bottom: 10px;
            font-weight: 500;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .list .list-group-item:hover {
            background-color: rgba(93, 64, 55, 0.9);
            transform: translateX(5px);
        }

        /* Elementos decorativos de café */
        .coffee-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .coffee-bean {
            position: absolute;
            width: 20px;
            height: 10px;
            background: var(--cafe-oscuro);
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
            top: 30%;
            right: 10%;
            animation-delay: 3s;
        }

        .bean-3 {
            bottom: 20%;
            left: 15%;
            animation-delay: 6s;
        }

        .bean-4 {
            bottom: 40%;
            right: 5%;
            animation-delay: 9s;
        }

        .bean-5 {
            top: 60%;
            left: 8%;
            animation-delay: 12s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.1;
            }
            50% {
                opacity: 0.2;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0.1;
            }
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--cafe-oscuro) 0%, var(--marron) 100%);
            color: var(--beige);
            padding: 2rem 1rem;
            text-align: center;
            margin-top: 3rem;
        }

        .footer p {
            margin: 0;
            font-size: 1rem;
        }

        /* Responsividad para pantallas pequeñas */
        @media (max-width: 768px) {
            .gallery-dulce .grid,
            .services .grid {
                padding: 0 0.5rem;
            }
            
            .inicio h1 {
                font-size: 2rem;
            }
            
            .services h2,
            .gallery-dulce h2 {
                font-size: 1.8rem;
            }
            
            .container {
                padding: 1.5rem;
            }
        }
    </style>
  </head>
  <body>
    <!-- Elementos decorativos de café -->
    <div class="coffee-elements">
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
        <div class="coffee-bean bean-4"></div>
        <div class="coffee-bean bean-5"></div>
    </div>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <i class="fas fa-coffee"></i> Café Sofft
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">

          @php
            $usuario = Auth::guard('usuarios')->user();
          @endphp

          @if ($usuario && $usuario->user_tipo == '0')
            <li class="nav-item">
              <a class="nav-link active" href="/libros/crear">
                <i class="fas fa-plus-circle"></i> Crear
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/libros/leer">
                <i class="fas fa-list"></i> Leer
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/libros/eliminar">
                <i class="fas fa-trash-alt"></i> Eliminar
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/libros/consultar') }}">
                <i class="fas fa-search"></i> Consultar por ID
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/libros/registrarse') }}">
                <i class="fas fa-user-plus"></i> Registrar Usuario
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/carrito') }}">
                <i class="fas fa-shopping-cart"></i> Carrito de compras
              </a>
            </li>

          @else
            <!-- Usuarios que NO son tipo 0 -->
            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/libros/consultar') }}">
                <i class="fas fa-shopping-cart"></i> Carrito
              </a>
            </li>
          @endif

            <li class="nav-item">
              <form action="{{ route('logout') }}" method="POST">
                @csrf 
                <button class="nav-link" type="submit">
                  <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        @yield('content')
        
        <!-- Sección de bienvenida -->
        <section class="inicio">
            <div class="content">
                <div class="text">
                    <h1>Bienvenido a Café Sofft</h1>
                    <p>Disfruta de la mejor experiencia en gestión de cafetería con nuestro sistema integral. Desde el control de inventario hasta la gestión de pedidos, tenemos todo lo que necesitas para hacer crecer tu negocio.</p>
                    <button>Descubre Más</button>
                </div>
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Café Sofft">
            </div>
        </section>

        <!-- Sección de servicios -->
        <section class="services">
            <h2>Nuestros Servicios</h2>
            <p>Ofrecemos una amplia gama de servicios para gestionar eficientemente tu cafetería y brindar la mejor experiencia a tus clientes.</p>
            <div class="grid">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Gestión de Inventario">
                    <p class="gallery-description">Gestión de Inventario</p>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Control de Pedidos">
                    <p class="gallery-description">Control de Pedidos</p>
                </div>
            </div>
        </section>

        <!-- Información adicional -->
        <section class="additional-info">
            <h2>Características del Sistema</h2>
            <div class="list">
                <div class="list-group">
                    <div class="list-group-item">
                        <i class="fas fa-check-circle me-2"></i> Gestión completa de productos
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-check-circle me-2"></i> Control de inventario en tiempo real
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-check-circle me-2"></i> Sistema de pedidos eficiente
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-check-circle me-2"></i> Reportes y análisis detallados
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2023 Café Sofft - Sistema de Gestión. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  </body>
</html>