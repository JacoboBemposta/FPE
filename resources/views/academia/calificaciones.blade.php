@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Calificaciones del Curso: </h1>
    <h2>{{ $cursoAcademico->curso->nombre }}</h2>

    <!-- Tabla de calificaciones -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alumnos</th>
                @foreach($cursoAcademico->curso->modulos as $modulo)
                    @if($modulo->unidades->count() > 0)
                        <th colspan="{{ $modulo->unidades->count() }}">{{ $modulo->codigo }}</th>
                    @else
                        <th>{{ $modulo->codigo }}</th>
                    @endif
                @endforeach
            </tr>
            <tr>
                <th></th>
                @foreach($cursoAcademico->curso->modulos as $modulo)
                    @if($modulo->unidades->count() > 0)
                        @foreach($modulo->unidades as $unidad)
                            <th>{{ $unidad->codigo }}</th>
                        @endforeach
                    @else
                        @if($modulo->codigo=="MP0391")
                            <th>Módulo de prácticas</th>
                        @else
                            <th>{{ $modulo->codigo }}</th>
                        @endif
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($cursoAcademico->alumnos as $alumno)
            <tr>
                <td>{{ $alumno->nombre }}</td>
                @foreach($cursoAcademico->curso->modulos as $modulo)
                    @foreach($modulo->unidades as $unidad)
                        @php
                            // Buscar la calificación de este alumno y unidad
                            $calificacion = $alumno->calificaciones->where('unidad_formativa_id', $unidad->id)->first();
                        @endphp
                        <td>
                            @if($calificacion)
                                <input type="number" class="form-control" value="{{ $calificacion->nota }}" data-calificacion-id="{{ $calificacion->id }}" onchange="updateCalificacion(this)">
                            @else
                                <input type="number" class="form-control" value="" 
                                       data-curso-academico-id="{{ $cursoAcademico->id }}" 
                                       data-alumno-id="{{ $alumno->id }}" 
                                       data-unidad-id="{{ $unidad->id }}" 
                                       data-modulo-id="{{ $modulo->id }}" 
                                       onchange="createCalificacion(this)">
                            @endif
                        </td>
                    @endforeach
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Agregar el código de Ajax para actualizar las calificaciones -->
<script>
// Función para actualizar calificación mediante AJAX
function updateCalificacion(input) {
    const calificacionId = input.getAttribute('data-calificacion-id');
    const nota = input.value;

    // Validar que la nota esté en el rango correcto
    if (nota < 0 || nota > 10) {
        alert('La nota debe estar entre 0 y 10');
        input.value = ''; // Restablecer el valor
        return;
    }

    // Usar AJAX para actualizar la calificación
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
        if (data.success) {
            alert('Calificación actualizada correctamente');
        } else {
            alert('Hubo un error al actualizar la calificación');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al actualizar la calificación');
    });
}

// Función para crear una nueva calificación mediante AJAX si no existe
function createCalificacion(input) {
    const unidadId = input.getAttribute('data-unidad-id');
    const alumnoCursoId = input.getAttribute('data-alumno-id'); // Cambia el nombre a algo más descriptivo
    const cursoAcademicoId = input.getAttribute('data-curso-academico-id');
    const moduloId = input.getAttribute('data-modulo-id');
    const nota = input.value;

    console.log('Datos enviados:', {
        curso_academico_id: cursoAcademicoId,
        alumno_curso_id: alumnoCursoId, // Cambia a alumno_curso_id
        unidad_formativa_id: unidadId,
        modulo_id: moduloId,
        nota: nota
    });

    // Validar que la nota esté en el rango correcto
    if (nota < 0 || nota > 10) {
        alert('La nota debe estar entre 0 y 10');
        input.value = ''; // Restablecer el valor
        return;
    }

    // Usar AJAX para crear una nueva calificación
    fetch('/calificaciones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({

            alumno_curso_id: alumnoCursoId, // Cambia a alumno_curso_id
            unidad_formativa_id: unidadId,

            nota: nota
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Calificación creada correctamente');
            // Actualizar el input con el ID de la nueva calificación
            input.setAttribute('data-calificacion-id', data.calificacion_id);
            input.removeAttribute('data-unidad-id');
            input.removeAttribute('data-alumno-id');
        } else {
            alert('Hubo un error al crear la calificación');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al crearse la calificación');
    });
}
</script>

@endsection
