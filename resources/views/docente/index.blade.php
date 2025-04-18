@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4" style="background-color: #007bff; color:white">Cursos que puedo impartir</h1>

    <!-- Filtros y botones -->
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">
            <a href="{{ route('cursos.index') }}" class="text-white">Buscar Cursos</a>
        </button>
        <a href="{{ route('profesor.ver_academias') }}" class="btn btn-info">Buscar Academias</a>

    </div>

    <!-- Tabla de cursos -->
    <table class="table table-bordered table-hover">
        <thead class="thead-dark" style="background-color: #0056b3; color: white;">
            <tr>
                <th>Código</th>
                <th>Curso</th>
                <th>Familia Profesional</th>
                <th>Horas</th>
                {{-- <th>Municipio</th> --}}
                <th>Provincia</th>
                {{-- <th>Inicio</th> --}}
                {{-- <th>Fin</th> --}}
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
                    {{-- <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td> --}}
                    <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                    {{-- <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td> --}}
                    {{-- <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td> --}}
                    <td>
                        <!-- Botón para editar curso -->
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $cursoAcademico->id }}"
                            data-municipio="{{ $cursoAcademico->municipio }}"
                            data-provincia="{{ $cursoAcademico->provincia }}"
                            data-inicio="{{ $cursoAcademico->inicio }}"
                            data-fin="{{ $cursoAcademico->fin }}">
                            Editar
                        </button>
                        <form action="{{ route('profesor.curso.destroy', $cursoAcademico->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?');">
                                Eliminar
                            </button>
                        </form>
                        
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal para editar curso -->
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
                     {{-- <div class="form-group">
                         <label for="municipio">Municipio</label>
                         <input type="text" class="form-control" id="municipio" name="municipio">
                     </div> --}}
                     <div class="form-group">
                         <label for="provincia">Provincia</label>
                         <input type="text" class="form-control" id="provincia" name="provincia">
                     </div>
                     {{-- <div class="form-group">
                         <label for="inicio">Fecha de Inicio</label>
                         <input type="date" class="form-control" id="inicio" name="inicio">
                     </div>
                     <div class="form-group">
                         <label for="fin">Fecha de Fin</label>
                         <input type="date" class="form-control" id="fin" name="fin">
                     </div> --}}
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                     <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                 </div>
             </form>
         </div>
      </div>
    </div>

    <!-- Scripts: jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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