<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Calificaciones - Grado C</title>
    <style>
        @page {
            margin: 0.5cm;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0.5cm;
            color: #000;
            font-size: 12px;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .header h1 {
            font-size: 10px;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 9px;
            margin: 2px 0;
        }
        
        .header h3 {
            font-size: 8px;
            margin: 2px 0;
        }
        
        .info-section {
            margin-bottom: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 10px;
            margin-bottom: 8px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 2px;
        }
        
        .info-label {
            min-width: 80px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .info-value {
            flex: 1;
            font-size: 10px;
        }
        
        .table-container {
            margin-bottom: 10px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            table-layout: fixed;
        }
        
        .table th {
            background-color: #f2f2f2;
            border: 0.5px solid #000;
            padding: 3px 2px;
            text-align: center;
            font-weight: bold;
            height: 20px;
        }
        
        .table td {
            border: 0.5px solid #000;
            padding: 2px 3px;
            vertical-align: middle;
            height: 18px;
        }
        
        .col-codigo {
            width: 12%;
        }
        
        .col-modulo {
            width: 45%;
        }
        
        .col-horas {
            width: 10%;
            text-align: center;
        }
        
        .col-calificacion {
            width: 13%;
            text-align: center;
        }
        
        .col-resultado {
            width: 12%;
            text-align: center;
        }
        
        .table-modulo {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 10px;
        }
        
        .table-unidad {
            font-size: 10px;
        }
        
        .table-unidad td:first-child {
            padding-left: 10px !important;
        }
        
        .resumen {
            margin-top: 12px;
            padding: 5px;
            border: 0.5px solid #000;
            background-color: #f9f9f9;
        }
        
        .resumen-grid {
            display: grid;
            grid-template-columns: 80px 1fr;
            gap: 3px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 0.5px solid #000;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
            font-size: 12px;
        }
        
        .signature-line {
            margin-top: 25px;
            border-top: 0.5px solid #000;
            padding-top: 2px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Asegurar que no haya saltos de página dentro de elementos importantes */
        .table, .table tr, .table td, .table th {
            page-break-inside: avoid;
        }
        
        /* Optimizar espacios en blanco */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <!-- Cabecera -->
    <div class="header">
        <h1>ACTA DE CALIFICACIONES - GRADO C</h1>
        <h2>FORMACIÓN PROFESIONAL PARA EL EMPLEO</h2>
        <h3>CERTIFICADO DE PROFESIONALIDAD</h3>
    </div>

    <!-- Información del curso y alumno - Diseño en grid -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Curso:</div>
                <div class="info-value">{{ Str::limit($cursoAcademico->curso->nombre, 40) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Código:</div>
                <div class="info-value">{{ $cursoAcademico->curso->codigo }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Inicio:</div>
                <div class="info-value">{{ $fechaInicial ? $fechaInicial->format('d/m/Y') : '--/--/----' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Final:</div>
                <div class="info-value">{{ $fechaFinal ? $fechaFinal->format('d/m/Y') : '--/--/----' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Alumno:</div>
                <div class="info-value">{{ Str::limit($alumno->nombre, 35) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">DNI:</div>
                <div class="info-value">{{ $alumno->dni }}</div>
            </div>
            <div class="info-item" style="grid-column: 1 / span 2;">
                <div class="info-label">Centro:</div>
                <div class="info-value">{{ Str::limit($centroFormacion->name ?? 'Centro de Formación', 60) }}</div>
            </div>
        </div>
    </div>

    <!-- Tabla de calificaciones -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-codigo">Código</th>
                    <th class="col-modulo">Módulo / Unidad Formativa</th>
                    <th class="col-horas">Horas</th>
                    <th class="col-calificacion">Calificación</th>
                    <th class="col-resultado">Apto/No Apto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modulos as $modulo)
                    <!-- Fila del módulo -->
                    <tr class="table-modulo">
                        <td class="col-codigo">{{ $modulo->codigo }}</td>
                        <td class="col-modulo">{{ Str::limit($modulo->nombre, 60) }}</td>
                        <td class="col-horas">{{ $modulo->horas ?? '--' }}</td>
                        <td class="col-calificacion">
                            @php
                                $moduloNota = 0;
                                $unidadCount = $modulo->unidades->count();
                                if($unidadCount > 0){
                                    $suma = 0;
                                    foreach($modulo->unidades as $unidad){
                                        if(isset($calificacionesPorUnidad[$modulo->id][$unidad->id]) ){
                                            $suma += $calificacionesPorUnidad[$modulo->id][$unidad->id];
                                        } else {
                                            $unidadCount--; // restamos si no hay calificación
                                        }
                                    }
                                    $moduloNota = $unidadCount > 0 ? $suma / $unidadCount : null;
                                } else {
                                    $moduloNota = $calificacionesPorModulo[$modulo->id] ?? null;
                                }
                            @endphp
                            {{ $moduloNota !== null ? number_format($moduloNota, 1) : '--' }}
                        </td>
                        <td class="col-resultado">
                            @if($moduloNota !== null)
                                {{ $moduloNota >= 5 ? 'APTO' : 'NO APTO' }}
                            @endif
                        </td>
                    </tr>

                    
                    <!-- Filas de unidades formativas -->
                    @if($modulo->unidades->count() > 0)
                        @foreach($modulo->unidades as $unidad)
                            <tr class="table-unidad">
                                <td class="col-codigo">{{ $unidad->codigo }}</td>
                                <td class="col-modulo">{{ Str::limit($unidad->nombre, 60) }}</td>
                                <td class="col-horas">{{ $unidad->horas ?? '--' }}</td>
                                <td class="col-calificacion">
                                    @if(isset($calificacionesPorUnidad[$modulo->id][$unidad->id]) )
                                        {{ number_format($calificacionesPorUnidad[$modulo->id][$unidad->id], 1) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="col-resultado">
                                    @if(isset($calificacionesPorUnidad[$modulo->id][$unidad->id]) )
                                        {{ $calificacionesPorUnidad[$modulo->id][$unidad->id] >= 5 ? 'APTO' : 'NO APTO' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Resumen final -->
    <div class="resumen">
        <div class="resumen-grid">
            <div style="font-weight: bold; font-size: 10px;">Calificación Media:</div>
            <div style="font-size: 8px;">
                @php
                    $totalCalificaciones = 0;
                    $contador = 0;
                    
                    // Sumar calificaciones de módulos
                    foreach($calificacionesPorModulo as $calificacion) {
                        $totalCalificaciones += $calificacion;
                        $contador++;
                    }
                    
                    // Sumar calificaciones de unidades
                    foreach($calificacionesPorUnidad as $unidades) {
                        foreach($unidades as $calificacion) {
                            $totalCalificaciones += $calificacion;
                            $contador++;
                        }
                    }
                    
                    $media = $contador > 0 ? $totalCalificaciones / $contador : 0;
                @endphp
                {{ number_format($media, 2) }}
            </div>
            
            <div style="font-weight: bold; font-size: 12px;">Resultado Final:</div>
            <div style="font-size: 8px; font-weight: bold;">
                {{ $media >= 5 ? 'APTO' : 'NO APTO' }}
            </div>
        </div>
    </div>

    <!-- Sección de firmas -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Firma del Alumno</div>
            <div class="signature-line"></div>
            <div style="margin-top: 2px;">{{ Str::limit($alumno->nombre, 25) }}</div>
            <div style="font-size: 6px; margin-top: 1px;">DNI: {{ $alumno->dni }}</div>
        </div>
        
        <div class="signature-box">
            <div>Firma del Tutor/Responsable</div>
            <div class="signature-line"></div>
            <div style="margin-top: 2px;">{{ Str::limit($centroFormacion->name ?? 'Responsable del Centro', 25) }}</div>
            <div style="font-size: 6px; margin-top: 1px;">{{ date('d/m/Y') }}</div>
        </div>
    </div>
</body>
</html>