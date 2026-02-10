@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>
                        <i class="fas fa-info-circle"></i> Detalles del Backup
                        <small class="text-muted">{{ $metadata['backup_name'] }}</small>
                    </h4>
                    <div>
                        <a href="{{ route('backups.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('backups.download', $filename) }}" class="btn btn-primary">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th width="40%">Nombre del backup:</th>
                                            <td>{{ $metadata['backup_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Archivo:</th>
                                            <td><code>{{ $filename }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de creación:</th>
                                            <td>{{ $metadata['created_at'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Base de datos:</th>
                                            <td>
                                                <span class="badge bg-info">{{ $metadata['database'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Formato:</th>
                                            <td>
                                                <span class="badge bg-secondary">{{ $metadata['format'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tamaño del archivo:</th>
                                            <td>{{ formatBytes($file_size) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fecha del archivo:</th>
                                            <td>{{ $file_date }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-user"></i> Información del Usuario</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 64px; height: 64px; border-radius: 50%; font-size: 24px;">
                                                {{ strtoupper(substr($metadata['created_by'], 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">{{ $metadata['created_by'] }}</h4>
                                            @if(isset($metadata['created_by_email']))
                                                <p class="text-muted mb-1">{{ $metadata['created_by_email'] }}</p>
                                            @endif
                                            @if(isset($metadata['created_by_id']))
                                                <small class="text-muted">ID: {{ $metadata['created_by_id'] }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($metadata['collections_details']) && !empty($metadata['collections_details']))
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-database"></i> Colecciones Incluidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Colección</th>
                                            <th class="text-end">Documentos</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($metadata['collections_details'] as $collection)
                                        <tr>
                                            <td>
                                                <code>{{ $collection['name'] }}</code>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($collection['documents_count']) }}
                                            </td>
                                            <td>
                                                @if($collection['documents_count'] > 0)
                                                    <span class="badge bg-success">Exportada</span>
                                                @else
                                                    <span class="badge bg-warning">Vacía</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-end">{{ number_format($metadata['total_documents'] ?? 0) }}</th>
                                            <th>{{ count($metadata['collections_details']) }} colecciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($metadata['system_info']))
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-server"></i> Información del Sistema</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6>PHP</h6>
                                            <h4 class="text-primary">{{ $metadata['system_info']['php_version'] ?? 'N/A' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6>Laravel</h6>
                                            <h4 class="text-primary">{{ $metadata['system_info']['laravel_version'] ?? 'N/A' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6>Servidor</h6>
                                            <p class="text-muted mb-0">{{ $metadata['system_info']['server'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('backups.restore.form', $filename) }}" class="btn btn-warning">
                            <i class="fas fa-upload"></i> Restaurar este Backup
                        </a>
                        <form action="{{ route('backups.delete', $filename) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar el backup \'{{ $metadata['backup_name'] }}\'?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Eliminar Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush