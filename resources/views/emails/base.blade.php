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
    

</body>
</html>