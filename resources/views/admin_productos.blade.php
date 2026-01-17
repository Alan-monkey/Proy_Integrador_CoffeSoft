<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">

    <h1 class="text-center mb-4">Administrador de Productos</h1>

    <!-- FORMULARIO DE PRODUCTOS -->
    <div class="card mb-4">
        <div class="card-header">Registrar / Editar Producto</div>
        <div class="card-body">
            <form id="formProducto" enctype="multipart/form-data">

                <input type="hidden" id="id">

                <div class="mb-3">
                    <label class="form-label">Nombre del producto</label>
                    <input type="text" id="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Precio</label>
                    <input type="number" id="precio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <input type="file" id="imagen" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                <button type="button" class="btn btn-secondary" id="btnLimpiar">Limpiar</button>

            </form>
        </div>
    </div>

    <!-- TABLA -->
    <div class="card">
        <div class="card-header">Productos Registrados</div>
        <div class="card-body">

            <table class="table table-bordered table-striped" id="tablaProductos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>
</div>

<script>
// URL de tu API
const API = "http://localhost:3000";

// ======================= CARGAR PRODUCTOS =========================
async function cargarProductos() {
    const res = await fetch(API + "/productos");
    const productos = await res.json();

    const tbody = document.querySelector("#tablaProductos tbody");
    tbody.innerHTML = "";

    productos.forEach(p => {
        tbody.innerHTML += `
            <tr>
                <td>${p.id}</td>
                <td><img src="${p.imagen}" width="80"></td>
                <td>${p.nombre}</td>
                <td>$${p.precio}</td>
                <td>${p.descripcion || ""}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editar(${p.id})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminar(${p.id})">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

// ======================= GUARDAR / ACTUALIZAR =========================
document.getElementById("formProducto").addEventListener("submit", async e => {
    e.preventDefault();

    const id = document.getElementById("id").value;
    const nombre = document.getElementById("nombre").value;
    const precio = document.getElementById("precio").value;
    const descripcion = document.getElementById("descripcion").value;
    const imagen = document.getElementById("imagen").files[0];

    const formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("precio", precio);
    formData.append("descripcion", descripcion);
    if (imagen) formData.append("imagen", imagen);

    let metodo = id ? "PUT" : "POST";
    let url = id ? `${API}/producto/${id}` : `${API}/producto`;

    const res = await fetch(url, {
        method: metodo,
        body: formData
    });

    const data = await res.json();

    alert(data.message);
    limpiar();
    cargarProductos();
});

// ======================= EDITAR =========================
async function editar(id) {
    const res = await fetch(API + "/productos");
    const productos = await res.json();
    const p = productos.find(x => x.id === id);

    document.getElementById("id").value = p.id;
    document.getElementById("nombre").value = p.nombre;
    document.getElementById("precio").value = p.precio;
    document.getElementById("descripcion").value = p.descripcion;
}

// ======================= ELIMINAR =========================
async function eliminar(id) {
    if (!confirm("¿Seguro que deseas eliminar este producto?")) return;

    const res = await fetch(`${API}/producto/${id}`, {
        method: "DELETE"
    });

    const data = await res.json();
    alert(data.message);
    cargarProductos();
}

// ======================= LIMPIAR FORMULARIO =========================
function limpiar() {
    document.getElementById("id").value = "";
    document.getElementById("formProducto").reset();
}

document.getElementById("btnLimpiar").addEventListener("click", limpiar);

// Inicializar tabla
cargarProductos();

</script>

</body>
</html>
