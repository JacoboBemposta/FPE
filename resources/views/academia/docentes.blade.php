@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary bg-opacity-75 text-white">
            <h2 class="text-center my-3">Docentes Disponibles</h2>
        </div>

        <div class="card-body">
            <!-- Buscador Avanzado con diseño moderno -->
            <form method="GET" action="{{ route('academia.ver_docentes') }}" class="mb-5">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="codigo" class="form-control" id="codigoInput" placeholder="Código" value="{{ request('codigo') }}">
                            <label for="codigoInput">Código del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="nombre" class="form-control" id="nombreInput" placeholder="Nombre" value="{{ request('nombre') }}">
                            <label for="nombreInput">Nombre del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="docente_nombre" class="form-control" id="docenteInput" placeholder="Docente" value="{{ request('docente_nombre') }}">
                            <label for="docenteInput">Nombre del Docente</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="provincia" class="form-control" id="provinciaInput" placeholder="Provincia" value="{{ request('provincia') }}">
                            <label for="provinciaInput">Provincia</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary me-2 px-4">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                    <a href="{{ route('academia.ver_docentes') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-broom me-2"></i>Limpiar
                    </a>
                </div>
            </form>

            <!-- Tabla de Docentes con diseño moderno -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>Código</th>
                            <th><i class="fas fa-book me-2"></i>Curso</th>
                            <th><i class="fas fa-chalkboard-teacher me-2"></i>Docente</th>
                            <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($docentesConCursos as $docente)
                            <tr class="align-middle">
                                <td>{{ $docente->curso_codigo }}</td>
                                <td>{{ $docente->curso_nombre }}</td>
                                <td>{{ $docente->docente_nombre }}</td>
                                <td>{{ $docente->provincia ?? 'N/A' }}</td>
                                <td><a href="mailto:{{ $docente->docente_email }}" class="text-primary">{{ $docente->docente_email }}</a></td>
                                <td>{{ $docente->docente_telefono }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No se encontraron docentes disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('academia.miscursos') }}" class="btn btn-success px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver a mis Cursos
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 0.35rem 0.35rem 0 0 !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>

<!-- Incluir Font Awesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection