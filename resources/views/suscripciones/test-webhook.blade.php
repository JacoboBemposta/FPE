{{-- 
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Prueba de Webhook de Stripe</h1>
    
    <div class="card mt-4">
        <div class="card-body">
            <h5>URL del Webhook:</h5>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="webhookUrl" 
                       value="{{ url('/stripe/webhook') }}" readonly>
                <button class="btn btn-outline-secondary" onclick="copyToClipboard()">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            
            <h5 class="mt-4">Probar Webhook Manualmente:</h5>
            <form id="testWebhookForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Evento a simular:</label>
                    <select class="form-select" name="event_type">
                        <option value="customer.subscription.created">Suscripción Creada</option>
                        <option value="invoice.payment_succeeded">Pago Exitoso</option>
                        <option value="invoice.payment_failed">Pago Fallido</option>
                        <option value="customer.subscription.deleted">Suscripción Cancelada</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Probar Webhook</button>
            </form>
            
            <div id="webhookResult" class="mt-3" style="display: none;">
                <pre class="bg-light p-3 rounded"></pre>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('webhookUrl');
    input.select();
    document.execCommand('copy');
    alert('URL copiada al portapapeles');
}

document.getElementById('testWebhookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/test-webhook-event', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('webhookResult');
        const pre = resultDiv.querySelector('pre');
        pre.textContent = JSON.stringify(data, null, 2);
        resultDiv.style.display = 'block';
    });
});
</script>
@endsection --}}