@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <!-- Encabezado con gradiente -->
        <div class="card-header bg-gradient-primary text-white">
            <h2 class="text-center my-2"><i class="fas fa-university me-2"></i>Academias relacionadas a cursos</h2>
        </div>

        <div class="card-body">
            <!-- Buscador Avanzado con diseño moderno -->
            <form id="searchForm" method="GET" action="{{ route('profesor.ver_academias') }}" class="mb-5">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="academia_nombre" class="form-control" id="academiaInput" placeholder="Academia" value="{{ request('academia_nombre') }}">
                            <label for="academiaInput"><i class="fas fa-school me-2"></i>Academia</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="curso_codigo" class="form-control" id="codigoInput" placeholder="Código" value="{{ request('curso_codigo') }}">
                            <label for="codigoInput"><i class="fas fa-hashtag me-2"></i>Código del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="curso_nombre" class="form-control" id="nombreCursoInput" placeholder="Nombre" value="{{ request('curso_nombre') }}">
                            <label for="nombreCursoInput"><i class="fas fa-book me-2"></i>Nombre del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="provincia" class="form-control" id="provinciaInput" placeholder="Provincia" value="{{ request('provincia') }}">
                            <label for="provinciaInput"><i class="fas fa-map-marked-alt me-2"></i>Provincia</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="municipio" class="form-control" id="municipioInput" placeholder="Municipio" value="{{ request('municipio') }}">
                            <label for="municipioInput"><i class="fas fa-map-marker-alt me-2"></i>Municipio</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary me-2 px-4">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                    <button type="button" id="clearBtn" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-broom me-2"></i>Limpiar
                    </button>
                </div>
            </form>

            <!-- Tabla de Academias con diseño moderno -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-school me-2"></i>Academia</th>
                            <th><i class="fas fa-hashtag me-2"></i>Código</th>
                            <th><i class="fas fa-book me-2"></i>Curso</th>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Municipio</th>
                            <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                            <th><i class="fas fa-calendar-start me-2"></i>Inicio</th>
                            <th><i class="fas fa-calendar-end me-2"></i>Fin</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-phone me-2"></i>Contacto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursosAcademicos as $cursoAcademico)
                            <tr>
                                <td>{{ $cursoAcademico->academia_nombre ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso_codigo ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso_nombre ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td>
                                <td><a href="mailto:{{ $cursoAcademico->email ?? '' }}" class="text-primary">{{ $cursoAcademico->email ?? 'N/A' }}</a></td>
                                <td>{{ $cursoAcademico->telefono ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">No se encontraron resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Botón Volver -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('profesor.miscursos') }}" class="btn btn-success px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Mis Cursos
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript mejorado para el buscador -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Limpiar formulario
        document.getElementById('clearBtn').addEventListener('click', function() {
            const form = document.getElementById('searchForm');
            const inputs = form.querySelectorAll('input[type="text"]');
            
            inputs.forEach(input => {
                input.value = '';
            });
            
            // Enviar el formulario limpio
            form.submit();
        });

        // Validación del formulario antes de enviar
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const academiaInput = document.getElementById('academiaInput');
            const cursoNombreInput = document.getElementById('nombreCursoInput');
            
            // Convertir a minúsculas y eliminar espacios en blanco
            if(academiaInput.value) {
                academiaInput.value = academiaInput.value.trim().toLowerCase();
            }
            
            if(cursoNombreInput.value) {
                cursoNombreInput.value = cursoNombreInput.value.trim().toLowerCase();
            }
        });

        // Efecto hover en filas de la tabla
        const tableRows = document.querySelectorAll('.table-hover tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
                this.style.transition = 'background-color 0.3s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Efecto en los botones
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 6px 10px rgba(0, 0, 0, 0.15)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        });
    });
</script>

<!-- Estilos CSS personalizados -->
<style>
    .card {
        border: none;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 0.35rem 0.35rem 0 0 !important;
    }
    
    .form-floating label {
        padding-left: 2.5rem;
    }
    
    .form-floating i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #86b7fe;
    }
    
    .table th {
        white-space: nowrap;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    /* Mejora para inputs de búsqueda */
    #academiaInput, #nombreCursoInput {
        text-transform: lowercase;
    }
</style>

<!-- Incluir Font Awesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection