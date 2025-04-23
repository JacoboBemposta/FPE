@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Administración de Cursos</h1>

    <!-- Botones para crear nuevos elementos -->
    <div class="mb-4">
        <button class="btn btn-primary" data-toggle="modal" data-target="#crearFamiliaModal">
            <i class="fas fa-plus"></i> Crear Familia Profesional
        </button>
        <button class="btn btn-success" data-toggle="modal" data-target="#crearCursoModal">
            <i class="fas fa-plus"></i> Crear Curso
        </button>
    </div>

    <!-- Listado jerárquico de Familias Profesionales - ESTRUCTURA CORREGIDA -->
    <div class="accordion" id="familiasAccordion">
        @foreach($familiasProfesionales as $familia)
        <div class="card">
            <div class="card-header" id="familiaHeading{{ $familia->id }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center collapsed" 
                            type="button" 
                            data-toggle="collapse" 
                            data-target="#familiaCollapse{{ $familia->id }}" 
                            aria-expanded="false" 
                            aria-controls="familiaCollapse{{ $familia->id }}">
                        <span>
                            <strong>{{ $familia->codigo }}</strong> - {{ $familia->nombre }}
                        </span>
                        <span class="badge badge-primary">{{ $familia->cursos->count() }} cursos</span>
                    </button>
                </h2>
            </div>









            <div id="familiaCollapse{{ $familia->id }}" 
                 class="collapse" 
                 aria-labelledby="familiaHeading{{ $familia->id }}" 
                 data-parent="#familiasAccordion">
                <div class="card-body p-0">
                    @foreach($familia->cursos as $curso)
                    <div class="accordion" id="cursosAccordion{{ $familia->id }}">
                        <div class="card mb-2">
                            <div class="card-header" id="cursoHeading{{ $curso->id }}">
                                <h3 class="mb-0">
                                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center collapsed" 
                                            type="button" 
                                            data-toggle="collapse" 
                                            data-target="#cursoCollapse{{ $curso->id }}" 
                                            aria-expanded="false" 
                                            aria-controls="cursoCollapse{{ $curso->id }}"
                                            data-parent="#cursosAccordion{{ $familia->id }}">
                                        <span>
                                            <strong>{{ $curso->codigo }}</strong> - {{ $curso->nombre }} ({{ $curso->horas }}h)
                                        </span>
                                        <div>
                                            <button class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#editarCursoModal{{ $curso->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </button>
                                </h3>
                            </div>










                            <!-- Modal Editar Curso - Debe estar dentro del bucle de cursos -->
                            <div class="modal fade" id="editarCursoModal{{ $curso->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.cursos.update', $curso->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Curso</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Campo de selección de Familia Profesional -->
                                                <div class="form-group">
                                                    <label>Familia Profesional</label>
                                                    <select name="familia_profesional_id" class="form-control" required>
                                                        @foreach($familiasProfesionales as $familia)
                                                            <option value="{{ $familia->id }}" 
                                                                {{ $curso->familia_profesional_id == $familia->id ? 'selected' : '' }}>
                                                                {{ $familia->codigo }} - {{ $familia->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                            
                                                <!-- Código del Curso -->
                                                <div class="form-group">
                                                    <label>Código</label>
                                                    <input type="text" name="codigo" class="form-control" value="{{ $curso->codigo }}" required>
                                                </div>
                            
                                                <!-- Nombre del Curso -->
                                                <div class="form-group">
                                                    <label>Nombre</label>
                                                    <input type="text" name="nombre" class="form-control" value="{{ $curso->nombre }}" required>
                                                </div>
                            
                                                <!-- Horas del Curso -->
                                                <div class="form-group">
                                                    <label>Horas totales</label>
                                                    <input type="number" name="horas" class="form-control" value="{{ $curso->horas }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>






                            <div id="cursoCollapse{{ $curso->id }}" class="collapse" aria-labelledby="cursoHeading{{ $curso->id }}" data-parent="#cursosAccordion{{ $familia->id }}">
                                <div class="card-body">
                                    <!-- Mostrar módulos y sus unidades -->
                                    <div class="mb-4">
                                        <h5>Módulos del Curso</h5>
                                        @if($curso->modulos && $curso->modulos->count())
                                            @foreach($curso->modulos as $modulo)
                                            <div class="card mb-3">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $modulo->codigo }}</strong> - {{ $modulo->nombre }} ({{ $modulo->horas }}h)
                                                </div>
                                                <div>
                                                    <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#agregarUnidadModal{{ $modulo->id }}">
                                                        <i class="fas fa-plus"></i> Añadir Unidad
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#unidadesModulo{{ $modulo->id }}" aria-expanded="false">
                                                        <i class="fas fa-list"></i> Ver Unidades
                                                    </button>
                                                    <form action="{{ route('admin.cursos.modulos.destroy', ['curso' => $curso->id, 'modulo' => $modulo->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este módulo del curso?');">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <!-- Sección de unidades formativas con collapse -->
                                            <div class="collapse" id="unidadesModulo{{ $modulo->id }}">
                                                <div class="card-body">
                                                    @if($modulo->unidades->count())
                                                        <div class="list-group">
                                                            @foreach($modulo->unidades->sortBy([['codigo'], ['nombre']]) as $unidad)
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <strong>{{ $unidad->codigo }}</strong> - {{ $unidad->nombre }}
                                                                        <span class="badge badge-info ml-2">{{ $unidad->horas }}h</span>
                                                                    </div>
                                                                    <div>
                                                                        <form action="{{ route('admin.unidades.destroy', $unidad->id) }}" method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta unidad formativa?');">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-muted">Este módulo no tiene unidades formativas.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                        <p class="text-muted">Este curso no tiene módulos.</p>
                                    @endif
                                    </div>

                                    <!-- Botón para añadir módulo -->
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#agregarModuloModal{{ $curso->id }}">
                                        <i class="fas fa-plus"></i> Añadir Módulo
                                    </button>
                                </div>
                            </div>

                           <!-- Modal simplificado para agregar módulo -->
                            <div class="modal fade" id="agregarModuloModal{{ $curso->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.cursos.modulos.store', $curso->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">Agregar Nuevo Módulo al Curso</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Código del Módulo</label>
                                                    <input type="text" name="codigo" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nombre del Módulo</label>
                                                    <input type="text" name="nombre" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Horas del Módulo</label>
                                                    <input type="number" name="horas" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar Módulo</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

<!-- Modal para agregar unidad formativa -->
@foreach($curso->modulos as $modulo)
<div class="modal fade" id="agregarUnidadModal{{ $modulo->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.unidades.store') }}" method="POST">
                @csrf
                <!-- Enviar múltiples módulos como array -->
                <input type="hidden" name="modulo_id" value="{{ $modulo->id }}"> 
                
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Unidad a {{ $modulo->nombre }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" name="codigo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Horas</label>
                        <input type="number" name="horas" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Unidad</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<!-- Modal Crear Familia Profesional -->
<div class="modal fade" id="crearFamiliaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.familias-profesionales.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Familia Profesional</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" name="codigo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Crear Curso -->
<div class="modal fade" id="crearCursoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Curso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Familia Profesional</label>
                        <select name="familia_profesional_id" class="form-control" required>
                            @foreach($familiasProfesionales as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->codigo }} - {{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" name="codigo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Horas totales</label>
                        <input type="number" name="horas" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>




@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de eliminación de módulos (mantener esta funcionalidad)
        document.addEventListener('click', function(event) {
            if (event.target.closest('.eliminar-modulo')) {
                event.preventDefault();
                const button = event.target.closest('.eliminar-modulo');
                const moduloId = button.getAttribute('data-modulo-id');
                const cursoId = button.getAttribute('data-curso-id');
                
                confirmarEliminarModulo(moduloId, cursoId);
            }
        });

        function confirmarEliminarModulo(moduloId, cursoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la relación entre el módulo y el curso",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarModulo(moduloId, cursoId);
                }
            });
        }

        function eliminarModulo(moduloId, cursoId) {
            fetch(`/admin/cursos/${cursoId}/modulos/${moduloId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        '¡Eliminado!',
                        'La relación ha sido eliminada.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al eliminar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error',
                    error.message || 'Ocurrió un error al eliminar',
                    'error'
                );
            });
        }

     // Manejo de eliminación de unidades
     document.addEventListener('click', function(e) {
            if (e.target.closest('.eliminar-unidad')) {
                e.preventDefault();
                const form = e.target.closest('form');
                confirmarEliminarUnidad(form);
            }
        });

        function confirmarEliminarUnidad(form) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará permanentemente la unidad formativa",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

    });
</script>
@endpush