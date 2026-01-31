<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h1>✅ Laravel está FUNCIONANDO</h1>
            </div>
            <div class="card-body">
                <p class="fs-4">¡Enhorabuena! Laravel se está ejecutando correctamente.</p>
                <hr>
                <h3>Información del sistema:</h3>
                <ul>
                    <li>Fecha/hora servidor: <?php echo date('Y-m-d H:i:s'); ?></li>
                    <li>PHP Version: <?php echo phpversion(); ?></li>
                    <li>Laravel Version: <?php echo app()->version(); ?></li>
                    <li>App Name: <?php echo config('app.name'); ?></li>
                    <li>App URL: <?php echo config('app.url'); ?></li>
                    <li>App Environment: <?php echo config('app.env'); ?></li>
                </ul>
                <hr>
                <a href="/" class="btn btn-primary">Ir a la página principal</a>
            </div>
        </div>
    </div>
</body>
</html>
