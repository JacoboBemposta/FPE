<!DOCTYPE html>
<html>
<body>
    <p><strong>Asunto:</strong> {{ $asunto ?? 'No especificado' }}</p>
    <p><strong>De:</strong> {{ $remitente_nombre ?? 'No especificado' }} ({{ $remitente_email ?? 'No especificado' }})</p>
    
    @if(isset($contexto))
    <p><strong>Contexto:</strong> {{ $contexto }}</p>
    @endif
    
    <div style="margin: 20px 0; padding: 15px; background: #f5f5f5;">
        {!! nl2br(e($mensaje ?? 'Sin mensaje')) !!}
    </div>
    
    <p><small>Enviado el: {{ $fecha ?? now()->format('d/m/Y H:i') }}</small></p>
    
    <!-- Depuración (solo en desarrollo) -->
    @if(app()->environment('local'))
    <hr>
    <p><small>DEBUG:</small></p>
    <p><small>Asunto: {{ $asunto ?? 'NULL' }}</small></p>
    <p><small>Remitente nombre: {{ $remitente_nombre ?? 'NULL' }}</small></p>
    <p><small>Remitente email: {{ $remitente_email ?? 'NULL' }}</small></p>
    <p><small>Contexto: {{ $contexto ?? 'NULL' }}</small></p>
    <p><small>Fecha: {{ $fecha ?? 'NULL' }}</small></p>
    @endif
</body>
</html>