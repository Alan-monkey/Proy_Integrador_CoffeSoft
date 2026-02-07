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
                    <form action="{{ route('backups.restore') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="backup_file" class="form-label">Seleccionar Backup</label>
                            <select class="form-select" id="backup_file" name="backup_file" required>
                                <option value="">Selecciona un backup...</option>
                                @foreach($backups as $backup)
                                <option value="{{ $backup }}" {{ $filename == $backup ? 'selected' : '' }}>
                                    {{ $backup }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Modo de restauración</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="restore_mode" 
                                       id="replace" value="replace" checked>
                                <label class="form-check-label" for="replace">
                                    <strong>Reemplazar</strong> - Elimina datos existentes y restaura desde backup
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="restore_mode" 
                                       id="merge" value="merge">
                                <label class="form-check-label" for="merge">
                                    <strong>Fusionar</strong> - Mantiene datos existentes y añade del backup
                                </label>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>¡Precaución!</strong> La restauración puede sobrescribir datos existentes.
                            Se recomienda hacer un backup antes de restaurar.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('backups.index') }}" class="btn btn-secondary me-md-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('¿Estás seguro de restaurar este backup?')">
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
@endsection