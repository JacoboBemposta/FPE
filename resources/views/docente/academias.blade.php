@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4" style="background-color: #007bff; color:white">Academias relacionadas a cursos</h1>

    <!-- Buscador Avanzado -->
    <form method="GET" action="{{ route('profesor.ver_academias') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="academia" class="form-control" placeholder="Buscar por Academia" value="{{ request('academia') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="codigo" class="form-control" placeholder="Buscar por Código del Curso" value="{{ request('codigo') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="nombre_curso" class="form-control" placeholder="Buscar por Nombre del Curso" value="{{ request('nombre_curso') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="provincia" class="form-control" placeholder="Buscar por Provincia" value="{{ request('provincia') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="municipio" class="form-control" placeholder="Buscar por Municipio" value="{{ request('municipio') }}">
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('profesor.ver_academias') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>
    

    <!-- Tabla de resultados -->
    <table class="table table-bordered table-hover">
        <thead class="thead-dark" style="background-color: #0056b3; color: white;">
            <tr>
                <th>Academia</th>
                <th>Código del Curso</th>
                <th>Nombre del Curso</th>
                <th>Familia Profesional</th>
                <th>Municipio</th>
                <th>Provincia</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Email</th>
                <th>Contacto</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursosAcademicos as $cursoAcademico)
                <tr>
                    <td>{{ $cursoAcademico->academia->name ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->codigo ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->nombre ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->curso->familiaProfesional->nombre ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $cursoAcademico->academia->email ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->academia->telefono ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No se encontraron resultados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Botón Volver -->
<div class="mb-3">
    <a href="{{ route('profesor.miscursos') }}" class="btn btn-success">
        ← Volver a Mis Cursos
    </a>
</div>

</div>
@endsection
