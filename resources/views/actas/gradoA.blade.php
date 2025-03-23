<!DOCTYPE html>
<html>
<head>
    <title>ANEXO II - Informe de Avaliación Individualizado</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .header { margin-bottom: 30px; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ANEXO II</h1>
        <h2>(Real Decreto 659/2023)</h2>
        <h3>INFORME DE AVALIACIÓN INDIVIDUALIZADO</h3>
    </div>

    <!-- Datos del alumno -->
    <div>
        <p><strong>ALUMNO/A (DNI/NIE/PASAPORTE, NOME E APELIDOS):</strong> {{ $alumnoCurso->dni }} - {{ $alumnoCurso->nombre }} {{ $alumnoCurso->apellidos }}</p>
        <p><strong>CERTIFICADO PROFESIONAL:</strong> {{ $curso->nombre }}</p>
    </div>

    <!-- Datos de la acción formativa -->
    <div>
        <p><strong>DATAS DE INICIO:</strong> {{ $cursoAcademico->inicio }}</p>
        <p><strong>DENOMINACIÓN:</strong> {{ $curso->nombre }}</p>
        <p><strong>CÓDIGO NIVEL:</strong> {{ $curso->codigo }}</p>
        <p><strong>No ACCIÓN FORMATIVA:</strong> {{ $unidad->codigo }}</p>
    </div>

    <!-- Datos del centro de formación -->
    <div>
        <p><strong>ENTIDADE OU CENTRO DE FORMACIÓN:</strong></p>
        <p><strong>No CENSO:</strong> {{ $centroFormacion->numero_censo }}</p>
        <p><strong>DENOMINACIÓN:</strong> {{ $centroFormacion->ident }}</p> <!-- Nombre del centro -->
        <p><strong>ENDEREZO:</strong> {{ $centroFormacion->direccion }}</p>
        <p><strong>C. POSTAL:</strong> {{ $centroFormacion->codigo_postal }}</p>
        <p><strong>LOCALIDADE:</strong> {{ $centroFormacion->localidad }}</p>
        <p><strong>PROVINCIA:</strong> {{ $centroFormacion->provincia }}</p>
    </div>

    <!-- Módulos profesionales -->
    <h3>MÓDULOS PROFESIONAIS</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Denominación</th>
                <th>Horas</th>
                <th>Validación Cualitativa do Alumno/a</th>
                <th>Horas de Asistencia</th>
            </tr>
        </thead>
        <tbody>
    
                <tr>
                    <td>{{ $unidad->codigo }}</td>
                    <td>{{ $unidad->nombre }}</td>
                    <td>{{ $unidad->horas }}</td>
                    <td>{{ $notas[$unidad->id] ?? 'N/A' }}</td>
                    <td>{{ $alumnoCurso->horas_asistencia ?? 'N/A' }}</td> <!-- Horas de asistencia desde alumnos_curso -->
                </tr>

        </tbody>
    </table>

    <!-- Notas al pie -->
    <div class="footer">
        <p>Páxina 1 de 2</p>
        <p>1 Replíquese por cantos profesores/formadores, expertos/as estean presentes nesta acción formativa.</p>
        <p>2 Replíquese no caso de ter realizado a formación en máis dunha empresa ou organismo equiparado.</p>
    </div>
</body>
</html>