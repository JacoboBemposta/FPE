@extends('layouts.app')

@section('content')
<!-- Estilos para modal sin JavaScript -->
<style>
  /* Modal base */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;
  }
  /* Mostrar el modal cuando es el objetivo (target) */
  .modal:target {
    display: flex;
  }
  /* Contenedor interno del modal */
  .modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    position: relative;
    max-width: 500px;
    width: 90%;
  }
  /* Botón de cerrar modal */
  .close {
    position: absolute;
    top: 10px;
    right: 10px;
    text-decoration: none;
    font-size: 1.5rem;
    color: #000;
  }
  .hidden { display: none; }

</style>


<div class="container mt-4">
    <!-- Información del curso -->
    <div class="container mt-4">
    
        <!-- Información del curso -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Información del Curso</h5>
                <p class="card-text">
                    <strong>Familia Profesional:</strong> {{ $cursoAcademico->curso->FamiliaProfesional->nombre }}<br>
                    <strong>Código:</strong> {{ $cursoAcademico->curso->codigo }}<br>
                    <strong>Curso:</strong> {{ $cursoAcademico->curso->nombre }}<br>
                    <strong>Horas:</strong> {{ $cursoAcademico->curso->horas }}<br>
                </p>
            </div>
        </div>
    
        <!-- Botón para mostrar/ocultar detalles del curso -->
        <button class="btn btn-info mb-3" onclick="toggleCursoDetalles()">Detalles del Curso</button>
    
        <!-- Detalles del curso -->
        <div id="cursoDetalles" class="hidden">
            @foreach($cursoAcademico->curso->modulos as $modulo)
                <div class="card mb-3">
                    <div class="card-header">
                        <span>{{ $modulo->codigo }} - {{ $modulo->nombre }}</span>
                        <button class="btn btn-secondary btn-sm float-end" type="button" onclick="toggleModulo('{{ $modulo->id }}')">
                            Mostrar/Ocultar Unidades
                        </button>
                    </div>
                    <div id="modulo-{{ $modulo->id }}" class="hidden">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Unidad Formativa</th>
                                    {{-- <th>Código</th> --}}
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Examen0</th>
                                    <th>ExamenF</th>
   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modulo->unidades as $unidad)
                                    @php
                                        // Buscar el detalle asociado a esta unidad
                                        $detalle = $cursoAcademico->detallesCurso->where('unidad_formativa_id', $unidad->id)->first();
                                    @endphp
                                    <tr>
                                        {{-- <td>{{ $unidad->nombre }}</td> --}}
                                        <td>{{ $unidad->codigo }}</td>
                                        <td>
                                            <input type="date" class="form-control fecha-cambio" 
                                                   data-unidad="{{ $unidad->id }}"
                                                   data-detalle="{{ $detalle ? $detalle->id : '' }}" 
                                                   data-campo="inicio" 
                                                   value="{{ $detalle ? $detalle->inicio : '' }}">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control fecha-cambio" 
                                                   data-unidad="{{ $unidad->id }}"
                                                   data-detalle="{{ $detalle ? $detalle->id : '' }}" 
                                                   data-campo="fin" 
                                                   value="{{ $detalle ? $detalle->fin : '' }}">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control fecha-cambio" 
                                                   data-unidad="{{ $unidad->id }}"
                                                   data-detalle="{{ $detalle ? $detalle->id : '' }}" 
                                                   data-campo="Examen0" 
                                                   value="{{ $detalle ? $detalle->Examen0 : '' }}">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control fecha-cambio" 
                                                   data-unidad="{{ $unidad->id }}"
                                                   data-detalle="{{ $detalle ? $detalle->id : '' }}" 
                                                   data-campo="ExamenF" 
                                                   value="{{ $detalle ? $detalle->ExamenF : '' }}">
                                        </td>
            
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    
    
    <div>
            <!-- Botón para abrir el modal de Agregar Alumno -->
    @if($cursoAcademico->alumnos->count() < 25)
        <a href="#addAlumnoModal" class="btn btn-primary mb-3">Agregar Alumno</a>
    @else
        <div class="alert alert-warning">Se alcanzó el máximo de 25 alumnos.</div>
    @endif
    <button class="btn btn-primary mb-3">
        <a href="{{ route('academia.calificaciones', ['cursoAcademicoId' => $cursoAcademico->id]) }}" class="text-white">Calificaciones</a>
    </button>
<!-- Modal para Agregar Alumno (CSS Only) -->
<div id="addAlumnoModal" class="modal">
  <div class="modal-content">
      <a href="#" class="close">&times;</a>
      <h5>Agregar Alumno</h5>
      <form method="POST" action="{{ route('academia.guardarAlumno') }}">
        @csrf
        <input type="hidden" name="curso_academico_id" value="{{ $cursoAcademico->id }}">
        <div class="mb-3">
            <label for="dni">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" required maxlength="15">
        </div>
        <div class="mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="255">
        </div>
        <div class="mb-3">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Alumno</button>
      </form>
  </div>
</div>

<!-- Lista de alumnos inscritos -->
<div>
    <h3>Alumnos Inscritos</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursoAcademico->alumnos as $alumno)
            <tr>
                <td>{{ $alumno->dni }}</td>
                <td>{{ $alumno->nombre }}</td>
                <td>{{ $alumno->email }}</td>
                <td>{{ $alumno->telefono }}</td>
                <td>
                    <!-- Botón para editar alumno (abre modal sin JS) -->
                    <a href="#editAlumnoModal-{{ $alumno->id }}" class="btn btn-warning btn-sm">Editar</a>
                    <!-- Formulario para eliminar alumno -->
                    <form method="POST" action="{{ route('academia.eliminarAlumno', $alumno->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('¿Estás seguro de eliminar este alumno?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button class="btn btn-success" style="background-color: #28a745; border-color: #28a745;">
    <a href="{{ route('academia.miscursos') }}" class="text-white">Volver a mis Cursos</a>
</button>
</div>
<!-- Modales para Editar Alumno (uno por alumno) -->
@foreach($cursoAcademico->alumnos as $alumno)
<div id="editAlumnoModal-{{ $alumno->id }}" class="modal">
    <div class="modal-content">
        <a href="#" class="close">&times;</a>
        <h5>Editar Alumno</h5>
        <form method="POST" action="{{ route('academia.editarAlumno', $alumno->id) }}">
            @csrf
            @method('PUT') 
            <input type="hidden" name="curso_academico_id" value="{{ $cursoAcademico->id }}">
            <div class="mb-3">
                <label for="edit_dni_{{ $alumno->id }}">DNI</label>
                <input type="text" class="form-control" id="edit_dni_{{ $alumno->id }}" name="dni" value="{{ $alumno->dni }}" readonly>
            </div>
            <div class="mb-3">
                <label for="edit_nombre_{{ $alumno->id }}">Nombre</label>
                <input type="text" class="form-control" id="edit_nombre_{{ $alumno->id }}" name="nombre" value="{{ $alumno->nombre }}" maxlength="255">
            </div>
            <div class="mb-3">
                <label for="edit_email_{{ $alumno->id }}">Correo Electrónico</label>
                <input type="email" class="form-control" id="edit_email_{{ $alumno->id }}" name="email" value="{{ $alumno->email }}">
            </div>
            <div class="mb-3">
                <label for="edit_telefono_{{ $alumno->id }}">Teléfono</label>
                <input type="text" class="form-control" id="edit_telefono_{{ $alumno->id }}" name="telefono" value="{{ $alumno->telefono }}" maxlength="20">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</div>
@endforeach

</div>

<script>
    function toggleCursoDetalles() {
        document.getElementById('cursoDetalles').classList.toggle('hidden');
    }

    function toggleModulo(moduloId) {
        document.getElementById('modulo-' + moduloId).classList.toggle('hidden');
    }

    function updateDetalle(detalleId, campo, valor) {
    fetch("{{ route('academia.actualizarDetalle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            detalle_id: detalleId,
            campo: campo,
            valor: valor
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Detalle actualizado correctamente");
        } else {
            console.error("Error al actualizar el detalle:", data.message);
        }
    })
    .catch(error => console.error("Error en la solicitud:", error));
}

function createDetalle(unidadId, campo, valor, inputElement) {
    fetch("{{ route('academia.crearDetalle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            unidad_formativa_id: unidadId,
            curso_academico_id: "{{ $cursoAcademico->id }}",
            campo: campo,
            valor: valor
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Detalle creado correctamente");
            // Actualizar el atributo data-detalle del input con el nuevo ID
            inputElement.setAttribute("data-detalle", data.detalle_id);
        } else {
            console.error("Error al crear el detalle:", data.message);
        }
    })
    .catch(error => console.error("Error en la solicitud:", error));
}
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".fecha-cambio").forEach(function(input) {
        input.addEventListener("change", function() {
            let detalleId = this.getAttribute("data-detalle");
            let unidadId = this.getAttribute("data-unidad");
            let campo = this.getAttribute("data-campo");
            let valor = this.value;

            if (!detalleId) {
                // Si no existe un detalle, crearlo
                createDetalle(unidadId, campo, valor, this);
            } else {
                // Si existe, actualizarlo
                updateDetalle(detalleId, campo, valor);
            }
        });
    });
});
</script>
@endsection
