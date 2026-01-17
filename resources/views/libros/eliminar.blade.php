@extends('layouts.app')
@section('content')

<div class="coffee-delete-container">
    <!-- Elementos decorativos -->
    <div class="coffee-elements">
        <div class="coffee-bean bean-1"></div>
        <div class="coffee-bean bean-2"></div>
        <div class="coffee-bean bean-3"></div>
        <div class="warning-symbol">
            <div class="exclamation-mark">!</div>
        </div>
    </div>

    <div class="delete-wrapper">
        <!-- Tarjeta principal -->
        <div class="coffee-delete-card">
            <!-- Header con icono de advertencia -->
            <div class="delete-header">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1>Eliminar Producto</h1>
                <p>Acción irreversible - Por favor verifica cuidadosamente</p>
            </div>

            <!-- Información de advertencia -->
            <div class="warning-message">
                <div class="warning-content">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h4>¡Atención!</h4>
                        <p>Esta acción eliminará permanentemente el producto del sistema. Esta operación no se puede deshacer.</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de eliminación -->
            <form method="POST" action="{{ route('libros.destroy') }}" class="delete-form" id="deleteForm">
                @csrf
                @method('DELETE')
                
                <div class="form-group">
                    <div class="input-container">
                        <i class="fas fa-trash-alt input-icon"></i>
                        <input type="text" id="IdLibro" name="IdLibro" class="form-control" required placeholder=" " 
                               pattern="[0-9]+" title="Por favor ingresa solo números">
                        <label for="IdLibro" class="input-label">ID del Producto a Eliminar</label>
                    </div>
                    <div class="input-helper">
                        <i class="fas fa-key"></i>
                        Ingresa el ID numérico del producto que deseas eliminar
                    </div>
                </div>

                <!-- Confirmación adicional -->
                <div class="confirmation-section">
                    <label class="confirmation-checkbox">
                        <input type="checkbox" id="confirmDelete" required>
                        <span class="checkmark"></span>
                        <span class="confirmation-text">
                            Confirmo que deseo eliminar este producto permanentemente
                        </span>
                    </label>
                </div>

                <button type="submit" class="delete-btn" id="deleteBtn" disabled>
                    <i class="fas fa-trash"></i>
                    <span class="btn-text">Eliminar Producto</span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                        Eliminando...
                    </span>
                </button>
            </form>

            <!-- Información de seguridad -->
            <div class="safety-info">
                <div class="safety-item">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h4>Medida de Seguridad</h4>
                        <p>Requerimos confirmación explícita para prevenir eliminaciones accidentales</p>
                    </div>
                </div>
                <div class="safety-item">
                    <i class="fas fa-history"></i>
                    <div>
                        <h4>Sin Posibilidad de Recuperación</h4>
                        <p>Los productos eliminados no pueden ser restaurados del sistema</p>
                    </div>
                </div>
            </div>

            <!-- Enlaces alternativos -->
            <div class="delete-footer">
                <a href="{{ url()->previous() }}" class="cancel-link">
                    <i class="fas fa-arrow-left"></i>
                    Cancelar y Volver
                </a>
                <a href="#" class="view-link">
                    <i class="fas fa-list"></i>
                    Ver Todos los Productos
                </a>
            </div>
        </div>

        <!-- Panel de información -->
        <div class="info-panel">
            <div class="info-content">
                <div class="info-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h3>Gestión de Productos</h3>
                <p>Antes de eliminar, considera estas alternativas:</p>
                
                <div class="alternatives-list">
                    <div class="alternative">
                        <i class="fas fa-edit"></i>
                        <div>
                            <h4>Editar Producto</h4>
                            <p>Modifica la información del producto en lugar de eliminarlo</p>
                        </div>
                    </div>
                    <div class="alternative">
                        <i class="fas fa-eye-slash"></i>
                        <div>
                            <h4>Ocultar Temporalmente</h4>
                            <p>Desactiva la visibilidad sin perder la información</p>
                        </div>
                    </div>
                    <div class="alternative">
                        <i class="fas fa-archive"></i>
                        <div>
                            <h4>Archivar</h4>
                            <p>Guarda el producto en archivos en lugar de eliminarlo</p>
                        </div>
                    </div>
                </div>

                <div class="backup-notice">
                    <i class="fas fa-save"></i>
                    <p>Recuerda que siempre es recomendable tener un backup de tu información importante</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
    <div class="coffee-alert success">
        <div class="alert-content">
            <i class="fas fa-check-circle"></i>
            <div>
                <h4>¡Producto Eliminado!</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        <button class="alert-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="coffee-alert error">
        <div class="alert-content">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <h4>Error al Eliminar</h4>
                <p>{{ session('error') }}</p>
            </div>
        </div>
        <button class="alert-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Modal de confirmación final -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <h3>Confirmación Final</h3>
            </div>
            <div class="modal-body">
                <p>¿Estás absolutamente seguro de que deseas eliminar el producto con ID: <strong id="confirmId"></strong>?</p>
                <p class="warning-text">Esta acción no se puede deshacer y el producto será eliminado permanentemente.</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn cancel" id="modalCancel">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>
                <button class="modal-btn confirm" id="modalConfirm">
                    <i class="fas fa-check"></i>
                    Sí, Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .coffee-delete-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #fff8f8 0%, #ffebee 50%, #fce4ec 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    /* Elementos decorativos */
    .coffee-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .coffee-bean {
        position: absolute;
        width: 20px;
        height: 10px;
        background: #d32f2f;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 15s infinite linear;
    }

    .bean-1 {
        top: 15%;
        left: 8%;
        animation-delay: 0s;
    }

    .bean-2 {
        top: 65%;
        right: 12%;
        animation-delay: 3s;
    }

    .bean-3 {
        bottom: 25%;
        left: 12%;
        animation-delay: 6s;
    }

    .warning-symbol {
        position: absolute;
        top: 20%;
        right: 15%;
        opacity: 0.05;
        transform: rotate(15deg);
    }

    .exclamation-mark {
        width: 60px;
        height: 60px;
        background: #d32f2f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
    }

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(-100vh) rotate(360deg);
        }
    }

    /* Contenedor principal */
    .delete-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 1200px;
        width: 100%;
        position: relative;
        z-index: 2;
    }

    /* Tarjeta de eliminación */
    .coffee-delete-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(211, 47, 47, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-left: 4px solid #d32f2f;
    }

    /* Header */
    .delete-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .warning-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 25px rgba(211, 47, 47, 0.3);
    }

    .warning-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .delete-header h1 {
        color: #d32f2f;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .delete-header p {
        color: #e57373;
        font-size: 1.1rem;
        margin: 0;
        font-weight: 500;
    }

    /* Mensaje de advertencia */
    .warning-message {
        background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #d32f2f;
    }

    .warning-content {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .warning-content i {
        color: #d32f2f;
        font-size: 1.5rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .warning-content h4 {
        color: #c62828;
        margin: 0 0 8px 0;
        font-size: 1.1rem;
    }

    .warning-content p {
        color: #d32f2f;
        margin: 0;
        line-height: 1.5;
    }

    /* Formulario */
    .delete-form {
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .input-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #d32f2f;
        font-size: 1.1rem;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .form-control {
        width: 100%;
        padding: 15px 15px 15px 50px;
        border: 2px solid #ffcdd2;
        border-radius: 12px;
        font-size: 1.1rem;
        background: white;
        transition: all 0.3s ease;
        color: #d32f2f;
        font-weight: 600;
    }

    .form-control:focus {
        outline: none;
        border-color: #d32f2f;
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        transform: translateY(-2px);
    }

    .form-control:focus + .input-label,
    .form-control:not(:placeholder-shown) + .input-label {
        top: -8px;
        left: 45px;
        font-size: 0.8rem;
        background: white;
        padding: 0 8px;
        color: #d32f2f;
    }

    .input-label {
        position: absolute;
        left: 50px;
        top: 50%;
        transform: translateY(-50%);
        color: #e57373;
        font-size: 1rem;
        transition: all 0.3s ease;
        pointer-events: none;
        background: transparent;
    }

    .input-helper {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #e57373;
        font-size: 0.85rem;
        margin-top: 8px;
        padding: 8px 12px;
        background: #ffebee;
        border-radius: 8px;
        border-left: 3px solid #d32f2f;
    }

    .input-helper i {
        color: #d32f2f;
    }

    /* Confirmación con checkbox */
    .confirmation-section {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed #e57373;
    }

    .confirmation-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
        color: #d32f2f;
        font-weight: 500;
    }

    .confirmation-checkbox input {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid #e57373;
        border-radius: 4px;
        position: relative;
        flex-shrink: 0;
        margin-top: 2px;
        transition: all 0.3s ease;
    }

    .confirmation-checkbox input:checked + .checkmark {
        background: #d32f2f;
        border-color: #d32f2f;
    }

    .confirmation-checkbox input:checked + .checkmark::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 14px;
        font-weight: bold;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .confirmation-text {
        line-height: 1.4;
    }

    /* Botón de eliminación */
    .delete-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 5px 20px rgba(211, 47, 47, 0.3);
    }

    .delete-btn:enabled:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 47, 47, 0.4);
        background: linear-gradient(135deg, #c62828 0%, #d32f2f 100%);
    }

    .delete-btn:disabled {
        background: #bdbdbd;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    /* Información de seguridad */
    .safety-info {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .safety-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .safety-item:last-child {
        margin-bottom: 0;
    }

    .safety-item i {
        color: #d32f2f;
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .safety-item h4 {
        color: #d32f2f;
        margin: 0 0 4px 0;
        font-size: 0.95rem;
    }

    .safety-item p {
        color: #e57373;
        margin: 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    /* Footer */
    .delete-footer {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .cancel-link {
        color: #d32f2f;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .view-link {
        color: #5D4037;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .cancel-link:hover,
    .view-link:hover {
        background: #f5f5f5;
        transform: translateY(-2px);
    }

    /* Panel de información */
    .info-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .info-content {
        text-align: center;
    }

    .info-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .info-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .info-content h3 {
        color: #5D4037;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .info-content > p {
        color: #8D6E63;
        margin-bottom: 2rem;
    }

    .alternatives-list {
        text-align: left;
        margin-bottom: 2rem;
    }

    .alternative {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .alternative:hover {
        background: #e8f5e8;
        transform: translateX(5px);
    }

    .alternative i {
        color: #8B4513;
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .alternative h4 {
        color: #5D4037;
        margin: 0 0 4px 0;
        font-size: 0.95rem;
    }

    .alternative p {
        color: #8D6E63;
        margin: 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .backup-notice {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1rem;
        background: #e3f2fd;
        border-radius: 8px;
        border-left: 4px solid #2196F3;
    }

    .backup-notice i {
        color: #2196F3;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .backup-notice p {
        color: #1976D2;
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    /* Modal de confirmación */
    .confirmation-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .confirmation-modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border-top: 4px solid #d32f2f;
    }

    .modal-header {
        margin-bottom: 1.5rem;
    }

    .modal-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .modal-icon i {
        font-size: 2rem;
        color: white;
    }

    .modal-header h3 {
        color: #d32f2f;
        margin: 0;
        font-size: 1.5rem;
    }

    .modal-body {
        margin-bottom: 2rem;
    }

    .modal-body p {
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .warning-text {
        color: #d32f2f !important;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .modal-footer {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .modal-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-btn.cancel {
        background: #f5f5f5;
        color: #666;
    }

    .modal-btn.confirm {
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        color: white;
    }

    .modal-btn:hover {
        transform: translateY(-2px);
    }

    /* Alertas (mantener el mismo estilo de anteriores) */
    .coffee-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        max-width: 400px;
        z-index: 1000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid;
    }

    .coffee-alert.success {
        border-left-color: #4CAF50;
    }

    .coffee-alert.error {
        border-left-color: #F44336;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .delete-wrapper {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    @media (max-width: 768px) {
        .coffee-delete-container {
            padding: 1rem;
        }

        .coffee-delete-card,
        .info-panel {
            padding: 2rem 1.5rem;
        }

        .delete-header h1 {
            font-size: 1.7rem;
        }

        .warning-icon {
            width: 60px;
            height: 60px;
        }

        .warning-icon i {
            font-size: 2rem;
        }

        .delete-footer {
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-icon {
            width: 60px;
            height: 60px;
        }

        .info-icon i {
            font-size: 2rem;
        }

        .modal-footer {
            flex-direction: column;
        }
    }
</style>

<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteForm');
        const deleteBtn = document.getElementById('deleteBtn');
        const confirmCheckbox = document.getElementById('confirmDelete');
        const confirmationModal = document.getElementById('confirmationModal');
        const modalConfirm = document.getElementById('modalConfirm');
        const modalCancel = document.getElementById('modalCancel');
        const confirmId = document.getElementById('confirmId');

        // Habilitar/deshabilitar botón basado en checkbox
        confirmCheckbox.addEventListener('change', function() {
            deleteBtn.disabled = !this.checked;
        });

        // Validación del formulario
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = document.getElementById('IdLibro').value;
            if (!productId || !confirmCheckbox.checked) {
                return;
            }

            // Mostrar modal de confirmación final
            confirmId.textContent = productId;
            confirmationModal.classList.add('active');
        });

        // Confirmar eliminación
        modalConfirm.addEventListener('click', function() {
            // Mostrar estado de carga
            const btnText = deleteBtn.querySelector('.btn-text');
            const btnLoading = deleteBtn.querySelector('.btn-loading');
            
            btnText.style.display = 'none';
            btnLoading.style.display = 'flex';
            deleteBtn.disabled = true;

            // Cerrar modal
            confirmationModal.classList.remove('active');

            // Enviar formulario después de breve delay
            setTimeout(() => {
                deleteForm.submit();
            }, 1000);
        });

        // Cancelar eliminación
        modalCancel.addEventListener('click', function() {
            confirmationModal.classList.remove('active');
        });

        // Cerrar modal al hacer clic fuera
        confirmationModal.addEventListener('click', function(e) {
            if (e.target === confirmationModal) {
                confirmationModal.classList.remove('active');
            }
        });

        // Cerrar alertas automáticamente
        const alerts = document.querySelectorAll('.coffee-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Cerrar alerta manualmente
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.coffee-alert');
                alert.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });
        });

        // Efecto de focus en el input
        const idInput = document.getElementById('IdLibro');
        if (idInput) {
            idInput.setAttribute('placeholder', ' ');
            
            idInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            idInput.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        }
    });
</script>

@endsection