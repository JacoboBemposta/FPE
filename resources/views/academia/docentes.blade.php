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
                    
                    <!-- Selector de elementos por página -->
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select name="per_page" class="form-control" id="perPageSelect" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 por página</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 por página</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 por página</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 por página</option>
                            </select>
                            <label for="perPageSelect">Resultados por página</label>
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

            <!-- Información de paginación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted">
                    Mostrando 
                    <strong>{{ $docentesConCursos->firstItem() ?? 0 }}-{{ $docentesConCursos->lastItem() ?? 0 }}</strong> 
                    de <strong>{{ $docentesConCursos->total() ?? 0 }}</strong> resultados
                </div>
            </div>

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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No se encontraron docentes disponibles</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($docentesConCursos->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Página <strong>{{ $docentesConCursos->currentPage() }}</strong> de <strong>{{ $docentesConCursos->lastPage() }}</strong>
                </div>
                
                <nav aria-label="Paginación de docentes">
                    <ul class="pagination justify-content-center mb-0">
                        <!-- Enlace anterior -->
                        <li class="page-item {{ $docentesConCursos->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $docentesConCursos->previousPageUrl() }}" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Números de página -->
                        @php
                            $current = $docentesConCursos->currentPage();
                            $last = $docentesConCursos->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $docentesConCursos->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $docentesConCursos->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $docentesConCursos->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        <!-- Enlace siguiente -->
                        <li class="page-item {{ !$docentesConCursos->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $docentesConCursos->nextPageUrl() }}" aria-label="Siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Selector de página rápida -->
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2">Ir a:</span>
                    <form method="GET" action="{{ route('academia.ver_docentes') }}" class="d-flex">
                        <input type="hidden" name="codigo" value="{{ request('codigo') }}">
                        <input type="hidden" name="nombre" value="{{ request('nombre') }}">
                        <input type="hidden" name="docente_nombre" value="{{ request('docente_nombre') }}">
                        <input type="hidden" name="provincia" value="{{ request('provincia') }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        
                        <input type="number" 
                               name="page" 
                               class="form-control form-control-sm" 
                               style="width: 80px;" 
                               min="1" 
                               max="{{ $docentesConCursos->lastPage() }}"
                               value="{{ $docentesConCursos->currentPage() }}">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endif
            
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
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .page-link {
        color: #0d6efd;
        border: 1px solid #dee2e6;
    }
    
    .page-link:hover {
        color: #0a58ca;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .pagination {
        margin-bottom: 0;
    }
</style>

@endsection