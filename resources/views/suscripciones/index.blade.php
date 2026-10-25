@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-crown me-2"></i>Suscripción Premium
                    </h4>
                </div>
                <div class="card-body">
                    
                    @if($tiene_suscripcion)
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>¡Ya eres Premium!</h5>
                            <p class="mb-1">Tu suscripción está activa hasta el 
                                <strong>{{ $fin_suscripcion->format('d/m/Y') }}</strong>
                            </p>
                            <a href="{{ route('suscripcion.detalles') }}" class="btn btn-sm btn-outline-success mt-2">
                                <i class="fas fa-eye me-1"></i>Ver detalles
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Suscripción Mensual</h5>
                            <p>Accede a todas las funciones premium por solo:</p>
                            <div class="text-center my-4">
                                <div class="display-4 text-primary">
                                    @if(Auth::user()->rol === 'profesor')
                                        5€
                                        <small class="fs-6 text-muted">/mes</small>
                                    @else
                                        10€
                                        <small class="fs-6 text-muted">/mes</small>
                                    @endif
                                </div>
                                <p class="text-muted">Facturación mensual recurrente</p>
                            </div>
                            
                            <div class="mt-4">
                                <h6><i class="fas fa-check text-success me-2"></i>Beneficios:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Acceso completo a todas las funciones</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Sin límites de uso</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Soporte prioritario</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Actualizaciones constantes</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button id="btnSuscribirse" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Suscribirse Ahora
                                </button>
                                <a href="{{ route('suscripcion.detalles') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-question-circle me-1"></i>Más información
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    
document.getElementById('btnSuscribirse').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
    btn.disabled = true;
    
    fetch('{{ route("suscripcion.checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.url) {
            window.location.href = data.url;
        } else {
            alert(data.message || 'Error al procesar la suscripción');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endsection