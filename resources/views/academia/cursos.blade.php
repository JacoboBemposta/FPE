
@extends('layouts.app')

@section('content')
<!-- No utilizada -->
<div class="container">
    <h1>Buscar Cursos</h1>
    
    <form method="GET" action="{{ route('academia.cursos') }}" class="mb-4">
        <div class="row">
            <div class="col-md-5">
                <select name="familia_id" class="form-control">
                    <option value="">Seleccione una Familia Profesional</option>
                    @foreach($familias_profesionales as $familia)
                        <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="curso_id" class="form-control">
                    <option value="">Seleccione un Curso</option>
                    @foreach($familias_profesionales as $familia)
                        @foreach($familia->cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Familia Profesional</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($familias_profesionales as $familia)
            <tr>
                <td>{{ $familia->nombre }}</td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#cursos-{{ $familia->id }}">
                        Ver Cursos
                    </button>
                </td>
            </tr>
            <tr class="collapse" id="cursos-{{ $familia->id }}">
                <td colspan="2">
                    <table class="table">
                        <tbody>
                            @foreach($familia->cursos as $curso)
                            <tr>
                                <td>{{ $curso->nombre }}</td>
                                <td>
                                    <!-- Botón para abrir el modal de asignación del curso -->
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#asignarCursoModal{{ $curso->id }}">
                                        Asignar Curso
                                    </button>
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

<!-- Modal para asignar curso -->
@foreach($familias_profesionales as $familia)
    @foreach($familia->cursos as $curso)
        <div class="modal fade" id="asignarCursoModal{{ $curso->id }}" tabindex="-1" aria-labelledby="asignarCursoModalLabel{{ $curso->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="asignarCursoModalLabel{{ $curso->id }}">Asignar Curso: {{ $curso->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('academia.asignar_curso', $curso->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <input type="text" name="municipio" id="municipio" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="provincia">Provincia</label>
                                <input type="text" name="provincia" id="provincia" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="inicio">Fecha de inicio</label>
                                <input type="date" name="inicio" id="inicio" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="fin">Fecha de fin</label>
                                <input type="date" name="fin" id="fin" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">Asignar Curso</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

@endsection
