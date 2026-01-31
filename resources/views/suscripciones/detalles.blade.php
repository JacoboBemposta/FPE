@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Detalles de Suscripción
                    </h4>
                    @if($tiene_suscripcion)
                        <span class="badge bg-success fs-6">ACTIVA</span>
                    @endif
                </div>
                <div class="card-body">
                    
                    @if($tiene_suscripcion && $suscripcion)
                        <!-- Estado actual -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">
                                            <i class="fas fa-calendar-check me-2"></i>Periodo Actual
                                        </h6>
                                        <p class="mb-1">
                                            <strong>Inicio:</strong> 
                                            {{ $suscripcion->fecha_inicio->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Fin:</strong> 
                                            {{ $suscripcion->fecha_fin->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Días restantes:</strong> 
                                            <span class="badge bg-info">
                                                {{ now()->diffInDays($suscripcion->fecha_fin) }} días
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-money-bill-wave me-2"></i>Información del Plan
                                        </h6>
                                        <p class="mb-1"><strong>Plan:</strong> {{ $suscripcion->plan }}</p>
                                        <p class="mb-1"><strong>Precio:</strong> {{ number_format($suscripcion->precio, 2) }}€/mes</p>
                                        <p class="mb-1"><strong>Periodo:</strong> {{ $suscripcion->intervalo }}</p>
                                        <p class="mb-0"><strong>Estado:</strong> {{ ucfirst($suscripcion->estado) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Historial de pagos -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-history me-2"></i>Historial de Pagos
                            </h5>
                            @if($pagos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Periodo</th>
                                                <th>Monto</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pagos as $pago)
                                            <tr>
                                                <td>{{ $pago->fecha_pago->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    {{ $pago->fecha_inicio_periodo->format('d/m/Y') }} - 
                                                    {{ $pago->fecha_fin_periodo->format('d/m/Y') }}
                                                </td>
                                                <td>{{ number_format($pago->monto_total, 2) }}€</td>
                                                <td>
                                                    <span class="badge bg-success">COMPLETADO</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay registros de pagos aún.
                                </div>
                            @endif
                        </div>
                        
                        <!-- Acciones -->
                        <div class="border-top pt-3">
                            <button id="btnCancelarSuscripcion" class="btn btn-outline-danger">
                                <i class="fas fa-ban me-2"></i>Cancelar Suscripción
                            </button>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                La cancelación será efectiva al final del periodo actual.
                            </small>
                        </div>
                        
                    @else
                        <!-- Sin suscripción activa -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-crown fa-4x text-muted mb-3"></i>
                                <h4>No tienes una suscripción activa</h4>
                                <p class="text-muted">
                                    Suscríbete para acceder a todas las funciones premium
                                </p>
                            </div>
                            <a href="{{ route('suscripciones.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>
                                Suscribirse Ahora
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($tiene_suscripcion)
<script>
document.getElementById('btnCancelarSuscripcion').addEventListener('click', function() {
    if (!confirm('¿Estás seguro de que quieres cancelar tu suscripción?\n\nPodrás seguir usando las funciones premium hasta el final del periodo actual.')) {
        return;
    }
    
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cancelando...';
    btn.disabled = true;
    
    fetch('{{ route("suscripcion.cancelar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Error al cancelar');
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
@endif
@endpush