@extends('layouts.app')

@section('content')
<style>
    .header-calificaciones {
        background: linear-gradient(135deg, #677cc8 0%, #0720ad 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .table-calificaciones {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .table-calificaciones thead {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    .table-calificaciones th {
        vertical-align: middle;
        text-align: center;
    }
    .table-calificaciones td {
        vertical-align: middle;
    }
    .calificacion-input {
        width: 70px;
        margin: 0 auto;
        text-align: center;
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 5px;
        transition: all 0.2s ease;
    }
    .calificacion-input.calificacion-guardada {
        background-color: #e8f4e3;
        border-color: #28a745;
    }
    .checkbox-modulo {
        transform: scale(1.2);
        margin-right: 5px;
    }
    .checkbox-alumno {
        transform: scale(1.1);
        margin-right: 8px;
    }
    .badge-modulo {
        background-color: #12047a;
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 10px;
    }
    .form-check-label {
        cursor: pointer;
    }
    .modulo-header {
        background-color: #e9ecef;
        font-weight: bold;
    }
</style>

<div class="container py-4">
    <!-- Encabezado -->
    <div class="header-calificaciones">
        <h2 class="mb-1">Calificaciones del Curso</h2>
        <h3 class="mb-0">{{ $cursoAcademico->curso->nombre }}</h3>
    </div>

    <!-- Tabla de calificaciones -->
    <div class="table-responsive">
        <table class="table table-calificaciones table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 200px;"></th>
                    @foreach($cursoAcademico->curso->modulos as $modulo)
                        @if($modulo->unidades->count() > 0)
                            <th colspan="{{ $modulo->unidades->count() }}" class="text-center modulo-header">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input checkbox-modulo modulo-checkbox" 
                                           type="checkbox" 
                                           name="modulos[]" 
                                           value="{{ $modulo->id }}" 
                                           id="modulo{{ $modulo->id }}">
                                    <label class="form-check-label" for="modulo{{ $modulo->id }}">
                                        <span class="badge badge-modulo">{{ $modulo->codigo }}</span>
                                    </label>
                                </div>
                            </th>
                        @else
                            <th class="text-center modulo-header">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input checkbox-modulo modulo-checkbox" 
                                           type="checkbox" 
                                           name="modulos[]" 
                                           value="{{ $modulo->id }}" 
                                           id="modulo{{ $modulo->id }}">
                                    <label class="form-check-label" for="modulo{{ $modulo->id }}">
                                        <span class="badge badge-modulo">{{ $modulo->codigo }}</span>
                                    </label>
                                </div>
                            </th>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    <th>Alumnos</th>
                    @foreach($cursoAcademico->curso->modulos as $modulo)
                        @if($modulo->unidades->count() > 0)
                            @foreach($modulo->unidades as $unidad)
                                <th class="text-center">
                                    <small>{{ $unidad->codigo }}</small>
                                </th>
                            @endforeach
                        @else
                            <th class="text-center">
                                <small>{{ $modulo->codigo }}</small>
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($cursoAcademico->alumnos as $alumno)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input checkbox-alumno alumno-checkbox" 
                                       type="checkbox" 
                                       name="alumnos[]" 
                                       value="{{ $alumno->id }}" 
                                       id="alumno{{ $alumno->id }}">
                                <label class="form-check-label" for="alumno{{ $alumno->id }}">
                                    {{ Str::limit($alumno->nombre, 25) }}
                                </label>
                            </div>
                        </td>
                        @foreach($cursoAcademico->curso->modulos as $modulo)
                            @if($modulo->unidades->count() > 0)
                                @foreach($modulo->unidades as $unidad)
                                    @php
                                        $calificacion = $alumno->calificaciones
                                            ->where('unidad_formativa_id', $unidad->id)
                                            ->first();
                                    @endphp
                                    <td class="text-center">
                                        <input type="number" 
                                               name="calificaciones[{{ $alumno->id }}][unidad][{{ $unidad->id }}]" 
                                               class="form-control calificacion-input" 
                                               min="0" max="10" step="0.01"
                                               value="{{ $calificacion ? $calificacion->nota : '' }}"
                                               @if($calificacion) data-calificacion-id="{{ $calificacion->id }}" @endif
                                               data-tipo="unidad"
                                               data-curso-academico-id="{{ $cursoAcademico->id }}"
                                               data-alumno-curso-id="{{ $alumno->id }}"
                                               data-unidad-id="{{ $unidad->id }}">
                                    </td>
                                @endforeach
                            @else
                                @php
                                    $calificacionModulo = $alumno->calificaciones
                                        ->where('modulo_id', $modulo->id)
                                        ->whereNull('unidad_formativa_id')
                                        ->first();
                                @endphp
                                <td class="text-center">
                                    <input type="number" 
                                           name="calificaciones[{{ $alumno->id }}][modulo][{{ $modulo->id }}]" 
                                           class="form-control calificacion-input" 
                                           min="0" max="10" step="0.01"
                                           value="{{ $calificacionModulo ? $calificacionModulo->nota : '' }}"
                                           @if($calificacionModulo) data-calificacion-id="{{ $calificacionModulo->id }}" @endif
                                           data-tipo="modulo"
                                           data-curso-academico-id="{{ $cursoAcademico->id }}"
                                           data-alumno-curso-id="{{ $alumno->id }}"
                                           data-modulo-id="{{ $modulo->id }}">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botón para volver -->
    <div class="text-center mt-4">
        <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" 
           class="btn btn-primary btn-action">
            <i class="fas fa-arrow-left mr-2"></i> Volver al curso
        </a>
    </div>
</div>

@push('scripts')
    <meta name="calificaciones-store-route" content="{{ route('academia.calificaciones.store', [], false) }}">
    <script src="{{ asset('public/js/academia-calificaciones.js?v=' . time()) }}"></script>
@endpush

@endsection
