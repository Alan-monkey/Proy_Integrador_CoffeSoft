@extends('layouts.app')
@section('content')

<div class="card" style="width: 18rem;">
  <div class="card-body">

    <h5 class="card-title">Añadir un producto</h5>

<form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Nombre del producto</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Precio</label>
        <input type="number" step="0.01" name="precio" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control" required></textarea>
    </div>

    <div class="form-group">
        <label>Imagen (opcional)</label>
        <input type="file" name="imagen" class="form-control">
    </div>

    <button class="btn btn-primary">Guardar</button>

</form>

@if (session('success'))
<div class="alert alert-success mt-2">
    {{ session('success') }}
</div>
@endif

</div>
</div>

@endsection
