@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-database"></i> Gestión de Backups</h4>
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
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            @if (session('info'))
                                <div class="mt-1">{{ session('info') }}</div>
                            @endif
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

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Usuario actual:</strong> {{ Auth::guard('usuarios')->user()->nombre }}
                        ({{ Auth::guard('usuarios')->user()->email }})
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Backup</th>
                                    <th>Tamaño</th>
                                    <th>Fecha</th>
                                    <th>Creado por</th>
                                    <th>Base de datos</th>
                                    <th>Colecciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backups as $backup)
                                <tr>
                                    <td>
                                        <strong>{{ $backup['name'] }}</strong>
                                        <br>
                                        <small class="text-muted">Formato: {{ $backup['format'] }}</small>
                                    </td>
                                    <td>{{ formatBytes($backup['size']) }}</td>
                                    <td>
                                        {{ $backup['date'] }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($backup['date'])->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($backup['created_by'] != 'Desconocido')
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 32px; height: 32px; border-radius: 50%;">
                                                        {{ strtoupper(substr($backup['created_by'], 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong>{{ $backup['created_by'] }}</strong>
                                                    @if($backup['created_by_id'])
                                                        <br>
                                                        <small class="text-muted">ID: {{ $backup['created_by_id'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Desconocido</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $backup['database'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $backup['collections_count'] }} colecciones</span>
                                    </td>
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
                                                  onsubmit="return confirm('¿Eliminar el backup \'{{ $backup['name'] }}\'?\n\nCreado por: {{ $backup['created_by'] }}\nFecha: {{ $backup['date'] }}')">
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
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                        <h5>No hay backups disponibles</h5>
                                        <p class="text-muted">Crea tu primer backup haciendo clic en el botón "Nuevo Backup"</p>
                                    </td>
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

<!-- Modal para detalles del backup -->
<div class="modal fade" id="backupDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="backupDetailsContent">
                <!-- Los detalles se cargarán aquí via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    font-size: 14px;
    font-weight: bold;
}
.badge {
    font-size: 0.85em;
}
.table td {
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script>
function showBackupDetails(filename) {
    // Mostrar loading
    $('#backupDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del backup...</p>
        </div>
    `);
    
    // Mostrar modal
    var modal = new bootstrap.Modal(document.getElementById('backupDetailsModal'));
    modal.show();
    
    // Cargar detalles via AJAX
    $.ajax({
        url: '{{ url("backups/details") }}/' + filename,
        method: 'GET',
        success: function(response) {
            $('#backupDetailsContent').html(response);
        },
        error: function(xhr) {
            $('#backupDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error al cargar los detalles del backup.
                </div>
            `);
        }
    });
}

// Función para confirmar eliminación con más detalles
function confirmDelete(filename, createdBy, date) {
    return confirm(`¿Eliminar el backup "${filename}"?\n\nCreado por: ${createdBy}\nFecha: ${date}\n\nEsta acción no se puede deshacer.`);
}
</script>
@endpush