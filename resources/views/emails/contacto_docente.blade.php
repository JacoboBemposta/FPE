{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $academia_nombre }} - {{ $data['subject'] ?? 'Contacto' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .message {
            white-space: pre-line;
            margin: 20px 0;
            padding: 15px;
            background-color: white;
            border-left: 4px solid #007bff;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $academia_nombre }}</h2>
        <p>Mensaje enviado a través de la plataforma</p>
    </div>
    
    <div class="content">
        <p><strong>De:</strong> {{ $academia_nombre }} ({{ $academia_email }})</p>
        
        <div class="message">
            {{ $mensaje }}
        </div>
        
        <p><strong>Respuesta:</strong> Puede responder directamente a este email.</p>
    </div>
    
    <div class="footer">
        <p>Este mensaje fue enviado automáticamente desde la plataforma de gestión educativa.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
    </div>
</body>
</html> --}}