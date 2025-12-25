@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-crown me-2"></i>Detalles de tu Suscripción
                    </h4>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>¡Suscripción Activa!</strong> Tu suscripción está vigente.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Información de la Suscripción</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Fecha de inicio:</strong> {{ $inicio_suscripcion->format('d/m/Y') }}</p>
                                    <p><strong>Fecha de fin:</strong> {{ $fin_suscripcion->format('d/m/Y') }}</p>
                                    <p><strong>Días restantes:</strong> {{ now()->diffInDays($fin_suscripcion) }} días</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Acciones</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('suscripcion.planes') }}" class="btn btn-primary">
                                            <i class="fas fa-sync-alt me-2"></i>Renovar Suscripción
                                        </a>
                                        <button class="btn btn-outline-danger" onclick="cancelarSuscripcion()">
                                            <i class="fas fa-times me-2"></i>Cancelar Suscripción
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cancelarSuscripcion() {
    if (confirm('¿Estás seguro de que deseas cancelar tu suscripción?')) {
        // Aquí puedes implementar la lógica para cancelar la suscripción
        alert('Función de cancelación en desarrollo. Contacta con soporte.');
    }
}
</script>
@endsection