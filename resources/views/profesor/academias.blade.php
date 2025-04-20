@extends('layouts.app')

@section('content')
<style>
    .header-academias {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .form-control {
        border-radius: 10px;
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
        background: linear-gradient(135deg, #0056b3, #003d7a);
        color: white;
    }
    .table-modern th, .table-modern td {
        vertical-align: middle;
        padding: 12px 18px;
    }
</style>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="header-academias">
        <h1><i class="fas fa-university me-2"></i>Academias relacionadas a cursos</h1>
    </div>

    <!-- Buscador Avanzado -->
    <form method="GET" action="{{ route('profesor.ver_academias') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="academia" class="form-control" placeholder="Buscar por Academia" value="{{ request('academia') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="codigo" class="form-control" placeholder="Buscar por Código del Curso" value="{{ request('codigo') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="nombre_curso" class="form-control" placeholder="Buscar por Nombre del Curso" value="{{ request('nombre_curso') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="provincia" class="form-control" placeholder="Buscar por Provincia" value="{{ request('provincia') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="municipio" class="form-control" placeholder="Buscar por Municipio" value="{{ request('municipio') }}">
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary btn-custom">
                    <i class="fas fa-search me-1"></i>Buscar
                </button>
                <a href="{{ route('profesor.ver_academias') }}" class="btn btn-secondary btn-custom">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </div>
    </form>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-hover table-modern">
            <thead>
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
    </div>

    <!-- Botón Volver -->
    <div class="mt-4">
        <a href="{{ route('profesor.miscursos') }}" class="btn btn-success btn-custom">
            ← Volver a Mis Cursos
        </a>
    </div>
</div>
@endsection
