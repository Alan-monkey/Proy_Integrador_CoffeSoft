@extends('layouts.app')
@section('content')

<h3>Lista de productos</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Descripción</th>
            <th>Imagen</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productos as $p)
        <tr>
            <td>{{ $p->nombre }}</td>
            <td>${{ $p->precio }}</td>
            <td>{{ $p->descripcion }}</td>
            <td>
                @if($p->imagen)
                    <img src="{{ asset('storage/' . $p->imagen) }}" width="80">
                @else
                    Sin imagen
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection
