@extends('layouts.app')

@section('content')
<style>
    .header-curso {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 15px 0;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-action {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s;
        margin: 0 5px;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table-custom {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .table-custom thead {
        background: linear-gradient(135deg, #0056b3 0%, #003d7a 100%);
        color: white;
    }
    .table-custom th {
        border: none;
        padding: 12px 15px;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 12px 15px;
    }
    .badge-docente {
        background-color: #17a2b8;
        font-size: 0.8rem;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .action-btn {
        padding: 5px 12px;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .action-btn:hover {
        filter: brightness(90%);
    }
    .btn-edit {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    .btn-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
        height: 100%;
    }
    .btn-view {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }
</style>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="header-curso text-center">
        <h1>Mis Cursos Formativos</h1>
    </div>

    <!-- Barra de acciones -->
    <div class="d-flex justify-content-between mb-4">
        <div>
            <a href="{{ route('academia.cursos') }}" class="btn btn-primary btn-action">
                <i class="fas fa-search mr-2"></i>Buscar Cursos
            </a>
            <a href="{{ route('academia.ver_docentes') }}" class="btn btn-primary btn-action">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Buscar Docente
            </a>
        </div>
    </div>

    <!-- Tabla de cursos -->
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
                    <td>{{ $cursoAcademico->curso->codigo ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->nombre ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->familiaProfesional->nombre ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->horas ?? 'N/A' }}</td>
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
                                Editar
                            </button>
                            
                            <!-- Botón para eliminar curso -->
                            <form action="{{ route('academia.curso_academico.destroy', $cursoAcademico->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete action-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?')">
                                    Eliminar
                                </button>
                            </form>
                            
                            <!-- Botón para ver detalles del curso -->
                            <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" class="btn btn-view action-btn">
                                Ir al curso
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para editar curso (se mantiene igual) -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="editModalLabel">Editar Curso Académico</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <form id="editForm" method="POST" action="">
                 @csrf
                 @method('PUT')
                 <div class="modal-body">
                     <input type="hidden" id="curso_id" name="curso_id" value="">
                     <div class="form-group">
                         <label for="municipio">Municipio</label>
                         <input type="text" class="form-control" id="municipio" name="municipio">
                     </div>
                     <div class="form-group">
                         <label for="provincia">Provincia</label>
                         <input type="text" class="form-control" id="provincia" name="provincia">
                     </div>
                     <div class="form-group">
                         <label for="inicio">Fecha de Inicio</label>
                         <input type="date" class="form-control" id="inicio" name="inicio">
                     </div>
                     <div class="form-group">
                         <label for="fin">Fecha de Fin</label>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        var misCursos = @json($misCursos);
        $(document).ready(function() {
            // Evento para abrir el modal de edición de curso
            $('.edit-btn').on('click', function() {
                let cursoAcademicoId = $(this).data('id');
                let municipio = $(this).data('municipio');
                let provincia = $(this).data('provincia');
                let inicio = $(this).data('inicio');
                let fin = $(this).data('fin');

                // Asignar los valores al formulario del modal de curso
                $('#curso_id').val(cursoAcademicoId);
                $('#municipio').val(municipio);
                $('#provincia').val(provincia);
                $('#inicio').val(inicio);
                $('#fin').val(fin);

                // Actualizar el action del formulario
                $('#editForm').attr('action', '/academia/curso/' + cursoAcademicoId + '/editar');

                // Mostrar el modal de edición de curso
                $('#editModal').modal('show');
            });
        });
    </script>
</div>
@endsection