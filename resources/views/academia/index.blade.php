@extends('layouts.app')

@section('content')
<style>
    .header-curso {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 20px 0;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .btn-action {
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s;
        margin: 0 5px;
        border: none;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .table-custom {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        border: none;
        font-size: 0.85rem;
    }
    .table-custom thead {
        background: linear-gradient(135deg, #0056b3 0%, #003d7a 100%);
        color: white;
    }
    .table-custom th {
        border: none;
        padding: 12px 10px;
        font-weight: 600;
        font-size: 0.8rem;
        white-space: nowrap;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 12px 10px;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.03);
    }
    .badge-docente {
        background-color: #17a2b8;
        font-size: 0.75rem;
        padding: 0.4em 0.7em;
    }
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    .action-btn {
        padding: 7px 10px;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.2s;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .btn-edit {
        background-color: #ffc107;
        color: #212529;
    }
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    .btn-view {
        background-color: #17a2b8;
        color: white;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }
    .modal-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }
    .action-tooltip {
        position: relative;
    }
    .action-tooltip .tooltip-text {
        visibility: hidden;
        width: max-content;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 8px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.7rem;
        white-space: nowrap;
    }
    .action-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
    .table-responsive {
        border-radius: 10px;
    }
    .curso-nombre {
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .docente-col {
        min-width: 120px;
    }
    .acciones-col {
        min-width: 140px;
    }
    @media (max-width: 1200px) {
        .curso-nombre {
            max-width: 220px;
        }
    }
    @media (max-width: 992px) {
        .table-custom {
            font-size: 0.8rem;
        }
        .table-custom th,
        .table-custom td {
            padding: 10px 8px;
        }
        .curso-nombre {
            max-width: 200px;
        }
    }
    @media (max-width: 768px) {
        .hidden-mobile {
            display: none;
        }
        .curso-nombre {
            max-width: 150px;
        }
    }
</style>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="header-curso text-center">
        <h1 class="mb-2">Mis Cursos Formativos</h1>
        <p class="mb-0 opacity-75">Gestiona y administra todos tus cursos asignados</p>
    </div>

    <!-- Barra de acciones -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <!-- Grupo izquierdo: Buscar Cursos + Cursos Archivados -->
        <div class="d-flex flex-column flex-sm-row gap-2 mb-3 mb-md-0 align-items-start align-items-sm-center">
            <a href="{{ route('academia.cursos') }}" class="btn btn-primary btn-action">
                <i class="fas fa-search me-2"></i>Buscar Cursos
            </a>
            <a href="{{ route('academia.cursos_archivados') }}" class="btn btn-secondary btn-action">
                <i class="fas fa-archive me-2"></i>Ver Cursos Archivados
            </a>
        </div>
        
        <!-- Grupo derecho: Buscar Docente -->
        <div class="ms-md-auto">
            <a href="{{ route('academia.ver_docentes') }}" class="btn btn-primary btn-action">
                <i class="fas fa-chalkboard-teacher me-2"></i>Buscar Docente
            </a>
        </div>
    </div>

    <!-- Tabla de cursos -->
    @if($misCursos && count($misCursos) > 0)
    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-2"></i>Código</th>
                    <th><i class="fas fa-book me-2"></i>Curso</th>
                    <th><i class="fas fa-map-marker-alt me-2"></i>Municipio</th>
                    <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                    <th><i class="fas fa-calendar-alt me-2"></i>Inicio</th>
                    <th><i class="fas fa-calendar-check me-2"></i>Fin</th>
                    <th><i class="fas fa-chalkboard-teacher me-2"></i>Docente</th>
                    <th class="acciones-col text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($misCursos as $cursoAcademico)
                <tr>
                    <td><strong>{{ $cursoAcademico->curso->codigo ?? 'N/A' }}</strong></td>
                    <td class="curso-nombre" title="{{ $cursoAcademico->curso->nombre ?? 'N/A' }}">
                        {{ $cursoAcademico->curso->nombre ?? 'N/A' }}
                    </td>
                    <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                    <td>
                        {{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td>
                        {{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="docente-col">
                        @php
                            $docente = $cursoAcademico->alumnos->where('es_profesor', 1)->first();
                        @endphp
                        @if($docente)
                            <span class="badge badge-docente">{{ $docente->nombre }}</span>
                        @else
                            <span class="badge bg-warning text-dark">Sin asignar</span>
                        @endif
                    </td>
                    <td class="acciones-col">
                        <div class="action-buttons">
                            <!-- Botón para editar curso - RUTA CORREGIDA -->
                            <div class="action-tooltip">
                                <button class="btn btn-edit action-btn edit-btn"
                                        data-id="{{ $cursoAcademico->id }}"
                                        data-municipio="{{ $cursoAcademico->municipio }}"
                                        data-provincia="{{ $cursoAcademico->provincia }}"
                                        data-inicio="{{ $cursoAcademico->inicio }}"
                                        data-fin="{{ $cursoAcademico->fin }}"
                                        data-route="{{ route('academia.curso_academico.update', $cursoAcademico->id) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <span class="tooltip-text">Editar curso</span>
                            </div>
                            
                            <!-- Botón para ver detalles del curso -->
                            <div class="action-tooltip">
                                <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" class="btn btn-view action-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <span class="tooltip-text">Ver detalles</span>
                            </div>
                            
                            <!-- Botón para archivar curso - MODIFICADO -->
                            <div class="action-tooltip">
                                <form action="{{ route('academia.curso_academico.archive', $cursoAcademico->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning action-btn" onclick="return confirm('¿Estás seguro de que deseas archivar este curso?')">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                                <span class="tooltip-text">Archivar curso</span>
                            </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state bg-light rounded-3 p-5">
        <i class="fas fa-folder-open"></i>
        <h3 class="text-muted">No tienes cursos asignados</h3>
        <p class="text-muted">Comienza asignando cursos desde el buscador de cursos.</p>
        <a href="{{ route('academia.cursos') }}" class="btn btn-primary mt-3">
            <i class="fas fa-search me-2"></i>Buscar Cursos
        </a>
    </div>
    @endif

    <!-- Modal para editar curso -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="editModalLabel">Editar Curso Académico</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <form id="editForm" method="POST" action="">
                 @csrf
                 @method('PUT')
                 <div class="modal-body">
                     <input type="hidden" id="curso_id" name="curso_id">
                     <div class="mb-3">
                         <label for="municipio" class="form-label">Municipio</label>
                         <input type="text" class="form-control" id="municipio" name="municipio">
                     </div>
                     <div class="mb-3">
                         <label for="provincia" class="form-label">Provincia</label>
                         <input type="text" class="form-control" id="provincia" name="provincia">
                     </div>
                     <div class="mb-3">
                         <label for="inicio" class="form-label">Fecha de Inicio</label>
                         <input type="date" class="form-control" id="inicio" name="inicio">
                     </div>
                     <div class="mb-3">
                         <label for="fin" class="form-label">Fecha de Fin</label>
                         <input type="date" class="form-control" id="fin" name="fin">
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                     <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                 </div>
             </form>
         </div>
      </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Evento para abrir el modal de edición de curso
        const editButtons = document.querySelectorAll('.edit-btn');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const cursoAcademicoId = this.getAttribute('data-id');
                const municipio = this.getAttribute('data-municipio');
                const provincia = this.getAttribute('data-provincia');
                const inicio = this.getAttribute('data-inicio');
                const fin = this.getAttribute('data-fin');
                const route = this.getAttribute('data-route');

                // Asignar los valores al formulario del modal de curso
                document.getElementById('curso_id').value = cursoAcademicoId;
                document.getElementById('municipio').value = municipio;
                document.getElementById('provincia').value = provincia;
                document.getElementById('inicio').value = inicio;
                document.getElementById('fin').value = fin;

                // Actualizar el action del formulario con la ruta correcta
                document.getElementById('editForm').setAttribute('action', route);

                // Mostrar el modal de edición de curso usando Bootstrap 5
                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        });
    });
</script>
@endsection