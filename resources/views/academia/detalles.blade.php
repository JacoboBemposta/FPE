@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detalles del Curso: {{ $cursoAcademico->curso->nombre }}</h1>

    <!-- Información del curso -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Información del Curso</h5>
            <p class="card-text">
                <strong>Familia Profesional:</strong> {{ $cursoAcademico->curso->FamiliaProfesional->nombre }}<br>
                <strong>Código:</strong> {{ $cursoAcademico->curso->codigo }}<br>
                <strong>Curso:</strong> {{ $cursoAcademico->curso->nombre }}<br>
                <strong>Horas:</strong> {{ $cursoAcademico->curso->horas }}<br>
            </p>
        </div>
    </div>

    <!-- Módulos y Unidades Formativas del Curso -->
    <h3>Módulos del Curso</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre del Módulo</th>
                <th>Unidades Formativas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursoAcademico->curso->modulos as $modulo)
            <tr>
                <td>{{ $modulo->nombre }}</td>
                <td>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre de la Unidad</th>
                                <th>Horas</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modulo->unidades as $unidadFormativa)
                            <tr>
                                <td>{{ $unidadFormativa->nombre }}</td>
                                <td>{{ $unidadFormativa->horas }}</td>
                                <!-- Aquí agregarás los nuevos campos -->
                                <td>{{ $unidadFormativa->fecha_inicio ? \Carbon\Carbon::parse($unidadFormativa->fecha_inicio)->format('d/m/Y') : 'No definida' }}</td>
                                <td>{{ $unidadFormativa->fecha_fin ? \Carbon\Carbon::parse($unidadFormativa->fecha_fin)->format('d/m/Y') : 'No definida' }}</td>
                                <td>{{ $unidadFormativa->calificacion ?? 'No definida' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button class="btn btn-success">
        <a href="{{ route('academia.miscursos') }}" class="text-white">Volver a mis Cursos</a>
    </button>
</div>
@endsection
