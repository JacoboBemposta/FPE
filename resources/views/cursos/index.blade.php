@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detalles del Curso: {{ $cursoAcademico->curso->nombre }}</h1>

    <!-- Botón para ver detalles del curso (Módulos y Unidades Formativas) -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseDetailsModal">
            Ver Detalles del Curso
        </button>
    </div>

    <!-- Tabla de cursos -->
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Código</th>
                <th>Curso</th>
                <th>Familia Profesional</th>
                <th>Horas</th>
                <th>Municipio</th>
                <th>Provincia</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $cursoAcademico->curso->codigo }}</td>
                <td>{{ $cursoAcademico->curso->nombre }}</td>
                <td>{{ $cursoAcademico->curso->FamiliaProfesional->nombre }}</td>
                <td>{{ $cursoAcademico->curso->horas }}</td>
                <td>{{ $cursoAcademico->municipio }}</td>
                <td>{{ $cursoAcademico->provincia }}</td>
                <td>{{ \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('academia.detalleCurso', $cursoAcademico->id) }}" class="btn btn-info btn-sm">Ir al curso</a>
                </td>
            </tr>
        </tbody>
    </table>

<!-- Modal para Detalles del Curso -->
<div class="modal fade" id="courseDetailsModal" tabindex="-1" aria-labelledby="courseDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseDetailsModalLabel">Detalles del Curso: {{ $cursoAcademico->curso->nombre }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Información del curso -->
                <h5>Información del Curso</h5>
                <div class="mb-4">
                    <p><strong>Familia Profesional:</strong> {{ $cursoAcademico->curso->FamiliaProfesional->nombre }}</p>
                    <p><strong>Código:</strong> {{ $cursoAcademico->curso->codigo }}</p>
                    <p><strong>Curso:</strong> {{ $cursoAcademico->curso->nombre }}</p>
                    <p><strong>Horas:</strong> {{ $cursoAcademico->curso->horas }}</p>
                </div>

                <!-- Módulos del Curso -->
                <h5>Módulos del Curso</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre del Módulo</th>
                            <th>Unidades Formativas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cursoAcademico->modulos as $modulo)
                            <tr>
                                @dd($modulo)
                                <td>{{ $modulo->nombre }}</td>
                                <td>
                                    <ul>
                                        @foreach($modulo->unidades as $unidadFormativa) <!-- Cambié de 'unidadesFormativas' a 'unidades' -->
                                            <li>{{ $unidadFormativa->nombre }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


</div>
@endsection
