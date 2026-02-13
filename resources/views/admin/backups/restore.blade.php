@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Restaurar Backup</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <form action="{{ route('backups.restore') }}" method="POST" id="restoreForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="backup_file" class="form-label">Seleccionar Backup</label>
                            <select class="form-select" id="backup_file" name="backup_file" required onchange="loadBackupDetails(this.value)">
                                <option value="">Selecciona un backup...</option>
                                @foreach($backups as $backup)
                                <option value="{{ $backup }}" {{ isset($filename) && $filename == $backup ? 'selected' : '' }}>
                                    {{ $backup }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Información del backup seleccionado -->
                        <div id="backupInfo" style="display: none;" class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Información del Backup</h5>
                                    <div id="backupDetails"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selección de colecciones -->
                        <div class="mb-3" id="collectionsSection" style="display: none;">
                            <label class="form-label">Colecciones a restaurar</label>
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllCollections(true)">
                                    Seleccionar todas
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllCollections(false)">
                                    Limpiar selección
                                </button>
                            </div>
                            <div id="collectionsList" class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="text-muted">Primero selecciona un backup</div>
                            </div>
                            <div class="text-muted mt-1 small">
                                <span id="selectedCount">0</span> colecciones seleccionadas
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Modo de restauración</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="restore_mode" 
                                       id="replace" value="replace" checked>
                                <label class="form-check-label" for="replace">
                                    <strong>Reemplazar</strong> - Elimina datos existentes y restaura desde backup
                                </label>
                                <small class="text-muted d-block">Todas las colecciones seleccionadas serán limpiadas antes de restaurar</small>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="restore_mode" 
                                       id="merge" value="merge">
                                <label class="form-check-label" for="merge">
                                    <strong>Fusionar</strong> - Mantiene datos existentes y añade del backup
                                </label>
                                <small class="text-muted d-block">Los documentos con IDs duplicados serán actualizados</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>¡Precaución!</strong> 
                            <ul class="mt-2 mb-0">
                                <li>La restauración puede sobrescribir datos existentes</li>
                                <li>Se recomienda hacer un backup antes de restaurar</li>
                                <li>Verifica que el backup sea válido y corresponda a la base de datos actual</li>
                                <li>La restauración puede tardar varios minutos dependiendo del tamaño</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('backups.index') }}" class="btn btn-secondary me-md-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return validateAndConfirm()">
                                <i class="fas fa-upload"></i> Restaurar Backup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Advertencias</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Importante:</strong>
                        <ul class="mt-2">
                            <li>Verifica que el backup sea válido</li>
                            <li>La restauración puede tardar varios minutos</li>
                            <li>No interrumpas el proceso de restauración</li>
                            <li>Revisa los logs si hay errores</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let currentBackupData = null;

// Función para cargar detalles del backup
function loadBackupDetails(filename) {
    console.log('loadBackupDetails llamado con:', filename);
    
    if (!filename) {
        document.getElementById('backupInfo').style.display = 'none';
        document.getElementById('collectionsSection').style.display = 'none';
        return;
    }
    
    // Mostrar indicador de carga
    document.getElementById('collectionsList').innerHTML = '<div class="text-center">Cargando información del backup...</div>';
    document.getElementById('collectionsSection').style.display = 'block';
    
    // Obtener información del backup
    fetch(`/backups/test-restore/${filename}`)
        .then(response => {
            console.log('Respuesta recibida:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            currentBackupData = data;
            
            // Determinar las colecciones
            let collections = [];
            
            if (data.collections && Array.isArray(data.collections) && data.collections.length > 0) {
                collections = data.collections;
            } else if (data.debug && data.debug.files_in_zip && data.debug.files_in_zip.length > 0) {
                collections = data.debug.files_in_zip;
            } else if (data.collections_details && data.collections_details.length > 0) {
                collections = data.collections_details.map(c => c.name);
            }
            
            console.log('Colecciones encontradas:', collections);
            
            // Mostrar información general
            document.getElementById('backupInfo').style.display = 'block';
            let detailsHtml = `
                <p><strong>Backup:</strong> ${data.backup_name || filename}</p>
                <p><strong>Creado por:</strong> ${data.created_by || 'Desconocido'}</p>
                <p><strong>Fecha:</strong> ${data.created_at || 'Desconocida'}</p>
                <p><strong>Total documentos:</strong> ${data.total_documents || 0}</p>
                <p><strong>Colecciones encontradas:</strong> ${collections.length}</p>
            `;
            document.getElementById('backupDetails').innerHTML = detailsHtml;
            
            // Mostrar colecciones disponibles
            if (collections && collections.length > 0) {
                let collectionsHtml = '';
                collections.forEach(collection => {
                    // Buscar detalles de la colección
                    let docCount = '';
                    if (data.collections_details) {
                        const details = data.collections_details.find(c => c.name === collection);
                        if (details && details.documents_count) {
                            docCount = `<span class="text-muted"> (${details.documents_count} documentos)</span>`;
                        }
                    }
                    
                    collectionsHtml += `
                        <div class="form-check">
                            <input class="form-check-input collection-checkbox" type="checkbox" 
                                   name="collections_to_restore[]" value="${collection}" 
                                   id="col_${collection}" onchange="updateSelectedCount()">
                            <label class="form-check-label" for="col_${collection}">
                                ${collection} ${docCount}
                            </label>
                        </div>
                    `;
                });
                document.getElementById('collectionsList').innerHTML = collectionsHtml;
                updateSelectedCount();
            } else {
                document.getElementById('collectionsList').innerHTML = '<div class="text-warning">No se encontraron colecciones en este backup</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('collectionsList').innerHTML = '<div class="text-danger">Error al cargar información del backup</div>';
        });
}

function selectAllCollections(select) {
    const checkboxes = document.querySelectorAll('.collection-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = select;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.collection-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selected;
}

function validateAndConfirm() {
    const selectedCollections = document.querySelectorAll('.collection-checkbox:checked').length;
    
    if (selectedCollections === 0) {
        alert('Debes seleccionar al menos una colección para restaurar');
        return false;
    }
    
    const backupFile = document.getElementById('backup_file').value;
    if (!backupFile) {
        alert('Debes seleccionar un archivo de backup');
        return false;
    }
    
    const mode = document.querySelector('input[name="restore_mode"]:checked').value;
    const modeText = mode === 'replace' ? 'REEMPLAZAR' : 'FUSIONAR';
    
    return confirm(`¿Estás seguro de restaurar ${selectedCollections} colecciones en modo ${modeText}?\n\n` +
                  'Esta acción no se puede deshacer fácilmente.');
}

// Cargar detalles si hay un filename en la URL
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado');
    const select = document.getElementById('backup_file');
    console.log('Select element:', select);
    
    if (select && select.value) {
        console.log('Valor inicial del select:', select.value);
        loadBackupDetails(select.value);
    }
});
</script>
@endsection