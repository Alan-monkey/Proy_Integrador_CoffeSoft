@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Gestión de Backups</h4>
                    <div>
                        <a href="{{ route('backups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Backup
                        </a>
                        <a href="{{ route('backups.restore.form') }}" class="btn btn-success">
                            <i class="fas fa-upload"></i> Restaurar Backup
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tamaño</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backups as $backup)
                                <tr>
                                    <td>{{ $backup['name'] }}</td>
                                    <td>
                                        {{-- Usa el helper que creamos --}}
                                        {{ formatBytes($backup['size']) }}
                                    </td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('backups.download', $backup['name']) }}" 
                                               class="btn btn-sm btn-success" title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('backups.restore.form', $backup['name']) }}" 
                                               class="btn btn-sm btn-warning" title="Restaurar">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                            <form action="{{ route('backups.delete', $backup['name']) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar este backup?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay backups disponibles</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection