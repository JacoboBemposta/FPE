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

  .fecha-cambio {
    transition: all 0.3s ease;
  }

  .fecha-cambio:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  .fecha-cambio.actualizado {
    background-color: #e8f4e3;
    border-color: #28a745;
  }

  .resaltar-nombre {
    background-color: #e8f4e3;  /* Color de fondo verde claro */
    padding: 2px 5px;  /* Espaciado alrededor del texto */
    border-radius: 3px;  /* Bordes redondeados */
    }
</style>

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
    
                <!-- Mostrar número de participantes (alumnos + docente) -->
                <strong>Número de Alumnos:</strong> 
                {{ $cursoAcademico->alumnos->where('es_profesor', 0)->count() }} <br>
    
                <!-- Mostrar nombre del docente si existe -->
                @php
                    $docente = $cursoAcademico->alumnos->where('es_profesor', 1)->first();
                @endphp
                <strong>Docente:</strong>
                @if($docente)
                    {{ $docente->nombre }}
                @else
                <span class="text-muted">⚠️ Sin docente asignado</span>
                @endif
                <br>

            </p>
        </div>
    </div>
    

    <button class="btn btn-info mb-3" onclick="toggleCursoDetalles()">Detalles del Curso</button>

    <div id="cursoDetalles" class="hidden">
        @php
            $detalles = $cursoAcademico->detallesCurso ?? collect();
        @endphp

        @foreach($cursoAcademico->curso->modulos as $modulo)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ $modulo->codigo }} - {{ $modulo->nombre }}</span>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="toggleModulo('{{ $modulo->id }}')">Mostrar/Ocultar</button>
                </div>
                <div id="modulo-{{ $modulo->id }}" class="hidden">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Unidad/Módulo</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Examen0</th>
                                <th>ExamenF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modulo->unidades as $unidad)
                                @php
                                    $detalle = $detalles->where('unidad_formativa_id', $unidad->id)->first();
                                @endphp
                                <tr>
                                    <td>{{ $unidad->codigo }} - {{ $unidad->nombre }}</td>
                                    @foreach(['inicio','fin','Examen0','ExamenF'] as $campo)
                                        @php
                                            $fecha = optional($detalle)->{$campo} ? $detalle->{$campo}->format('Y-m-d') : '';
                                        @endphp
                                        <td>
                                            <input type="date" class="form-control fecha-cambio"
                                                data-unidad="{{ $unidad->id }}"
                                                data-campo="{{ $campo }}"
                                                data-detalle="{{ optional($detalle)->id ?? '' }}"
                                                value="{{ old($campo, $fecha) }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                @php
                                    $detalleModulo = $detalles->where('modulo_id', $modulo->id)->whereNull('unidad_formativa_id')->first();
                                @endphp
                                <tr>
                                    <td>{{ $modulo->codigo }} (Módulo)</td>
                                    @foreach(['inicio','fin','Examen0','ExamenF'] as $campo)
                                        @php
                                            $fecha = optional($detalleModulo)->{$campo} ? $detalleModulo->{$campo}->format('Y-m-d') : '';
                                        @endphp
                                        <td>
                                            <input type="date" class="form-control fecha-cambio"
                                                data-modulo="{{ $modulo->id }}"
                                                data-campo="{{ $campo }}"
                                                data-detalle="{{ optional($detalleModulo)->id ?? '' }}"
                                                value="{{ old($campo, $fecha) }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Botón para abrir el modal de Agregar Alumno -->
    @if($cursoAcademico->alumnos->count() < 25)
        <a href="#addAlumnoModal" class="btn btn-primary mb-3">Agregar Participante</a>
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
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="es_profesor" name="es_profesor" value="1">
                    <label class="form-check-label" for="es_profesor">¿Es profesor?</label>
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
                    <td class="{{ $alumno->es_profesor ? 'resaltar-nombre' : '' }}">
                        {{ $alumno->nombre }}
                        @if($alumno->es_profesor)
                            <span class="badge bg-success">Profesor</span>
                        @endif
                    </td>
                    
                    <td>{{ $alumno->email }}</td>
                    <td>{{ $alumno->telefono }}</td>
                    <td>
                        <!-- Botón para editar alumno (abre modal sin JS) -->
                        <a href="#editAlumnoModal-{{ $alumno->id }}" class="btn btn-warning btn-sm">Editar</a>
                        <!-- Formulario para eliminar alumno -->
                        <form method="POST" action="{{ route('academia.eliminarAlumno', $alumno->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este alumno?')">
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
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="edit_profesor_{{ $alumno->id }}" name="es_profesor" value="1" {{ $alumno->es_profesor ? 'checked' : '' }}>
                <label class="form-check-label" for="edit_profesor_{{ $alumno->id }}">¿Es profesor?</label>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</div>
@endforeach

<script>
    function toggleCursoDetalles() {
      document.getElementById('cursoDetalles').classList.toggle('hidden');
    }

    function toggleModulo(moduloId) {
      document.getElementById('modulo-' + moduloId).classList.toggle('hidden');
    }

    async function saveDetalle(payload, inputElement) {
        try {
            const res = await fetch("{{ route('academia.crearActualizarDetalle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            if (!data.success) throw new Error(data.message);

            // Actualizar UI
            inputElement.setAttribute('data-detalle', data.detalle.id);
            inputElement.value = data.detalle[payload.campo] 
                                ? new Date(data.detalle[payload.campo]).toISOString().split('T')[0]
                                : '';

            // Feedback visual
            inputElement.classList.add('actualizado');
            setTimeout(() => inputElement.classList.remove('actualizado'), 2000);

        } catch (err) {
            console.error("Error:", err);
            alert("Error: " + err.message);
            inputElement.value = inputElement.defaultValue;
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".fecha-cambio").forEach(input => {
            input.addEventListener("change", function() {
                const payload = {
                    detalle_id: this.dataset.detalle || null,
                    curso_academico_id: "{{ $cursoAcademico->id }}",
                    campo: this.dataset.campo,
                    valor: this.value
                };

                // Determinar tipo de relación
                if (this.dataset.unidad) {
                    payload.unidad_formativa_id = this.dataset.unidad;
                } else if (this.dataset.modulo) {
                    payload.modulo_id = this.dataset.modulo;
                }

                saveDetalle(payload, this);
            });
        });
    });
</script>

@endsection