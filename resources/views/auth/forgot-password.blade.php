@extends('layouts.app3')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recuperar Contraseña</div>

                <div class="card-body">
                    {{-- Mostrar enlace generado --}}
                    @if (isset($show_link) && $show_link)
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> ¡Enlace generado!</h5>
                            <p>Para: <strong>{{ $email }}</strong></p>
                            
                            <div class="mt-3">
                                <a href="{{ $reset_link }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-key"></i> Restablecer Contraseña
                                </a>
                                
                                <div class="mt-3">
                                    <label class="form-label">O copia este enlace:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" 
                                               value="{{ $reset_link }}" readonly id="linkInput">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="copyLink()">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('password.forgot') }}" class="btn btn-secondary">
                                Generar otro enlace
                            </a>
                        </div>
                    @else
                        {{-- Formulario normal --}}
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Generar Enlace de Recuperación
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-link">
                                    Volver al Login
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    const input = document.getElementById('linkInput');
    input.select();
    input.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(input.value)
        .then(() => {
            alert('Enlace copiado al portapapeles!');
        });
}
</script>
@endsection