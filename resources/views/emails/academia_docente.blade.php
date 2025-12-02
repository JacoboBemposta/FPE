
{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $asunto ?? 'Mensaje' }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #fff; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $asunto ?? 'Mensaje de la Plataforma' }}</h2>
        </div>
        
        <div class="content">
            {!! nl2br(e($mensaje ?? '')) !!}
            
            <hr>
            
            <p><strong>Enviado por:</strong> {{ $remitente_nombre ?? 'Plataforma' }}</p>
            @if(isset($contexto))
                <p><small>Contexto: {{ $contexto }}</small></p>
            @endif
        </div>
        
        <div class="footer">
            <p>Este mensaje fue enviado a través de la plataforma de formación.</p>
            <p>© {{ date('Y') }} Plataforma de Formación. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html> --}}