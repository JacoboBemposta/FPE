@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-sync-alt mr-2"></i>Sincronización con Stripe
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Estado de Sincronización
                    </h6>
                </div>
                <div class="card-body">
                    <div id="syncStatus">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <p class="mt-2">Verificando estado...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Acciones de Sincronización
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button id="btnSyncAll" class="btn btn-primary btn-lg">
                            <i class="fas fa-sync mr-2"></i>Sincronizar Todo
                        </button>
                        
                        <button id="btnCheckStatus" class="btn btn-info">
                            <i class="fas fa-redo mr-1"></i>Verificar Estado
                        </button>
                        
                        <a href="{{ route('admin.stats') }}" class="btn btn-secondary">
                            <i class="fas fa-chart-bar mr-1"></i>Ver Estadísticas
                        </a>
                    </div>
                    
                    <hr>
                    
                    <div class="mt-4">
                        <h6>Configuración Webhook</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   value="{{ route('stripe.webhook') }}" 
                                   id="webhookUrl" readonly>
                            <button class="btn btn-outline-secondary" onclick="copyWebhookUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">
                            URL para configurar en el dashboard de Stripe
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadSyncStatus() {
    fetch('{{ route("admin.stripe.sync-status") }}')
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Suscripciones locales
                        <span class="badge badge-primary badge-pill">${data.local_subscriptions}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Pagos locales
                        <span class="badge badge-primary badge-pill">${data.local_payments}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Suscripciones en Stripe
                        <span class="badge badge-success badge-pill">${data.stripe_subscriptions || 'N/A'}</span>
                    </div>
                </div>
            `;
            
            if (data.sync_needed) {
                html += `
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Hay suscripciones en Stripe que no están sincronizadas localmente.
                    </div>
                `;
            }
            
            if (data.error) {
                html = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        ${data.error}
                    </div>
                `;
            }
            
            document.getElementById('syncStatus').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('syncStatus').innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar el estado de sincronización
                </div>
            `;
        });
}

document.getElementById('btnSyncAll').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sincronizando...';
    btn.disabled = true;
    
    fetch('{{ route("admin.stripe.sync-all") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                loadSyncStatus();
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            showToast('error', 'Error en la sincronización');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
});

document.getElementById('btnCheckStatus').addEventListener('click', loadSyncStatus);

function copyWebhookUrl() {
    const input = document.getElementById('webhookUrl');
    input.select();
    document.execCommand('copy');
    showToast('success', 'URL copiada al portapapeles');
}

function showToast(type, message) {
    // Implementa tu función de toast o usa una librería
    alert(message); // Temporal
}

// Cargar estado al iniciar
document.addEventListener('DOMContentLoaded', loadSyncStatus);
</script>
@endpush