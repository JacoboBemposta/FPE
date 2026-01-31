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
    }
    .btn-acta {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
        margin: 0 10px;
        transition: all 0.3s;
    }
    .btn-acta:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

    <!-- Formulario de calificaciones -->
    <form id="form-actas" action="{{ route('generar.actas', 'gradoA') }}" method="POST" target="_blank">
        @csrf
        <input type="hidden" name="curso_academico_id" value="{{ $cursoAcademico->id }}">

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
                                                   @if($calificacion)
                                                       value="{{ $calificacion->nota }}"
                                                       data-calificacion-id="{{ $calificacion->id }}"
                                                       data-tipo="unidad"
                                                   @else
                                                       value=""
                                                       data-tipo="unidad"
                                                       data-curso-academico-id="{{ $cursoAcademico->id }}"
                                                       data-alumno-curso-id="{{ $alumno->id }}"
                                                       data-unidad-id="{{ $unidad->id }}"
                                                   @endif
                                                   onchange="guardarCalificacion(this)">
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
                                               @if($calificacionModulo)
                                                   value="{{ $calificacionModulo->nota }}"
                                                   data-calificacion-id="{{ $calificacionModulo->id }}"
                                                   data-tipo="modulo"
                                               @else
                                                   value=""
                                                   data-tipo="modulo"
                                                   data-curso-academico-id="{{ $cursoAcademico->id }}"
                                                   data-alumno-curso-id="{{ $alumno->id }}"
                                                   data-modulo-id="{{ $modulo->id }}"
                                               @endif
                                               onchange="guardarCalificacion(this)">
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Botones para generar actas -->
        <div class="text-center mt-4">
            <button type="submit" formaction="{{ route('generar.actas', 'gradoB') }}" 
                    class="btn btn-warning btn-acta">
                <i class="fas fa-file-pdf mr-2"></i> Acta Grado B
            </button>
            <button type="submit" formaction="{{ route('generar.actas', 'gradoC') }}" 
                    class="btn btn-danger btn-acta">
                <i class="fas fa-file-pdf mr-2"></i> Acta Grado C
            </button>
        </div>
    </form>

    <!-- Botón para volver -->
    <div class="text-center mt-4">
        <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" 
           class="btn btn-primary btn-action">
            <i class="fas fa-arrow-left mr-2"></i> Volver al curso
        </a>
    </div>
</div>

<script>
// Función para guardar calificaciones
async function guardarCalificacion(input) {
    const nota = parseFloat(input.value);
    
    if (isNaN(nota)) {
        alert('Por favor ingrese un número válido');
        input.value = '';
        return;
    }

    if (nota < 0 || nota > 10) {
        alert('La nota debe estar entre 0 y 10');
        input.value = '';
        return;
    }

    const tipo = input.getAttribute('data-tipo');
    const data = {
        alumno_curso_id: input.getAttribute('data-alumno-curso-id'),
        curso_academico_id: input.getAttribute('data-curso-academico-id'),
        nota: nota
    };

    if (tipo === 'unidad') {
        data.unidad_formativa_id = input.getAttribute('data-unidad-id');
        data.modulo_id = null;
    } else {
        data.modulo_id = input.getAttribute('data-modulo-id');
        data.unidad_formativa_id = null;
    }

    const calificacionId = input.getAttribute('data-calificacion-id');
    let url, method, data_send;

    if (calificacionId) {
        // CAMBIA: Añade el prefijo academia/
        url = `/academia/calificaciones/${calificacionId}`;
        method = 'PUT';
        data_send = { nota: nota };
    } else {
        // CAMBIA: Añade el prefijo academia/
        url = '/academia/calificaciones';
        method = 'POST';
        data_send = data;
    }

 

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data_send)
        });

        const result = await response.json();


        if (!response.ok) {
            let errorMsg = result.message || 'Error al guardar la calificación';
            if (result.errors) {
                errorMsg += '\n' + Object.values(result.errors).flat().join('\n');
            }
            throw new Error(errorMsg);
        }

        if (!calificacionId && result.data && result.data.id) {
            input.setAttribute('data-calificacion-id', result.data.id);

        }

        // Feedback visual
        input.classList.add('border-success');
        setTimeout(() => input.classList.remove('border-success'), 1000);
        
    } catch (error) {
        console.error('Error completo:', error);
        alert('Error: ' + error.message);
        input.value = '';
        input.focus();
    }
}
// Validación de formulario para actas
function validarFormulario(grado) {
    const modulosSeleccionados = document.querySelectorAll('input[name="modulos[]"]:checked').length;
    

    if (grado === 'gradoB' && modulosSeleccionados !== 1) {
        alert('Para el Grado B, debes seleccionar exactamente un módulo.');
        return false;
    }

    return true;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Validación al enviar actas
    document.querySelectorAll('button[formaction]').forEach(button => {
        button.addEventListener('click', function(event) {
            const grado = this.getAttribute('formaction').split('/').pop();
            if (!validarFormulario(grado)) {
                event.preventDefault();
            }
        });
    });

    // Seleccionar todos los alumnos si ninguno está seleccionado
    document.getElementById('form-actas').addEventListener('submit', function(e) {
        const alumnosCheckboxes = document.querySelectorAll('input[name="alumnos[]"]:checked');
        if (alumnosCheckboxes.length === 0) {
            document.querySelectorAll('input[name="alumnos[]"]').forEach(alumno => {
                alumno.checked = true;
            });
        }
    });

    // Selección/deselección de módulos
    document.querySelectorAll('.modulo-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const moduloId = this.value;
            const isChecked = this.checked;
                        

        });
    });
});

</script>

@endsection