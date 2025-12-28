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
    display: flex !important;
  }
  /* Contenedor interno del modal */
  .modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    position: relative;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
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
  .hidden { 
    display: none !important; 
  }

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
    background-color: #e8f4e3;
    padding: 2px 5px;
    border-radius: 3px;
  }
  
  .modulo-container {
    margin-bottom: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 5px;
  }
  
  .modulo-header {
    background-color: #f8f9fa;
    padding: 10px 15px;
    border-bottom: 1px solid #dee2e6;
  }
  
  .modulo-content {
    padding: 15px;
  }
</style>

<div class="container mt-4">
    <!-- Información del curso -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Información del Curso</h5>
            <p class="card-text">
                <strong>Familia Profesional:</strong> {{ $cursoAcademico->curso->FamiliaProfesional->nombre ?? 'No asignada' }}<br>
                <strong>Código:</strong> {{ $cursoAcademico->curso->codigo ?? 'N/A' }}<br>
                <strong>Curso:</strong> {{ $cursoAcademico->curso->nombre ?? 'N/A' }}<br>
                <strong>Horas:</strong> {{ $cursoAcademico->curso->horas ?? 'N/A' }}<br>
    
                <!-- Mostrar número de participantes (alumnos + docente) -->
                <strong>Número de Participantes:</strong> 
                {{ $cursoAcademico->alumnos->count() + ($cursoAcademico->alumnos->where('es_profesor', 1)->count() > 0 ? 1 : 0) }}<br>
    
                <!-- Mostrar nombre del docente si existe -->
                @php
                    $docente = $cursoAcademico->alumnos->where('es_profesor', 1)->first();
                @endphp
                @if($docente)
                    <strong>Docente:</strong> {{ $docente->nombre }}<br>
                @endif
            </p>
        </div>
    </div>
    


    <button class="btn btn-info mb-3" onclick="toggleCursoDetalles()" id="btnDetallesCurso">
        <i class="fas fa-chevron-down me-2"></i>Detalles del Curso
    </button>

    <div id="cursoDetalles" class="hidden">
        @php
            $detalles = $cursoAcademico->detallesCurso ?? collect();
            $modulos = $cursoAcademico->curso->modulos ?? collect();
        @endphp

        @if($modulos->count() > 0)
            @foreach($modulos as $modulo)
                <div class="modulo-container">
                    <div class="modulo-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $modulo->codigo ?? 'N/A' }}</strong> - {{ $modulo->nombre ?? 'Sin nombre' }}
                            <span class="badge bg-secondary ms-2">
                                {{ $modulo->unidades->count() ?? 0 }} unidades
                            </span>
                        </div>
                        <button class="btn btn-secondary btn-sm" type="button" onclick="toggleModulo({{ $modulo->id ?? 0 }})">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div id="modulo-{{ $modulo->id ?? 0 }}" class="modulo-content hidden">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Unidad/Módulo</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Examen Inicial</th>
                                        <th>Examen Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($modulo->unidades) && $modulo->unidades->count() > 0)
                                        @foreach($modulo->unidades as $unidad)
                                            @php
                                                $detalle = $detalles->where('unidad_formativa_id', $unidad->id)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <small>{{ $unidad->codigo ?? 'N/A' }}</small><br>
                                                    {{ $unidad->nombre ?? 'Sin nombre' }}
                                                </td>
                                                @foreach(['inicio','fin','Examen0','ExamenF'] as $campo)
                                                    @php
                                                        $fecha = optional($detalle)->{$campo} ? 
                                                                \Carbon\Carbon::parse($detalle->{$campo})->format('Y-m-d') : '';
                                                    @endphp
                                                    <td>
                                                        <input type="date" class="form-control form-control-sm fecha-cambio"
                                                            data-unidad="{{ $unidad->id }}"
                                                            data-campo="{{ $campo }}"
                                                            data-detalle="{{ optional($detalle)->id ?? '' }}"
                                                            value="{{ $fecha }}"
                                                            onchange="saveFecha(this)">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        @php
                                            $detalleModulo = $detalles->where('modulo_id', $modulo->id)->whereNull('unidad_formativa_id')->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $modulo->codigo ?? 'N/A' }} (Módulo completo)</strong><br>
                                                {{ $modulo->nombre ?? 'Sin nombre' }}
                                            </td>
                                            @foreach(['inicio','fin','Examen0','ExamenF'] as $campo)
                                                @php
                                                    $fecha = optional($detalleModulo)->{$campo} ? 
                                                            \Carbon\Carbon::parse($detalleModulo->{$campo})->format('Y-m-d') : '';
                                                @endphp
                                                <td>
                                                    <input type="date" class="form-control form-control-sm fecha-cambio"
                                                        data-modulo="{{ $modulo->id }}"
                                                        data-campo="{{ $campo }}"
                                                        data-detalle="{{ optional($detalleModulo)->id ?? '' }}"
                                                        value="{{ $fecha }}"
                                                        onchange="saveFecha(this)">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No hay módulos asignados a este curso.
            </div>
        @endif
    </div>

    <!-- Botón para abrir el modal de Agregar Alumno -->
    @if($cursoAcademico->alumnos->count() < 25)
        <a href="#addAlumnoModal" class="btn btn-primary mb-3">
            <i class="fas fa-user-plus me-2"></i>Agregar Participante
        </a>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-circle me-2"></i>Se alcanzó el máximo de 25 alumnos.
        </div>
    @endif

    <a href="{{ route('academia.calificaciones', ['cursoAcademicoId' => $cursoAcademico->id]) }}" 
       class="btn btn-primary mb-3">
        <i class="fas fa-graduation-cap me-2"></i>Calificaciones
    </a>

    <!-- Modal para Agregar Alumno o Docente (CSS Only) -->
    <div id="addAlumnoModal" class="modal">
        <div class="modal-content">
            <a href="#" class="close">&times;</a>
            <h5 class="mb-3"><i class="fas fa-user-plus me-2"></i>Agregar Alumno / Docente</h5>
            <form method="POST" action="{{ route('academia.guardarAlumno') }}">
                @csrf
                <input type="hidden" name="curso_academico_id" value="{{ $cursoAcademico->id }}">

                <div class="mb-3">
                    <label for="dni" class="form-label">DNI / NIE</label>
                    <input type="text" class="form-control" id="dni" name="dni" maxlength="15">
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20">
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="es_profesor" name="es_profesor" value="1">
                    <label class="form-check-label" for="es_profesor">
                        <b>¿Es el docente?</b>
                    </label>
                </div>

                <div class="form-text mb-3">
                    Los datos de los alumnos y el docente se recogen bajo consentimiento de ellos o de sus representantes legales y serán cifrados y protegidos por RedFPE.
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Lista de alumnos inscritos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Alumnos Inscritos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
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
                        @forelse($cursoAcademico->alumnos as $alumno)
                        <tr>
                            <td>{{ $alumno->dni }}</td>
                            <td class="{{ $alumno->es_profesor ? 'resaltar-nombre' : '' }}">
                                {{ $alumno->nombre }}
                                @if($alumno->es_profesor)
                                    <span class="badge bg-success ms-2">Profesor</span>
                                @endif
                            </td>
                            <td>{{ $alumno->email }}</td>
                            <td>{{ $alumno->telefono }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Botón para editar alumno -->
                                    <a href="#editAlumnoModal-{{ $alumno->id }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Formulario para eliminar alumno -->
                                    <form method="POST" action="{{ route('academia.eliminarAlumno', $alumno->id) }}" 
                                          class="d-inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este alumno?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-user-slash me-2"></i>No hay alumnos inscritos
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('academia.miscursos') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver a mis Cursos
        </a>
    </div>
</div>

<!-- Modales para Editar Alumno (uno por alumno) -->
@foreach($cursoAcademico->alumnos as $alumno)
<div id="editAlumnoModal-{{ $alumno->id }}" class="modal">
    <div class="modal-content">
        <a href="#" class="close">&times;</a>
        <h5 class="mb-3"><i class="fas fa-edit me-2"></i>Editar Alumno</h5>
        <form method="POST" action="{{ route('academia.editarAlumno', $alumno->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="curso_academico_id" value="{{ $cursoAcademico->id }}">
            <div class="mb-3">
                <label for="edit_dni_{{ $alumno->id }}" class="form-label">DNI</label>
                <input type="text" class="form-control" id="edit_dni_{{ $alumno->id }}" 
                       name="dni" value="{{ $alumno->dni }}" readonly>
            </div>
            <div class="mb-3">
                <label for="edit_nombre_{{ $alumno->id }}" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="edit_nombre_{{ $alumno->id }}" 
                       name="nombre" value="{{ $alumno->nombre }}" maxlength="255" required>
            </div>
            <div class="mb-3">
                <label for="edit_email_{{ $alumno->id }}" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="edit_email_{{ $alumno->id }}" 
                       name="email" value="{{ $alumno->email }}" required>
            </div>
            <div class="mb-3">
                <label for="edit_telefono_{{ $alumno->id }}" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="edit_telefono_{{ $alumno->id }}" 
                       name="telefono" value="{{ $alumno->telefono }}" maxlength="20">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="edit_profesor_{{ $alumno->id }}" 
                       name="es_profesor" value="1" {{ $alumno->es_profesor ? 'checked' : '' }}>
                <label class="form-check-label" for="edit_profesor_{{ $alumno->id }}">
                    ¿Es profesor?
                </label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>

// Toggle curso detalles
// Toggle curso detalles
function toggleCursoDetalles() {
    const detallesDiv = document.getElementById('cursoDetalles');
    const boton = document.getElementById('btnDetallesCurso');
    
    if (detallesDiv) {
        detallesDiv.classList.toggle('hidden');
        const icon = boton.querySelector('i');
        
        if (detallesDiv.classList.contains('hidden')) {
            icon.className = 'fas fa-chevron-down me-2';
            boton.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Detalles del Curso';
        } else {
            icon.className = 'fas fa-chevron-up me-2';
            boton.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Ocultar Detalles';
        }
        

    }
}

// Toggle módulo específico
function toggleModulo(moduloId) {
    const moduloDiv = document.getElementById('modulo-' + moduloId);
    if (moduloDiv) {
        moduloDiv.classList.toggle('hidden');
        
    }
}

// Guardar fecha modificada
async function saveFecha(inputElement) {
    try {
        const payload = {
            detalle_id: inputElement.dataset.detalle || null,
            curso_academico_id: "{{ $cursoAcademico->id }}",
            campo: inputElement.dataset.campo,
            valor: inputElement.value,
            _token: "{{ csrf_token() }}"
        };

        // Determinar tipo de relación
        if (inputElement.dataset.unidad) {
            payload.unidad_formativa_id = inputElement.dataset.unidad;
        } else if (inputElement.dataset.modulo) {
            payload.modulo_id = inputElement.dataset.modulo;
        }

      

        const response = await fetch("{{ route('academia.crearActualizarDetalle') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        
        if (data.success) {
            // Actualizar UI
            inputElement.setAttribute('data-detalle', data.detalle.id);
            
            // Feedback visual
            inputElement.classList.add('actualizado');
            setTimeout(() => inputElement.classList.remove('actualizado'), 2000);
            

        } else {
            throw new Error(data.message || 'Error al guardar');
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Error: " + error.message);
        // Revertir cambio si hay error
        inputElement.value = inputElement.defaultValue;
    }
}




</script>

@endsection