@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-check-circle"></i> Enlace de Recuperación Generado
                </div>

                <div class="card-body">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check"></i> ¡Enlace de recuperación generado!</h5>
                        <p class="mb-0">Para el email: <strong>{{ $email }}</strong></p>
                    </div>

                    <div class="mb-4">
                        <h6>Haz clic en el botón para restablecer tu contraseña:</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ $reset_link }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-key"></i> Restablecer Contraseña Ahora
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <i class="fas fa-link"></i> Enlace Completo
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control" id="resetLink" 
                                       value="{{ $reset_link }}" readonly>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="copyToClipboard()">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                Este enlace expirará en 1 hora. Puedes copiarlo y pegarlo en tu navegador.
                            </small>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Modo desarrollo:</strong> En producción, este enlace se enviaría automáticamente al email <strong>{{ $email }}</strong>.
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('password.forgot') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Generar Otro Enlace
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary float-end">
                            <i class="fas fa-sign-in-alt"></i> Volver al Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const copyText = document.getElementById("resetLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(copyText.value)
        .then(() => {
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        })
        .catch(err => {
            alert('Error al copiar: ' + err);
        });
}
</script>
@endsection