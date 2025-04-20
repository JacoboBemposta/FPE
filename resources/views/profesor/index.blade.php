@extends('layouts.app')

@section('content')
<style>
    .header-curso {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 20px 0;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .btn-custom {
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    }
    .table-modern {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    .table-modern thead {
        background: linear-gradient(135deg, #0056b3 0%, #003d7a 100%);
        color: white;
    }
    .table-modern th, .table-modern td {
        vertical-align: middle;
        padding: 14px 20px;
    }
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    .action-btn {
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 6px 14px;
    }
    .btn-edit {
        background-color: #ffc107;
        color: #212529;
        border: none;
    }
    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: none;
    }
</style>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="header-curso">
        <h1><i class="fas fa-book-open me-2"></i>Cursos que puedo impartir</h1>
    </div>

    <!-- Filtros y acciones -->
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('profesor.cursos') }}" class="btn btn-primary btn-custom">
            <i class="fas fa-search me-2"></i>Buscar Cursos
        </a>
        <a href="{{ route('profesor.ver_academias') }}" class="btn btn-info btn-custom">
            <i class="fas fa-school me-2"></i>Buscar Academias
        </a>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-hover table-modern">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Curso</th>
                    <th>Familia Profesional</th>
                    <th>Horas</th>
                    <th>Provincia</th>
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
                        <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-edit action-btn edit-btn"
                                    data-id="{{ $cursoAcademico->id }}"
                                    data-municipio="{{ $cursoAcademico->municipio }}"
                                    data-provincia="{{ $cursoAcademico->provincia }}"
                                    data-inicio="{{ $cursoAcademico->inicio }}"
                                    data-fin="{{ $cursoAcademico->fin }}">
                                    Editar
                                </button>
                                <form action="{{ route('profesor.curso.destroy', $cursoAcademico->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete action-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?');">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="editModalLabel">Editar Curso</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <form id="editForm" method="POST" action="">
                 @csrf
                 @method('PUT')
                 <div class="modal-body">
                     <input type="hidden" id="curso_id" name="curso_id">
                     <div class="mb-3">
                         <label for="provincia" class="form-label">Provincia</label>
                         <input type="text" class="form-control" id="provincia" name="provincia">
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
    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                let cursoAcademicoId = $(this).data('id');
                let provincia = $(this).data('provincia');
                let inicio = $(this).data('inicio');
                let fin = $(this).data('fin');

                $('#curso_id').val(cursoAcademicoId);
                $('#provincia').val(provincia);
                $('#editForm').attr('action', '/academia/curso/' + cursoAcademicoId + '/editar');
                $('#editModal').modal('show');
            });
        });
    </script>
</div>
@endsection
