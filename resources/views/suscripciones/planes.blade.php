@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-crown me-2"></i>Planes de Suscripción
                    </h4>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Para poder contactar 
                        @if($tipo_usuario == 'docente')
                            <strong>academias</strong>,
                        @elseif($tipo_usuario == 'academia')
                            <strong>docentes</strong>,
                        @endif
                        necesitas activar una suscripción.
                    </div>

                    <div class="row justify-content-center">
                        @if($tipo_usuario == 'profesor')
                            <!-- Plan para Docente - 5€ -->
                            <div class="col-md-8">
                                <div class="card border-primary shadow">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h4 class="mb-0">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>
                                            Plan Docente
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <h1 class="display-4 text-primary">5€</h1>
                                            <p class="text-muted">por mes</p>
                                        </div>
                                        
                                        <h5 class="text-center mb-3">¿Qué incluye?</h5>
                                        <ul class="list-group list-group-flush mb-4">
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-check text-success me-3"></i>
                                                <span>Contactar con academias sin límite</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-check text-success me-3"></i>
                                                <span>Perfil visible para academias</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-check text-success me-3"></i>
                                                <span>Soporte por email</span>
                                            </li>
                                        </ul>
                                        
                                        <div class="text-center">
                                            <button class="btn btn-primary btn-lg btn-suscribir" 
                                                    data-tipo="docente" 
                                                    data-precio="5">
                                                <i class="fas fa-credit-card me-2"></i>
                                                Suscribirse por 5€/mes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @elseif($tipo_usuario == 'academia')
                            <!-- Plan para Academia - 10€ -->
                            <div class="col-md-8">
                                <div class="card border-warning shadow">
                                    <div class="card-header bg-warning text-white text-center">
                                        <h4 class="mb-0">
                                            <i class="fas fa-university me-2"></i>
                                            Plan Academia
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <h1 class="display-4 text-warning">10€</h1>
                                            <p class="text-muted">por mes</p>
                                        </div>
                                        
                                        <h5 class="text-center mb-3">¿Qué incluye?</h5>
                                        <ul class="list-group list-group-flush mb-4">
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-check text-success me-3"></i>
                                                <span>Contactar docentes sin límites</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-check text-success me-3"></i>
                                                <span>Búsqueda avanzada de docentes</span>
                                            </li>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-check text-success me-3"></i>
                                                <span>Soporte prioritario</span>
                                            </li>
                                        </ul>
                                        
                                        <div class="text-center">
                                            <button class="btn btn-warning btn-lg btn-suscribir" 
                                                    data-tipo="academia" 
                                                    data-precio="10">
                                                <i class="fas fa-credit-card me-2"></i>
                                                Suscribirse por 10€/mes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @else
                            <!-- Si no se reconoce el tipo de usuario -->
                            <div class="col-md-8">
                                <div class="alert alert-danger">
                                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Tipo de usuario no reconocido</h5>
                                    <p class="mb-0">Por favor, contacta con soporte para seleccionar el plan adecuado.</p>
                                </div>
                            </div>
                        @endif
                    </div>

<div class="text-center mt-4">
    @if($tipo_usuario == 'profesor')
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver atrás
        </a>
    @elseif($tipo_usuario == 'academia')
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver atrás
        </a>
    @else
        <a href="/" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al inicio
        </a>
    @endif
</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el clic en el botón de suscripción
    document.querySelectorAll('.btn-suscribir').forEach(button => {
        button.addEventListener('click', function() {
            const tipo = this.getAttribute('data-tipo');
            const precio = this.getAttribute('data-precio');
            
            // Mostrar confirmación
            Swal.fire({
                title: `¿Suscribirse al plan ${tipo}?`,
                html: `Estás a punto de suscribirte al plan <strong>${tipo}</strong> por <strong>${precio}€/mes</strong>.<br><br>
                      Esta suscripción te permitirá contactar con ${tipo === 'docente' ? 'academias' : 'docentes'}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar suscripción',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: tipo === 'docente' ? '#0d6efd' : '#ffc107'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí implementarás la lógica de pago
                    iniciarProcesoSuscripcion(tipo, precio);
                }
            });
        });
    });
    
    function iniciarProcesoSuscripcion(tipo, precio) {
        // Mostrar cargando
        Swal.fire({
            title: 'Procesando...',
            text: 'Redirigiendo al proceso de pago',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false
        });
        
        // Enviar petición al servidor para crear la suscripción
        fetch('/suscripciones/procesar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                tipo: tipo,
                precio: precio
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirigir al proceso de pago (Stripe, PayPal, etc.)
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    // Si no hay URL de redirección, mostrar éxito
                    Swal.fire({
                        title: '¡Suscripción activada!',
                        text: 'Tu suscripción ha sido activada correctamente.',
                        icon: 'success'
                    }).then(() => {
                        // Redirigir al inicio
                        window.location.href = '/';
                    });
                }
            } else {
                throw new Error(data.message || 'Error al procesar la suscripción');
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error'
            });
        });
    }
});
</script>
@endpush
@endsection