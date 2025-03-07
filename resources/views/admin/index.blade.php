@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Administración de Cursos</h1>
    
    <!-- Botones para añadir Familia Profesional, Curso, Módulo y Unidad Formativa -->
    <div class="mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFamiliaModal">
            Añadir Familia Profesional
        </button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            Añadir Curso
        </button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModuloModal">
            Añadir Módulo
        </button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUnidadModal">
            Añadir Unidad Formativa
        </button>
    </div>

    <!-- Tabla de Familias Profesionales -->
    <table id="familiasTable" class="table table-striped mt-3">
        <thead>
          <tr>
            <th style="width: 2%;">Código</th>
            <th style="width: 78%;">Familia Profesional</th>
            <th style="width: 20%;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($familias_profesionales as $familia)
            <!-- Fila de la familia -->
            <tr>
              <td>{{ $familia->codigo }}</td>
              <td>{{ $familia->nombre }}</td>
              <td class="text-end">
                <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#cursos-{{ $familia->id }}" aria-expanded="false" aria-controls="cursos-{{ $familia->id }}">
                  Ver Cursos
                </button>
                <form action="{{ route('admin.familia.destroy', $familia->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm ms-2">Eliminar</button>
                </form>
              </td>
            </tr>
            <!-- Fila de cursos (se muestra debajo de la columna "Nombre" de la familia) -->
            <tr class="collapse" id="cursos-{{ $familia->id }}">
              <td></td>
              <td colspan="2">
                <table class="table table-bordered mb-0">
                  <thead>
                    <tr>
                        <th style="width: 2%;">Código</th>
                        <th style="width: 78%;">Curso</th>
                        <th style="width: 20%;" class="text-end">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($familia->cursos as $curso)
                      <!-- Fila de curso -->
                      <tr>
                        <td>{{ $curso->codigo }}</td>
                        <td>{{ $curso->nombre }}</td>
                        <td class="text-end">
                          <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#modulos-{{ $curso->id }}" aria-expanded="false" aria-controls="modulos-{{ $curso->id }}">
                            Ver Módulos
                          </button>
                          <form action="{{ route('admin.curso.destroy', $curso->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm ms-2">Eliminar Curso</button>
                          </form>
                        </td>
                      </tr>
                      <!-- Fila de módulos (debajo de la columna "Nombre" del curso) -->
                      <tr class="collapse" id="modulos-{{ $curso->id }}">
                        <td></td>
                        <td colspan="2">
                          <table class="table table-striped mb-0">
                            <thead>
                              <tr>
                                <th style="width: 20%;">Código</th>
                                <th style="width: 60%;">Módulo</th>
                                <th style="width: 20%;" class="text-end">Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($curso->modulos as $modulo)
                                <!-- Fila de módulo -->
                                <tr>
                                  <td>{{ $modulo->codigo }}</td>
                                  <td>{{ $modulo->nombre }}</td>
                                  <td class="text-end">
                                    @if($modulo->unidades->count() > 0)
                                        <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#unidades-{{ $modulo->id }}" aria-expanded="false" aria-controls="unidades-{{ $modulo->id }}">
                                        Ver Unidades Formativas
                                        </button>
                                    @endif
                                    <form action="{{ route('admin.modulo.destroy', $modulo->id) }}" method="POST" style="display:inline;">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-danger btn-sm ms-2">Eliminar Módulo</button>
                                    </form>
                                  </td>
                                </tr>
                                <!-- Fila de unidades formativas (debajo de la columna "Nombre" del módulo) -->
                                <tr>
                                    <td></td>
                                    <td colspan="2">
                                    <div class="collapse" id="unidades-{{ $modulo->id }}">
                                        <div class="card card-body p-0">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                            <tr>
                                                <th style="width: 2%;">Código</th>
                                                <th style="width: 78%;">Unidad Formativa</th>
                                                <th style="width: 20%;" class="text-end">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($modulo->unidades as $unidad)
                                                <tr>
                                                <td>{{ $unidad->codigo }}</td>
                                                <td>{{ $unidad->nombre }}</td>
                                                <td class="text-end">
                                                    <form action="{{ route('admin.unidad.destroy', $unidad->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar </button>
                                                    </form>
                                                </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                    </td>
                                </tr>
  
                              @endforeach
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
</div>    
@endsection

<!-- Modal Familia Profesional -->
<div class="modal fade" id="addFamiliaModal" tabindex="-1" aria-labelledby="addFamiliaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFamiliaModalLabel">Añadir Familia Profesional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addFamiliaForm" method="POST" action="{{ route('admin.familia.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código de la familia profesional</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Familia Profesional</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Curso  -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Añadir Curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCourseForm" method="POST" action="{{ route('admin.curso.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="familias_profesionales_id" class="form-label">Familia Profesional</label>
                        <select class="form-select" id="familias_profesionales_id" name="familias_profesionales_id" required>
                            <option value="">Selecciona una Familia Profesional</option>
                            @foreach($familias_profesionales as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código del Curso</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Curso</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="horas" class="form-label">Numero de horas</label>
                        <input type="number" class="form-control" id="horas" name="horas" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modulo  -->
<div class="modal fade" id="addModuloModal" tabindex="-1" aria-labelledby="addModuloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModuloModalLabel">Añadir Módulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addModuloForm" method="POST" action="{{ route('admin.modulo.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="familia_id_modulo" class="form-label">Familia Profesional</label>
                        <select class="form-select" id="familia_id_modulo" name="familia_id_modulo" required>
                            <option value="">Selecciona una Familia Profesional</option>
                            @foreach($familias_profesionales as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="curso_id" class="form-label">Curso</label>
                        <select class="form-select" id="curso_id" name="curso_id" required>
                            <option value="">Selecciona un Curso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código del Módulo</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Módulo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="horas" class="form-label">Horas del Módulo</label>
                        <input type="text" class="form-control" id="horas" name="horas" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Unidad Formativa  -->
<div class="modal fade" id="addUnidadModal" tabindex="-1" aria-labelledby="addUnidadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUnidadModalLabel">Añadir Unidad Formativa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUnidadForm" method="POST" action="{{ route('admin.unidad.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="familia_id_unidad" class="form-label">Familia Profesional</label>
                        <select class="form-select" id="familia_id_unidad" name="familia_id_unidad" required>
                            <option value="">Selecciona una Familia Profesional</option>
                            @foreach($familias_profesionales as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="curso_id_unidad" class="form-label">Curso</label>
                        <select class="form-select" id="curso_id_unidad" name="curso_id_unidad" required>
                            <option value="">Selecciona un Curso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modulo_id" class="form-label">Módulo</label>
                        <select class="form-select" id="modulo_id" name="modulo_id" required>
                            <option value="">Selecciona un Módulo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código de la Unidad</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Unidad</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="horas" class="form-label">Horas de la Unidad</label>
                        <input type="text" class="form-control" id="horas" name="horas" required>
                    </div>
                    <button type="submit" class="btn btn-info">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Asegúrate de que los datos estén disponibles en el script de la siguiente manera -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // --- Para el modal de añadir Curso (si fuera necesario) ---
        $('#familia_id').on('change', function() {
            let familiaId = $(this).val();
            let cursoSelect = $('#curso_id');
            cursoSelect.html('<option value="">Selecciona un Curso</option>');
            if (familiaId) {
                @foreach($familias_profesionales as $familia)
                    if (familiaId == {{ $familia->id }}) {
                        @foreach($familia->cursos as $curso)
                            cursoSelect.append(`<option value="{{ $curso->id }}">{{ $curso->nombre }}</option>`);
                        @endforeach
                    }
                @endforeach
            }
        });
    
        // --- Para el modal de añadir Módulo (si fuera necesario) ---
        $('#familia_id_modulo').on('change', function() {
            let familiaId = $(this).val();
            let cursoSelect = $('#curso_id');
            cursoSelect.html('<option value="">Selecciona un Curso</option>');
            if (familiaId) {
                @foreach($familias_profesionales as $familia)
                    if (familiaId == {{ $familia->id }}) {
                        @foreach($familia->cursos as $curso)
                            cursoSelect.append(`<option value="{{ $curso->id }}">{{ $curso->nombre }}</option>`);
                        @endforeach
                    }
                @endforeach
            }
        });
    

   // --- Para el modal de añadir Unidad Formativa ---
   $('#familia_id_unidad').on('change', function() {
        let familiaId = $(this).val();
        let cursoSelect = $('#curso_id_unidad');
        cursoSelect.html('<option value="">Selecciona un Curso</option>');
        // También limpiamos el select de módulo
        $('#modulo_id').html('<option value="">Selecciona un Módulo</option>');
        
        if (familiaId) {
            @foreach($familias_profesionales as $familia)
                if (familiaId == {{ $familia->id }}) {
                    @foreach($familia->cursos as $curso)
                        cursoSelect.append(`<option value="{{ $curso->id }}">{{ $curso->nombre }}</option>`);
                    @endforeach
                }
            @endforeach
        }
    });

    $('#curso_id_unidad').on('change', function() {
        let cursoId = $(this).val();
        let moduloSelect = $('#modulo_id');
        moduloSelect.html('<option value="">Selecciona un Módulo</option>');
        if (cursoId) {
            @foreach($familias_profesionales as $familia)
                @foreach($familia->cursos as $curso)
                    if (cursoId == {{ $curso->id }}) {
                        @foreach($curso->modulos as $modulo)
                            moduloSelect.append(`<option value="{{ $modulo->id }}">{{ $modulo->nombre }}</option>`);
                        @endforeach
                    }
                @endforeach
            @endforeach
        }
    });
    
        $('#curso_unidad').on('change', function() {
            let cursoId = $(this).val();
            let moduloSelect = $('#modulo_unidad');
            moduloSelect.html('<option value="">Selecciona un Módulo</option>');
            if (cursoId) {
                @foreach($familias_profesionales as $familia)
                    @foreach($familia->cursos as $curso)
                        if (cursoId == {{ $curso->id }}) {
                            @foreach($curso->modulos as $modulo)
                                moduloSelect.append(`<option value="{{ $modulo->id }}">{{ $modulo->nombre }}</option>`);
                            @endforeach
                        }
                    @endforeach
                @endforeach
            }
        });
    
        // Confirmación antes de eliminar
        $('.delete-form').on('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
                e.preventDefault();
            }
        });
    });
    </script>
    