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
    }
    .table-custom thead {
        background: linear-gradient(135deg, #0056b3 0%, #003d7a 100%);
        color: white;
    }
    .table-custom th {
        border: none;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.03);
    }
    .badge-docente {
        background-color: #17a2b8;
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .action-btn {
        padding: 6px 14px;
        font-size: 0.85rem;
        border-radius: 6px;
        transition: all 0.2s;
        border: none;
        font-weight: 500;
    }
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
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
</style>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="header-curso text-center">
        <h1 class="mb-2">Mis Cursos Formativos</h1>
        <p class="mb-0 opacity-75">Gestiona y administra todos tus cursos asignados</p>
    </div>

    <!-- Barra de acciones -->
    <div class="d-flex justify-content-between mb-4">
        <div>
            <a href="{{ route('academia.cursos') }}" class="btn btn-primary btn-action">
                <i class="fas fa-search me-2"></i>Buscar Cursos
            </a>
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
                    <th>Código</th>
                    <th>Curso</th>
                    <th>Familia Profesional</th>
                    <th>Horas</th>
                    <th>Municipio</th>
                    <th>Provincia</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Docente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($misCursos as $cursoAcademico)
                <tr>
                    <td><strong>{{ $cursoAcademico->curso->codigo ?? 'N/A' }}</strong></td>
                    <td>{{ $cursoAcademico->curso->nombre ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->familiaProfesional->nombre ?? 'N/A' }}</td>
                    <td><span class="badge bg-secondary">{{ $cursoAcademico->curso->horas ?? 'N/A' }}h</span></td>
                    <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        @php
                            $docente = $cursoAcademico->alumnos->where('es_profesor', 1)->first();
                        @endphp
                        @if($docente)
                            <span class="badge badge-docente">{{ $docente->nombre }}</span>
                        @else
                            <span class="badge bg-warning text-dark">Sin asignar</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <!-- Botón para editar curso -->
                            <button class="btn btn-edit action-btn edit-btn"
                                    data-id="{{ $cursoAcademico->id }}"
                                    data-municipio="{{ $cursoAcademico->municipio }}"
                                    data-provincia="{{ $cursoAcademico->provincia }}"
                                    data-inicio="{{ $cursoAcademico->inicio }}"
                                    data-fin="{{ $cursoAcademico->fin }}">
                                <i class="fas fa-edit me-1"></i>Editar
                            </button>
                            
                            <!-- Botón para eliminar curso -->
                            <form action="{{ route('academia.curso_academico.destroy', $cursoAcademico->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete action-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?')">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </form>
                            
                            <!-- Botón para ver detalles del curso -->
                            <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" class="btn btn-view action-btn">
                                <i class="fas fa-eye me-1"></i>Ver Curso
                            </a>
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

                // Asignar los valores al formulario del modal de curso
                document.getElementById('curso_id').value = cursoAcademicoId;
                document.getElementById('municipio').value = municipio;
                document.getElementById('provincia').value = provincia;
                document.getElementById('inicio').value = inicio;
                document.getElementById('fin').value = fin;

                // Actualizar el action del formulario
                document.getElementById('editForm').setAttribute('action', '/academia/curso/' + cursoAcademicoId + '/editar');

                // Mostrar el modal de edición de curso usando Bootstrap 5
                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        });
    });
</script>
@endsection