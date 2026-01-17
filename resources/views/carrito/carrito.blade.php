<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <title>Café Sofft - Menú Digital</title>
  <style>
    :root {
      --cafe-oscuro: #8B4513;
      --cafe-medio: #A0522D;
      --cafe-claro: #D2691E;
      --beige: #F5F5DC;
      --crema: #FFF8DC;
      --marron: #5D4037;
      --dorado: #D4AF37;
      --text-light: #FFFFFF;
      --text-dark: #333333;
      --shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--cafe-oscuro) 0%, var(--marron) 100%);
      color: var(--text-light);
      min-height: 100vh;
      line-height: 1.6;
    }

    /* Header Styles */
    .header {
      background: linear-gradient(135deg, var(--cafe-oscuro) 0%, var(--cafe-medio) 100%);
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo-icon {
      font-size: 2.5rem;
      color: var(--dorado);
    }

    .logo h1 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-light);
    }

    .btn-carrito {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 50px;
      color: var(--text-light);
      padding: 12px 24px;
      cursor: pointer;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }

    .btn-carrito:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-2px);
    }

    .carrito-count {
      background: var(--dorado);
      color: var(--marron);
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      font-weight: bold;
    }

    /* Main Content */
    .contenedor {
      padding: 2rem;
      max-width: 1400px;
      margin: 0 auto;
    }

    .titulo-seccion {
      font-size: 2.5rem;
      margin-bottom: 2rem;
      text-align: center;
      color: var(--text-light);
      position: relative;
    }

    .titulo-seccion::after {
      content: '';
      display: block;
      width: 100px;
      height: 4px;
      background: var(--dorado);
      margin: 15px auto;
      border-radius: 2px;
    }

    /* Filtros y Búsqueda */
    .filtros-container {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .buscador {
      flex: 1;
      min-width: 300px;
      max-width: 500px;
      position: relative;
    }

    .buscador input {
      width: 100%;
      padding: 15px 50px 15px 20px;
      border: none;
      border-radius: 50px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      color: var(--text-light);
      font-size: 1rem;
      box-shadow: var(--shadow);
    }

    .buscador input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .buscador input:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.15);
    }

    .buscador-icono {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--dorado);
    }

    .categorias {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .btn-categoria {
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      color: var(--text-light);
      padding: 10px 20px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .btn-categoria:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    .btn-categoria.activa {
      background: var(--dorado);
      color: var(--marron);
      border-color: var(--dorado);
    }

    /* Loading State */
    .loading {
      text-align: center;
      padding: 3rem;
      grid-column: 1 / -1;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(255, 255, 255, 0.3);
      border-top: 4px solid var(--dorado);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Product Grid */
    .productos {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
    }

    .producto {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      overflow: hidden;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .producto:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.4);
      background: rgba(255, 255, 255, 0.15);
    }

    .producto-imagen {
      width: 100%;
      height: 220px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .producto:hover .producto-imagen {
      transform: scale(1.05);
    }

    .producto-info {
      padding: 1.5rem;
    }

    .producto-categoria {
      position: absolute;
      top: 15px;
      left: 15px;
      background: var(--dorado);
      color: var(--marron);
      padding: 5px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .producto-nombre {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text-light);
    }

    .producto-descripcion {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.95rem;
      margin-bottom: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .producto-precio {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--dorado);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn-agregar-rapido {
      background: var(--dorado);
      color: var(--marron);
      border: none;
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-agregar-rapido:hover {
      background: #FFC107;
      transform: scale(1.05);
    }

    /* Modal Styles */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .modal.activo {
      opacity: 1;
      visibility: visible;
    }

    .modal-contenido {
      background: linear-gradient(135deg, var(--cafe-medio) 0%, var(--marron) 100%);
      width: 90%;
      max-width: 500px;
      border-radius: 25px;
      padding: 2.5rem;
      position: relative;
      box-shadow: 0 25px 50px rgba(0,0,0,0.5);
      transform: translateY(20px);
      transition: transform 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal.activo .modal-contenido {
      transform: translateY(0);
    }

    .cerrar {
      position: absolute;
      top: 20px;
      right: 25px;
      font-size: 2rem;
      cursor: pointer;
      color: var(--dorado);
      transition: color 0.3s ease;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .cerrar:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .modal-imagen {
      width: 100%;
      max-width: 300px;
      height: 200px;
      object-fit: cover;
      border-radius: 15px;
      display: block;
      margin: 0 auto 1.5rem;
      box-shadow: var(--shadow);
    }

    .modal-nombre {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
      text-align: center;
      color: var(--text-light);
    }

    .modal-descripcion {
      color: rgba(255, 255, 255, 0.9);
      text-align: center;
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .modal-precio {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dorado);
      text-align: center;
      margin-bottom: 2rem;
    }

    .cantidad-control {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px;
      margin-bottom: 2rem;
    }

    .btn-cantidad {
      background: var(--dorado);
      color: var(--marron);
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 1.5rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
    }

    .btn-cantidad:hover {
      background: #FFC107;
      transform: scale(1.1);
    }

    .cantidad-numero {
      font-size: 1.8rem;
      font-weight: 700;
      min-width: 60px;
      text-align: center;
      color: var(--text-light);
    }

    .btn-agregar {
      background: linear-gradient(135deg, var(--dorado) 0%, #FFC107 100%);
      color: var(--marron);
      border: none;
      padding: 18px 30px;
      border-radius: 50px;
      font-size: 1.2rem;
      font-weight: 700;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
    }

    .btn-agregar:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    /* Carrito Modal */
    .contenido-carrito {
      background: linear-gradient(135deg, var(--cafe-medio) 0%, var(--marron) 100%);
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      border-radius: 25px;
      padding: 2.5rem;
      box-shadow: 0 25px 50px rgba(0,0,0,0.5);
      position: relative;
      overflow-y: auto;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .titulo-carrito {
      font-size: 2rem;
      margin-bottom: 2rem;
      text-align: center;
      color: var(--text-light);
    }

    .lista-carrito {
      margin-bottom: 2rem;
    }

    .item-carrito {
      display: flex;
      align-items: center;
      padding: 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
      margin-bottom: 1rem;
    }

    .item-carrito:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }

    .item-imagen {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
      margin-right: 1.5rem;
    }

    .item-info {
      flex: 1;
    }

    .item-nombre {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text-light);
    }

    .item-precio {
      color: var(--dorado);
      font-weight: 600;
    }

    .item-cantidad {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-top: 0.5rem;
    }

    .btn-eliminar {
      background: #E57373;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.9rem;
      transition: background 0.3s ease;
    }

    .btn-eliminar:hover {
      background: #EF5350;
    }

    .resumen-carrito {
      background: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 15px;
      margin-bottom: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .total-carrito {
      font-size: 1.8rem;
      font-weight: 700;
      text-align: center;
      color: var(--dorado);
    }

    .btn-pago {
      background: linear-gradient(135deg, var(--dorado) 0%, #FFC107 100%);
      color: var(--marron);
      border: none;
      padding: 18px;
      border-radius: 15px;
      font-size: 1.2rem;
      font-weight: 700;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
    }

    .btn-pago:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    .btn-pago:disabled {
      background: rgba(255, 255, 255, 0.2);
      color: rgba(255, 255, 255, 0.5);
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Modal Pago */
    .opciones-pago {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin: 2rem 0;
    }

    .btn-metodo-pago {
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      color: white;
      padding: 20px;
      border-radius: 15px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .btn-metodo-pago:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: var(--dorado);
      transform: translateY(-2px);
    }

    .btn-cancelar {
      background: transparent;
      border: 2px solid rgba(255, 255, 255, 0.2);
      color: white;
      padding: 15px;
      border-radius: 15px;
      font-size: 1.1rem;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s ease;
    }

    .btn-cancelar:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    /* Empty State */
    .carrito-vacio {
      text-align: center;
      padding: 3rem;
      color: rgba(255, 255, 255, 0.7);
    }

    .carrito-vacio-icono {
      font-size: 4rem;
      margin-bottom: 1.5rem;
      color: var(--dorado);
    }

    /* No Results */
    .sin-resultados {
      text-align: center;
      padding: 3rem;
      grid-column: 1 / -1;
    }

    .sin-resultados i {
      font-size: 4rem;
      color: var(--dorado);
      margin-bottom: 1rem;
    }

    /* Error State */
    .error-api {
      text-align: center;
      padding: 3rem;
      grid-column: 1 / -1;
      background: rgba(239, 83, 80, 0.1);
      border-radius: 15px;
      border: 1px solid rgba(239, 83, 80, 0.3);
    }

    .error-api i {
      font-size: 4rem;
      color: #EF5350;
      margin-bottom: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .header {
        padding: 1rem;
      }
      
      .logo h1 {
        font-size: 1.5rem;
      }
      
      .contenedor {
        padding: 1rem;
      }
      
      .productos {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      }
      
      .modal-contenido, .contenido-carrito {
        width: 95%;
        padding: 2rem;
      }

      .filtros-container {
        flex-direction: column;
      }

      .buscador {
        min-width: 100%;
      }

      .opciones-pago {
        grid-template-columns: 1fr;
      }
    }

    /* Animaciones */
    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOut {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="logo">
      <span class="logo-icon">☕</span>
      <h1>Café Sofft</h1>
    </div>
    <button id="btnCarrito" class="btn-carrito">
      🛒 Carrito <span id="contadorCarrito" class="carrito-count">0</span>
    </button>
  </header>

  <main class="contenedor">
    <h2 class="titulo-seccion">Menú de Cafetería</h2>
    
    <!-- Filtros y Búsqueda -->
    <div class="filtros-container">
      <div class="buscador">
        <input type="text" id="buscador" placeholder="Buscar productos, bebidas, postres..." />
        <span class="buscador-icono">🔍</span>
      </div>
      <div class="categorias">
        <button class="btn-categoria activa" data-categoria="todos">Todos</button>
        <button class="btn-categoria" data-categoria="bebidas">Bebidas</button>
        <button class="btn-categoria" data-categoria="comida">Comida</button>
        <button class="btn-categoria" data-categoria="postres">Postres</button>
        <button class="btn-categoria" data-categoria="especiales">Especiales</button>
      </div>
    </div>

    <!-- Grid de Productos -->
    <div id="listaProductos" class="productos">
      <div class="loading">
        <div class="loading-spinner"></div>
        <p>Cargando productos...</p>
      </div>
    </div>
  </main>

  <!-- Modal Detalle Producto -->
  <div id="modalProducto" class="modal">
    <div class="modal-contenido">
      <span id="cerrarModal" class="cerrar">&times;</span>
      <img id="modalImagen" src="" alt="" class="modal-imagen">
      <h3 id="modalNombre" class="modal-nombre"></h3>
      <p id="modalDescripcion" class="modal-descripcion"></p>
      <h4 id="modalPrecio" class="modal-precio"></h4>
      <div class="cantidad-control">
        <button id="menos" class="btn-cantidad">-</button>
        <span id="cantidad" class="cantidad-numero">1</span>
        <button id="mas" class="btn-cantidad">+</button>
      </div>
      <button id="agregarCarrito" class="btn-agregar">Agregar al Carrito</button>
    </div>
  </div>

  <!-- Modal Carrito -->
  <div id="modalCarrito" class="modal">
    <div class="contenido-carrito">
      <span id="cerrarCarrito" class="cerrar">&times;</span>
      <h2 class="titulo-carrito">Tu Carrito 🛒</h2>
      <div id="listaCarrito" class="lista-carrito">
        <div class="carrito-vacio">
          <div class="carrito-vacio-icono">🛒</div>
          <p>Tu carrito está vacío</p>
          <p style="margin-top: 1rem; opacity: 0.7;">¡Agrega algunos productos deliciosos!</p>
        </div>
      </div>
      <div class="resumen-carrito">
        <p id="totalCarrito" class="total-carrito"></p>
      </div>
      <button id="btnPagar" class="btn-pago" disabled>💳 Proceder al Pago</button>
    </div>
  </div>

  <!-- Modal Pago -->
  <div id="modalPago" class="modal">
    <div class="modal-contenido">
      <span id="cerrarPago" class="cerrar">&times;</span>
      <h2 class="modal-nombre">💰 Selecciona tu Método de Pago</h2>
      <div class="opciones-pago">
        <button id="pagoEfectivo" class="btn-metodo-pago">💵 Pago en Efectivo</button>
        <button id="pagoTarjeta" class="btn-metodo-pago">💳 Pago con Tarjeta</button>
      </div>
      <button id="btnCerrarPago" class="btn-cancelar">Cancelar</button>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
    // ======================================================
    // 🔹 VARIABLES GLOBALES
    // ======================================================
    let productos = [];
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    let productoSeleccionado = null;
    let cantidad = 1;
    let categoriaActual = 'todos';
    let terminoBusqueda = '';

    // 🔹 ELEMENTOS DEL DOM
    const listaProductos = document.getElementById("listaProductos");
    const modalProducto = document.getElementById("modalProducto");
    const modalCarrito = document.getElementById("modalCarrito");
    const modalPago = document.getElementById("modalPago");
    const contadorCarrito = document.getElementById("contadorCarrito");
    const buscador = document.getElementById("buscador");
    const categorias = document.querySelectorAll(".btn-categoria");

    // ======================================================
    // 🌐 FUNCIONES API
    // ======================================================
    const API_BASE_URL = 'http://localhost:3000';

    async function cargarProductos() {
      try {
        mostrarLoading();
        
        const response = await fetch(`${API_BASE_URL}/productos`);
        
        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status}`);
        }
        
        productos = await response.json();
        renderProductos();
        
      } catch (error) {
        console.error('Error al cargar productos:', error);
        mostrarError('No se pudieron cargar los productos. Verifica que el servidor esté funcionando.');
      }
    }

    async function agregarProductoAlCarritoAPI(productoId, cantidad) {
      try {
        const response = await fetch(`${API_BASE_URL}/carrito`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            productoId: productoId,
            cantidad: cantidad
          })
        });

        if (!response.ok) {
          throw new Error('Error al agregar producto al carrito');
        }

        return await response.json();
      } catch (error) {
        console.error('Error API carrito:', error);
        // Fallback al localStorage
        return { success: true };
      }
    }

    async function obtenerCarritoAPI() {
      try {
        const response = await fetch(`${API_BASE_URL}/carrito`);
        
        if (!response.ok) {
          throw new Error('Error al obtener carrito');
        }
        
        return await response.json();
      } catch (error) {
        console.error('Error API carrito:', error);
        // Fallback al localStorage
        return carrito;
      }
    }

    // ======================================================
    // ✅ FUNCIONES BASE
    // ======================================================
    const actualizarContador = () => {
      const total = carrito.reduce((acc, p) => acc + p.cantidad, 0);
      contadorCarrito.textContent = total;
    };

    const mostrarLoading = () => {
      listaProductos.innerHTML = `
        <div class="loading">
          <div class="loading-spinner"></div>
          <p>Cargando productos...</p>
        </div>
      `;
    };

    const mostrarError = (mensaje) => {
      listaProductos.innerHTML = `
        <div class="error-api">
          <i>⚠️</i>
          <h3>Error de conexión</h3>
          <p>${mensaje}</p>
          <button onclick="cargarProductos()" style="margin-top: 1rem; padding: 10px 20px; background: var(--dorado); color: var(--marron); border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
            Reintentar
          </button>
        </div>
      `;
    };

    const filtrarProductos = () => {
      return productos.filter(producto => {
        const coincideCategoria = categoriaActual === 'todos' || producto.categoria === categoriaActual;
        const coincideBusqueda = producto.nombre.toLowerCase().includes(terminoBusqueda) || 
                               (producto.descripcion && producto.descripcion.toLowerCase().includes(terminoBusqueda));
        return coincideCategoria && coincideBusqueda;
      });
    };

    const renderProductos = () => {
      const productosFiltrados = filtrarProductos();
      listaProductos.innerHTML = "";

      if (productosFiltrados.length === 0) {
        listaProductos.innerHTML = `
          <div class="sin-resultados">
            <i>🔍</i>
            <h3>No se encontraron productos</h3>
            <p>Intenta con otros términos de búsqueda o cambia de categoría</p>
          </div>
        `;
        return;
      }

      productosFiltrados.forEach(p => {
        const card = document.createElement("div");
        card.classList.add("producto");
        card.innerHTML = `
          <span class="producto-categoria">${p.categoria ? p.categoria.toUpperCase() : 'GENERAL'}</span>
          <img src="${p.imagen || 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400'}" alt="${p.nombre}" class="producto-imagen">
          <div class="producto-info">
            <h3 class="producto-nombre">${p.nombre}</h3>
            <p class="producto-descripcion">${p.descripcion || 'Delicioso producto de nuestra cafetería'}</p>
            <div class="producto-precio">
              $${p.precio} MXN
              <button class="btn-agregar-rapido" data-id="${p.id}">
                + Agregar
              </button>
            </div>
          </div>
        `;
        
        // Click en la tarjeta para abrir modal
        card.addEventListener("click", (e) => {
          if (!e.target.classList.contains('btn-agregar-rapido')) {
            abrirModal(p);
          }
        });

        // Click en botón rápido
        const btnRapido = card.querySelector('.btn-agregar-rapido');
        btnRapido.addEventListener('click', (e) => {
          e.stopPropagation();
          agregarAlCarritoRapido(p);
        });

        listaProductos.appendChild(card);
      });
    };

    const abrirModal = (producto) => {
      productoSeleccionado = producto;
      document.getElementById("modalImagen").src = producto.imagen || 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400';
      document.getElementById("modalNombre").textContent = producto.nombre;
      document.getElementById("modalDescripcion").textContent = producto.descripcion || 'Delicioso producto de nuestra cafetería';
      document.getElementById("modalPrecio").textContent = `$${producto.precio} MXN`;
      cantidad = 1;
      document.getElementById("cantidad").textContent = cantidad;
      modalProducto.classList.add("activo");
    };

    // ======================================================
    // 🛒 FUNCIONES DEL CARRITO
    // ======================================================
    const agregarAlCarritoRapido = async (producto) => {
      try {
        // Intentar con API primero
        await agregarProductoAlCarritoAPI(producto.id, 1);
        
        // Si la API funciona, actualizar localStorage como backup
        const existe = carrito.find(item => item.id === producto.id);
        if (existe) {
          existe.cantidad += 1;
        } else {
          carrito.push({ ...producto, cantidad: 1 });
        }
        
        localStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarContador();
        mostrarNotificacion(`✅ ${producto.nombre} agregado al carrito`);
        
      } catch (error) {
        // Fallback a solo localStorage si la API falla
        const existe = carrito.find(item => item.id === producto.id);
        if (existe) {
          existe.cantidad += 1;
        } else {
          carrito.push({ ...producto, cantidad: 1 });
        }
        
        localStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarContador();
        mostrarNotificacion(`✅ ${producto.nombre} agregado al carrito (modo local)`);
      }
    };

    document.getElementById("agregarCarrito").addEventListener("click", async () => {
      if (!productoSeleccionado) return;

      try {
        // Intentar con API primero
        await agregarProductoAlCarritoAPI(productoSeleccionado.id, cantidad);
        
        // Si la API funciona, actualizar localStorage como backup
        const existe = carrito.find(item => item.id === productoSeleccionado.id);
        if (existe) {
          existe.cantidad += cantidad;
        } else {
          carrito.push({ ...productoSeleccionado, cantidad });
        }
        
        localStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarContador();
        modalProducto.classList.remove("activo");
        mostrarNotificacion(`✅ ${productoSeleccionado.nombre} agregado al carrito`);
        
      } catch (error) {
        // Fallback a solo localStorage si la API falla
        const existe = carrito.find(item => item.id === productoSeleccionado.id);
        if (existe) {
          existe.cantidad += cantidad;
        } else {
          carrito.push({ ...productoSeleccionado, cantidad });
        }
        
        localStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarContador();
        modalProducto.classList.remove("activo");
        mostrarNotificacion(`✅ ${productoSeleccionado.nombre} agregado al carrito (modo local)`);
      }
    });

    // ======================================================
    // 🛍️ MOSTRAR CARRITO
    // ======================================================
    const mostrarCarrito = () => {
      const listaCarrito = document.getElementById("listaCarrito");
      const btnPagar = document.getElementById("btnPagar");

      if (carrito.length === 0) {
        listaCarrito.innerHTML = `
          <div class="carrito-vacio">
            <div class="carrito-vacio-icono">🛒</div>
            <p>Tu carrito está vacío</p>
            <p style="margin-top: 1rem; opacity: 0.7;">¡Agrega algunos productos deliciosos!</p>
          </div>
        `;
        document.getElementById("totalCarrito").textContent = "";
        btnPagar.disabled = true;
        return;
      }

      listaCarrito.innerHTML = "";
      carrito.forEach((item, index) => {
        const div = document.createElement("div");
        div.classList.add("item-carrito");
        div.innerHTML = `
          <img src="${item.imagen || 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400'}" alt="${item.nombre}" class="item-imagen">
          <div class="item-info">
            <h4 class="item-nombre">${item.nombre}</h4>
            <p class="item-precio">$${item.precio} MXN c/u</p>
            <div class="item-cantidad">
              <span>Cantidad: ${item.cantidad}</span>
              <button class="btn-eliminar" data-index="${index}">Eliminar</button>
            </div>
          </div>
        `;
        listaCarrito.appendChild(div);
      });

      // Agregar event listeners a los botones eliminar
      document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const index = parseInt(e.target.dataset.index);
          carrito.splice(index, 1);
          localStorage.setItem("carrito", JSON.stringify(carrito));
          actualizarContador();
          mostrarCarrito();
        });
      });

      const total = carrito.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
      document.getElementById("totalCarrito").textContent = `Total: $${total} MXN`;
      btnPagar.disabled = false;
    };

    document.getElementById("btnCarrito").addEventListener("click", () => {
      mostrarCarrito();
      modalCarrito.classList.add("activo");
    });

    // ======================================================
    // 🔍 FILTROS Y BÚSQUEDA
    // ======================================================
    buscador.addEventListener("input", (e) => {
      terminoBusqueda = e.target.value.toLowerCase();
      renderProductos();
    });

    categorias.forEach(btn => {
      btn.addEventListener("click", () => {
        categorias.forEach(b => b.classList.remove("activa"));
        btn.classList.add("activa");
        categoriaActual = btn.dataset.categoria;
        renderProductos();
      });
    });

    // ======================================================
    // 💳 PAGO Y TICKET
    // ======================================================
    document.getElementById("btnPagar").addEventListener("click", () => {
      if (carrito.length === 0) return;
      modalCarrito.classList.remove("activo");
      modalPago.classList.add("activo");
    });

    const confirmarPago = (metodo) => {
      modalPago.classList.remove("activo");

      // Generar ticket
      let ticket = `
        <div style="font-family: Arial, sans-serif; max-width: 300px; margin: 0 auto; padding: 20px;">
          <h2 style="text-align: center; color: #8B4513;">☕ Café Sofft</h2>
          <p style="text-align: center; color: #666;">Ticket de Compra</p>
          <hr style="border: 1px solid #eee;">
          <p style="color: #333;"><strong>Método de pago:</strong> ${metodo}</p>
          <table style="width: 100%; margin: 15px 0; border-collapse: collapse;">
      `;
      
      carrito.forEach(item => {
        ticket += `
          <tr>
            <td style="padding: 5px 0; border-bottom: 1px dashed #eee;">${item.nombre}</td>
            <td style="padding: 5px 0; border-bottom: 1px dashed #eee; text-align: center;">${item.cantidad}x</td>
            <td style="padding: 5px 0; border-bottom: 1px dashed #eee; text-align: right;">$${item.precio * item.cantidad}</td>
          </tr>
        `;
      });
      
      const total = carrito.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
      ticket += `
          </table>
          <hr style="border: 1px solid #eee;">
          <h3 style="text-align: right; color: #8B4513;">Total: $${total} MXN</h3>
          <p style="text-align: center; margin-top: 20px; color: #666;">¡Gracias por tu compra! ☕</p>
          <p style="text-align: center; font-size: 12px; color: #999;">${new Date().toLocaleString()}</p>
        </div>
      `;

      const ventana = window.open("", "_blank");
      ventana.document.write(ticket);
      ventana.document.close();
      ventana.print();
      ventana.close();

      // Limpiar carrito
      carrito = [];
      localStorage.removeItem("carrito");
      actualizarContador();
      mostrarCarrito();
      
      mostrarNotificacion("¡Compra realizada con éxito! 🎉");
    };

    document.getElementById("pagoEfectivo").addEventListener("click", () => confirmarPago("Efectivo"));
    document.getElementById("pagoTarjeta").addEventListener("click", () => confirmarPago("Tarjeta"));

    // ======================================================
    // 🔧 CONTROLADORES DE EVENTOS
    // ======================================================
    // Control de cantidad
    document.getElementById("mas").addEventListener("click", () => {
      cantidad++;
      document.getElementById("cantidad").textContent = cantidad;
    });

    document.getElementById("menos").addEventListener("click", () => {
      if (cantidad > 1) {
        cantidad--;
        document.getElementById("cantidad").textContent = cantidad;
      }
    });

    // Cerrar modales
    document.getElementById("cerrarModal").addEventListener("click", () => {
      modalProducto.classList.remove("activo");
    });

    document.getElementById("cerrarCarrito").addEventListener("click", () => {
      modalCarrito.classList.remove("activo");
    });

    document.getElementById("cerrarPago").addEventListener("click", () => {
      modalPago.classList.remove("activo");
    });

    document.getElementById("btnCerrarPago").addEventListener("click", () => {
      modalPago.classList.remove("activo");
      modalCarrito.classList.add("activo");
    });

    // Cerrar modales al hacer clic fuera
    [modalProducto, modalCarrito, modalPago].forEach(modal => {
      modal.addEventListener("click", (e) => {
        if (e.target === modal) {
          modal.classList.remove("activo");
        }
      });
    });

    // ======================================================
    // 🎯 FUNCIONES ADICIONALES
    // ======================================================
    function mostrarNotificacion(mensaje) {
      const notificacion = document.createElement("div");
      notificacion.textContent = mensaje;
      notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--dorado);
        color: var(--marron);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: var(--shadow);
        z-index: 2000;
        animation: slideIn 0.3s ease;
        font-weight: 600;
      `;
      
      document.body.appendChild(notificacion);
      
      setTimeout(() => {
        notificacion.style.animation = "slideOut 0.3s ease";
        setTimeout(() => {
          document.body.removeChild(notificacion);
        }, 300);
      }, 3000);
    }

    // ======================================================
    // 🚀 INICIALIZACIÓN
    // ======================================================
    cargarProductos();
    actualizarContador();

    // Hacer la función cargarProductos disponible globalmente para el botón de reintentar
    window.cargarProductos = cargarProductos;
  });
  </script>
</body>
</html>