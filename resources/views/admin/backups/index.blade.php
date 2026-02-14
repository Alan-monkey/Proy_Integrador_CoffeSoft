@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-database"></i> Gestión de Backups</h4>
                    <div>
                        <!-- Cambiamos el enlace por un botón que abre el modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passwordModal">
                            <i class="fas fa-plus"></i> Nuevo Backup
                        </button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#restorePasswordModal" class="btn btn-success">
                            <i class="fas fa-upload"></i> Restaurar Backup
                        </button>
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
                                            <button type="button" 
                                               class="btn btn-sm btn-success" onclick="showDownloadModal('{{ $backup['name'] }}')">
                                                <i class="fas fa-download"></i>
                                            </button>




                                            <button type="button" data-bs-toggle="modal" data-bs-target="#restorePasswordModal" 
                                               class="btn btn-sm btn-warning" title="Restaurar">
                                                <i class="fas fa-upload"></i>
                                            </button>


                                            <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="showDeleteModal('{{ $backup['name'] }}')"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

<script>
function showDownloadModal(filename) {
    document.getElementById('download_filename').value = filename;
    document.getElementById('download_backup_name').textContent = filename;
    
    var modal = new bootstrap.Modal(document.getElementById('downloadPasswordModal'));
    modal.show();
}

function showDeleteModal(filename) {
    document.getElementById('delete_filename').value = filename;
    document.getElementById('delete_backup_name').textContent = filename;
    
    var modal = new bootstrap.Modal(document.getElementById('deletePasswordModal'));
    modal.show();
}
</script>

<!-- Modal de verificación de contraseña backup -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="passwordModalLabel">
                    <i class="fas fa-lock"></i> Verificar identidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form action="{{ route('backups.verify-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Para acceder a la creación de backups, por favor confirma tu identidad:</p>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <strong>Contraseña</strong>
                        </label>
                        <input type="password" 
                               class="form-control @if(session('password_error')) is-invalid @endif" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="off"
                               placeholder="Ingresa tu contraseña">
                        
                        @if(session('password_error'))
                            <div class="invalid-feedback">
                                {{ session('password_error') }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <small>Esta verificación adicional protege contra accesos no autorizados.</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Verificar y continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para recuperacion de contraseñas para restauracion de backup -->
<div class="modal fade" id="restorePasswordModal" tabindex="-1" aria-labelledby="restorePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="restorePasswordModalLabel">
                    <i class="fas fa-lock"></i> Verificar identidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form action="{{ route('backups.verify-password-restore') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Para acceder a la restauración de backups, por favor confirma tu identidad:</p>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <strong>Contraseña</strong>
                        </label>
                        <input type="password" 
                               class="form-control @if(session('restore_password_error')) is-invalid @endif" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="off"
                               placeholder="Ingresa tu contraseña">
                        
                        @if(session('restore_password_error'))
                            <div class="invalid-feedback">
                                {{ session('restore_password_error') }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <small>Esta verificación adicional protege contra accesos no autorizados.</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Verificar y continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para DESCARGAR BACKUP -->
<div class="modal fade" id="downloadPasswordModal" tabindex="-1" aria-labelledby="downloadPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="downloadPasswordModalLabel">
                    <i class="fas fa-lock"></i> Verificar identidad para descargar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form action="{{ route('backups.verify-password-download') }}" method="POST" id="downloadVerifyForm">
                @csrf
                <input type="hidden" name="backup_filename" id="download_filename" value="">
                
                <div class="modal-body">
                    <p>Para descargar este backup, por favor confirma tu identidad:</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-download"></i>
                        <strong>Backup:</strong> <span id="download_backup_name"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="download_password" class="form-label">
                            <strong>Contraseña</strong>
                        </label>
                        <input type="password" 
                               class="form-control @if(session('download_password_error')) is-invalid @endif" 
                               id="download_password" 
                               name="password" 
                               required 
                               autocomplete="off"
                               placeholder="Ingresa tu contraseña">
                        
                        @if(session('download_password_error'))
                            <div class="invalid-feedback">
                                {{ session('download_password_error') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-check"></i> Verificar y descargar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ELIMINAR BACKUP -->
<div class="modal fade" id="deletePasswordModal" tabindex="-1" aria-labelledby="deletePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePasswordModalLabel">
                    <i class="fas fa-lock"></i> Verificar identidad para eliminar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form action="{{ route('backups.verify-password-delete') }}" method="POST" id="deleteVerifyForm">
                @csrf
                <input type="hidden" name="backup_filename" id="delete_filename" value="">
                
                <div class="modal-body">
                    <p>Para eliminar este backup, por favor confirma tu identidad:</p>
                    
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>¡Advertencia!</strong> Esta acción no se puede deshacer.
                        <br>
                        <strong>Backup:</strong> <span id="delete_backup_name"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">
                            <strong>Contraseña</strong>
                        </label>
                        <input type="password" 
                               class="form-control @if(session('delete_password_error')) is-invalid @endif" 
                               id="delete_password" 
                               name="password" 
                               required 
                               autocomplete="off"
                               placeholder="Ingresa tu contraseña">
                        
                        @if(session('delete_password_error'))
                            <div class="invalid-feedback">
                                {{ session('delete_password_error') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check"></i> Verificar y eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Script para abrir el modal automáticamente si hay error de contraseña -->
@if(session('password_error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('passwordModal'));
        modal.show();
    });
</script>
@endif

<!-- Script para abrir el modal automáticamente si hay error de contraseña -->
@if(session('restore_password_error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('restorePasswordModal'));
        modal.show();
    });
</script>
@endif

<!-- Scripts para abrir modales con errores -->
@if(session('download_password_error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('downloadPasswordModal'));
        modal.show();
    });
</script>
@endif

@if(session('delete_password_error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('deletePasswordModal'));
        modal.show();
    });
</script>
@endif

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