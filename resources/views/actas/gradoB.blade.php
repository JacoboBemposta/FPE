<!DOCTYPE html>
<html>
<head>
    <title>ACTA DE EVALUACIÓN FINAL GRADO B</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2, h3  { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { border: 1px solid #000; padding: 8px;text-align: center; font-size: 75%}
        td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 60%}
        .header { margin-bottom: 30px; }
        .footer { margin-top: 30px; font-size: 12px; }
        .fecha-derecha { text-align: right; }
        .accion-izquierda { text-align: left; }
        .info-linea { display: flex; justify-content: space-between; }
        .page-break {
            page-break-after: always; /* Fuerza nueva página después del anverso */
        }
        .info-linea {
            white-space: nowrap;
            font-size: 75%
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>ACTA DE EVALUACIÓN FINAL GRADO B</h1>
        <!-- Fecha de la acción formativa justificada a la derecha -->
        @php
            use Carbon\Carbon;

            Carbon::setLocale('es');

            $fecha = \Carbon\Carbon::parse($fechaInicial);
        @endphp
        <p class="fecha-derecha"><strong>Fecha de la acción formativa:</strong> {{ $fecha->day }} de {{ $fecha->translatedFormat('F') }} de {{ $fecha->year }}</p>

        <!-- Acción formativa y centro de formación justificados a la izquierda -->
        <div class="accion-izquierda">
            <p><strong>ACCIÓN FORMATIVA:</strong>  {{ $modulo->nombre }}</p>
            <p><strong>CENTRO DE FORMACIÓN: </strong> {{ auth()->user()->ident }}</p>
        </div>

        <div class="info-linea d-flex flex-wrap gap-2">
            <strong>Dirección:</strong> {{ auth()->user()->direccion }},
            <strong>Localidad:</strong> {{ auth()->user()->localidad }},
            <strong>Código Postal:</strong> {{ auth()->user()->codigo_postal }},
            <strong>Provincia:</strong> {{ auth()->user()->provincia }}
        </div>
    </div>

    <!-- Tabla de alumnos y calificaciones -->
    <table>
        <thead>
            <th colspan="3" >Relación alfabética del alumnado</th>
            <th colspan="{{ $unidades->count() }}" > {{ $modulo->codigo }}</th>
            <th rowspan="2" style="width: 5px">CALIFICACIÓN (Superado/No superado)</th>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 70%">Nº de orden</td>
                <td style="font-size: 70%">DNI/NIE/PASAPORTE</td>
                <td style="font-size: 70%">APELLIDOS Y NOMBRE</td>
                @foreach ($unidades as $unidad)
                    <td style="font-size: 70%">{{ $unidad->codigo }}</td>
                @endforeach
            </tr>
            @foreach ($alumnos as $alumno)
                <tr>
                    <!-- Nº de orden basado en la posición del alumno -->
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $alumno->dni }}</td>
                    <td>{{ $alumno->apellidos }} {{ $alumno->nombre }}</td>
                    @foreach ($unidades as $unidad)
                        <td>{{ $calificaciones[$alumno->id][$unidad->id] }}</td>
                    @endforeach
                    <!-- Cálculo de Superado/No superado -->
                    <td>
                        @php
                            $total = 0;
                            $allPassed = true;
                            foreach ($unidades as $unidad) {
                                $nota = (float)$calificaciones[$alumno->id][$unidad->id];
                                $total += $nota;
                                if ($nota < 5.0) {
                                    $allPassed = false;
                                }
                            }
                            $average = $total / count($unidades);
                            echo ($average >= 5.0 && $allPassed) ? 'Superado' : 'No superado';
                        @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Notas al pie -->
    <div class="footer">

        @php

            $fecha = \Carbon\Carbon::parse($examenFinal);
        @endphp
    
        <p>Evaluación final: {{ $fecha->day }} de {{ $fecha->translatedFormat('F') }} de {{ $fecha->year }}</p>
    


        <p>
            Evaluación final de {{ count($alumnos) }} alumnos y alumnas finalizando en 
            {{ \Carbon\Carbon::parse($fechaFinal)->translatedFormat('d \d\e F \d\e Y') }}
        </p>
    </div>
</body>
</html>