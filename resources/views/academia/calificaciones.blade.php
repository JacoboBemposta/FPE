@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="container mt-4">
    <h1>Calificaciones del Curso:</h1>
    <h2>{{ $cursoAcademico->curso->nombre }}</h2>

    <!-- Formulario para seleccionar módulos, unidades y alumnos -->
    <form id="form-actas" action="{{ route('generar.actas', 'gradoA') }}" method="POST" target="_blank">
        @csrf
        <!-- Campo oculto para el curso_academico_id -->
        <input type="hidden" name="curso_academico_id" value="{{ $cursoAcademico->id }}">        

        <!-- Tabla de calificaciones -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    @foreach($cursoAcademico->curso->modulos as $modulo)
                        @if($modulo->unidades->count() > 0)
                            <th colspan="{{ $modulo->unidades->count() }}">
                                <div class="form-check">
                                    <!-- Checkbox para seleccionar módulo -->
                                    <input class="form-check-input modulo-checkbox" type="checkbox" 
                                        name="modulos[]" value="{{ $modulo->id }}" 
                                        id="modulo{{ $modulo->id }}">
                                    <label class="form-check-label" for="modulo{{ $modulo->id }}">
                                        {{ $modulo->codigo }}
                                    </label>
                                </div>
                            </th>
                        @else
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input modulo-checkbox" type="checkbox" 
                                        name="modulos[]" value="{{ $modulo->id }}" 
                                        id="modulo{{ $modulo->id }}">
                                    <label class="form-check-label" for="modulo{{ $modulo->id }}">
                                        {{ $modulo->codigo }}
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
                                <th>
                                    {{ $unidad->codigo }}
                                </th>
                            @endforeach
                        @else
                            <th>{{ $modulo->codigo }}</th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($cursoAcademico->alumnos as $alumno)
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input alumno-checkbox" type="checkbox" 
                                name="alumnos[]" value="{{ $alumno->id }}" 
                                id="alumno{{ $alumno->id }}">
                            <label class="form-check-label" for="alumno{{ $alumno->id }}">
                                {{ $alumno->nombre }}
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
                                <td>
                                    <input type="number" 
                                        name="calificaciones[{{ $alumno->id }}][unidad][{{ $unidad->id }}]" 
                                        class="form-control" 
                                        @if($calificacion)
                                            value="{{ $calificacion->nota }}"
                                            data-calificacion-id="{{ $calificacion->id }}"
                                            onchange="updateCalificacion(this)"
                                        @else
                                            value=""
                                            data-curso-academico-id="{{ $cursoAcademico->id }}"
                                            data-alumno-id="{{ $alumno->id }}"
                                            data-unidad-id="{{ $unidad->id }}"
                                            onchange="createCalificacion(this)"
                                        @endif
                                    >
                                </td>
                            @endforeach
                        @else
                            @php
                                $calificacionModulo = $alumno->calificaciones
                                    ->where('modulo_id', $modulo->id)
                                    ->whereNull('unidad_formativa_id')
                                    ->first();
                            @endphp
                            <td>
                                <input type="number" 
                                    name="calificaciones[{{ $alumno->id }}][modulo][{{ $modulo->id }}]" 
                                    class="form-control" 
                                    @if($calificacionModulo)
                                        value="{{ $calificacionModulo->nota }}"
                                        data-calificacion-id="{{ $calificacionModulo->id }}"
                                        onchange="updateCalificacion(this)"
                                    @else
                                        value=""
                                        data-curso-academico-id="{{ $cursoAcademico->id }}"
                                        data-alumno-id="{{ $alumno->id }}"
                                        data-modulo-id="{{ $modulo->id }}"
                                        onchange="createCalificacionModulo(this)"
                                    @endif
                                >
                            </td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botones para generar actas -->
        <div class="text-center mt-4">
            <button type="submit" formaction="{{ route('generar.actas', 'gradoA') }}" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Actas Grado A
            </button>
            <button type="submit" formaction="{{ route('generar.actas', 'gradoB') }}" class="btn btn-warning">
                <i class="fas fa-file-pdf"></i> Actas Grado B
            </button>
            <button type="submit" formaction="{{ route('generar.actas', 'gradoC') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Actas Grado C
            </button>
        </div>
    </form>

    <!-- Botón para volver al curso -->
    <div class="text-center mt-4">
        <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" class="btn btn-info btn-sm">Volver al curso</a>
    </div>
</div>


<script>


// Asignar evento al formulario
document.getElementById('form-actas').addEventListener('submit', function (e) {
    // Si no hay alumnos seleccionados, seleccionar todos
    const alumnosCheckboxes = document.querySelectorAll('input[name="alumnos[]"]:checked');
    if (alumnosCheckboxes.length === 0) {
        document.querySelectorAll('input[name="alumnos[]"]').forEach(alumno => {
            alumno.checked = true;
        });
    }
});


// Función para validar el formulario antes de enviarlo
function validarFormulario(grado) {
 
    const modulosSeleccionados = document.querySelectorAll('input[name="modulos[]"]:checked').length;
    const alumnosSeleccionados = document.querySelectorAll('input[name="alumnos[]"]').length;

    
    // Validación para Grado A
    if (grado === 'gradoA' && modulosSeleccionados !== 1) {
        alert('Para el Grado A, debes seleccionar exactamente un modulo.');
        return false;
    }


    // Validación para Grado B
    if (grado === 'gradoB' && modulosSeleccionados !== 1) {
        alert('Para el Grado B, debes seleccionar exactamente un modulo.');
        return false;
    }
    // Validación para Grado C
    if (grado === 'gradoC') {
        const modulosTotales = document.querySelectorAll('input[name="modulos[]"]').length;

    }

    return true;
}

// Asignar eventos a los botones de generación de actas
document.querySelectorAll('button[formaction]').forEach(button => {
    button.addEventListener('click', function (event) {
        const grado = this.getAttribute('formaction').split('/').pop(); // Obtener el grado del formaction
        if (!validarFormulario(grado)) {
            event.preventDefault(); // Evitar que el formulario se envíe si la validación falla
        }
    });
});


// Función para actualizar calificación
function updateCalificacion(input) {
    const calificacionId = input.getAttribute('data-calificacion-id');
    const nota = input.value;

    if (nota < 0 || nota > 10) {
        alert('La nota debe estar entre 0 y 10');
        input.value = '';
        return;
    }

    fetch(`/calificaciones/${calificacionId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ nota })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) alert('Error al actualizar');
    })
    .catch(error => console.error('Error:', error));
}

// Función para crear calificación de módulo sin unidades
function createCalificacionModulo(input) {
    const moduloId = input.getAttribute('data-modulo-id');
    const nota = input.value;

    if (nota < 0 || nota > 10) {
        alert('La nota debe estar entre 0 y 10');
        input.value = '';
        return;
    }

    fetch('/calificaciones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            modulo_id: moduloId,
            alumno_curso_id: input.getAttribute('data-alumno-id'),
            curso_academico_id: input.getAttribute('data-curso-academico-id'),
            nota: nota
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.setAttribute('data-calificacion-id', data.calificacion_id);
            input.removeAttribute('data-modulo-id');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Función original para crear calificaciones de unidades
function createCalificacion(input) {
    const data = {
        unidad_formativa_id: input.getAttribute('data-unidad-id'),
        alumno_curso_id: input.getAttribute('data-alumno-id'),
        curso_academico_id: input.getAttribute('data-curso-academico-id'),
        nota: input.value
    };

    fetch('/calificaciones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.setAttribute('data-calificacion-id', data.calificacion_id);
            input.removeAttribute('data-unidad-id');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

@endsection