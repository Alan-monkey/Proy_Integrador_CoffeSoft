@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Crear Nuevo Backup</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('backups.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Backup (opcional)</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Ej: backup_diario">
                            <div class="form-text">Si se deja vacío, se usará la fecha actual</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Colecciones a incluir</label>
                            <div class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($collections as $collection)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="collections[]" value="{{ $collection }}" 
                                           id="col_{{ $collection }}" checked>
                                    <label class="form-check-label" for="col_{{ $collection }}">
                                        {{ $collection }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="selectAllCollections()">
                                    Seleccionar todas
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="deselectAllCollections()">
                                    Deseleccionar todas
                                </button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="format" class="form-label">Formato de exportación</label>
                                    <select class="form-select" id="format" name="format">
                                        <option value="json">JSON (recomendado)</option>
                                        <option value="csv">CSV</option>
                                        <option value="zip">ZIP (comprimido)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" 
                                               id="include_structure" name="include_structure" value="1">
                                        <label class="form-check-label" for="include_structure">
                                            Incluir estructura (índices)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> El backup se guardará en <code>storage/app/backups/</code>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('backups.index') }}" class="btn btn-secondary me-md-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Backup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Información</h5>
                </div>
                <div class="card-body">
                    <h6>Formatos disponibles:</h6>
                    <ul>
                        <li><strong>JSON:</strong> Ideal para MongoDB, mantiene la estructura de documentos</li>
                        <li><strong>CSV:</strong> Útil para importar en hojas de cálculo</li>
                        <li><strong>ZIP:</strong> Comprime todos los archivos en uno solo</li>
                    </ul>
                    
                    <h6 class="mt-3">Recomendaciones:</h6>
                    <ul>
                        <li>Realiza backups regularmente</li>
                        <li>Guarda los backups en un lugar seguro</li>
                        <li>Verifica que los backups se puedan restaurar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectAllCollections() {
        document.querySelectorAll('input[name="collections[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    }
    
    function deselectAllCollections() {
        document.querySelectorAll('input[name="collections[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
</script>
@endpush