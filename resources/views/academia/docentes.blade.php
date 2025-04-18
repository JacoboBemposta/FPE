@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4" style="background-color: #007bff; color:white">Docentes Disponibles</h1>

    <!-- Buscador Avanzado -->
    <form method="GET" action="{{ route('academia.ver_docentes') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="codigo" class="form-control" placeholder="Buscar por Código del Curso" value="{{ request('codigo') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="nombre" class="form-control" placeholder="Buscar por Nombre del Curso" value="{{ request('nombre') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="docente_nombre" class="form-control" placeholder="Buscar por Nombre del Docente" value="{{ request('docente_nombre') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="municipio" class="form-control" placeholder="Buscar por Municipio" value="{{ request('municipio') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="provincia" class="form-control" placeholder="Buscar por Provincia" value="{{ request('provincia') }}">
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('academia.ver_docentes') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <!-- Tabla de Docentes y Cursos Disponibles -->
    <table class="table">
        <thead>
            <tr>
                <th>Código del Curso</th>
                <th>Nombre del Curso</th>
                <th>Nombre del Docente</th>
                <th>Municipio</th>
                <th>Provincia</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach($docentesConCursos as $docenteConCursos)
                <tr>
                    <td>{{ $docenteConCursos['curso_codigo'] }}</td>
                    <td>{{ $docenteConCursos['curso_nombre'] }}</td>
                    <td>{{ $docenteConCursos['docente']->name }}</td>
                    <td>{{ $docenteConCursos['curso']->municipio ?: '' }}</td>
                    <td>{{ $docenteConCursos['curso']->provincia ?: '' }}</td>
                    <td>{{ $docenteConCursos['docente']->email }}</td>
                    <td>{{ $docenteConCursos['docente']->telefono }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-success" style="background-color: #28a745; border-color: #28a745;">
        <a href="{{ route('academia.miscursos') }}" class="text-white">Volver a mis Cursos</a>
    </button>
    
</div>
@endsection
