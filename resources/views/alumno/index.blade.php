@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <!-- Encabezado con gradiente y botón PDF -->
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="my-2">
                <i class="fas fa-graduation-cap me-2"></i>Cursos Académicos Disponibles
            </h2>
            <a href="{{ asset('pdfs/certificados.html') }}" 
               target="_blank" 
               class="btn btn-light btn-lg">
                <i class="fas fa-file-pdf me-2 text-danger"></i>Ver todos los certificados profesionales
            </a>
        </div>

        <div class="card-body">
            <!-- Buscador Avanzado con diseño moderno -->
            <form id="searchForm" method="GET" action="{{ route('alumno.index') }}" class="mb-5">
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
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select name="familia" class="form-control" id="familiaSelect">
                                <option value="">Todas las familias</option>
                                @foreach($familias as $familia)
                                    <option value="{{ $familia->id }}" {{ request('familia') == $familia->id ? 'selected' : '' }}>
                                        {{ $familia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="familiaSelect"><i class="fas fa-layer-group me-2"></i>Familia Profesional</label>
                        </div>
                    </div>
                    <!-- Selector de elementos por página -->
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select name="per_page" class="form-control" id="perPageSelect">
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
                    <button type="button" id="clearBtn" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-broom me-2"></i>Limpiar
                    </button>
                </div>
            </form>

            <!-- Información de paginación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted">
                    Mostrando 
                    <strong>{{ $cursosAcademicos->firstItem() ?? 0 }}-{{ $cursosAcademicos->lastItem() ?? 0 }}</strong> 
                    de <strong>{{ $cursosAcademicos->total() ?? 0 }}</strong> resultados
                </div>
            </div>

            <!-- Tabla de Cursos Académicos con diseño moderno -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-school me-2"></i>Academia</th>
                            <th><i class="fas fa-hashtag me-2"></i>Código</th>
                            <th><i class="fas fa-book me-2"></i>Curso</th>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Municipio</th>
                            <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                            <th><i class="fas fa-calendar-alt me-2"></i>Inicio</th>
                            <th><i class="fas fa-calendar-check me-2"></i>Fin</th>
                            <th><i class="fas fa-layer-group me-2"></i>Familia</th>
                            <th><i class="fas fa-envelope me-2"></i>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursosAcademicos as $cursoAcademico) 
                            <tr>
                                <td>{{ $cursoAcademico->academia->name ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso->codigo ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso->nombre ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso->familiaProfesional->nombre ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm contact-btn" 
                                            data-academia-id="{{ $cursoAcademico->academia->id ?? '' }}"
                                            data-academia-nombre="{{ $cursoAcademico->academia->name ?? 'N/A' }}"
                                            data-curso-nombre="{{ $cursoAcademico->curso->nombre ?? 'N/A' }}"
                                            data-municipio="{{ $cursoAcademico->municipio ?? 'N/A' }}"
                                            data-inicio="{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}"
                                            data-fin="{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}">
                                        <i class="fas fa-envelope me-1"></i> Contactar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-graduation-cap fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No se encontraron resultados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($cursosAcademicos->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Página <strong>{{ $cursosAcademicos->currentPage() }}</strong> de <strong>{{ $cursosAcademicos->lastPage() }}</strong>
                </div>
                
                <nav aria-label="Paginación de cursos">
                    <ul class="pagination justify-content-center mb-0">
                        <!-- Enlace anterior -->
                        <li class="page-item {{ $cursosAcademicos->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $cursosAcademicos->previousPageUrl() }}" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Números de página -->
                        @php
                            $current = $cursosAcademicos->currentPage();
                            $last = $cursosAcademicos->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $cursosAcademicos->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $cursosAcademicos->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $cursosAcademicos->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        <!-- Enlace siguiente -->
                        <li class="page-item {{ !$cursosAcademicos->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $cursosAcademicos->nextPageUrl() }}" aria-label="Siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Selector de página rápida -->
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2">Ir a:</span>
                    <form method="GET" action="{{ route('alumno.index') }}" class="d-flex">
                        @foreach(request()->except('page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="number" 
                               name="page" 
                               class="form-control form-control-sm" 
                               style="width: 80px;" 
                               min="1" 
                               max="{{ $cursosAcademicos->lastPage() }}"
                               value="{{ $cursosAcademicos->currentPage() }}">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Botón Volver -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('home') }}" class="btn btn-success px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Contacto para Alumno -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-envelope me-2"></i>Contactar Academia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contactForm" method="POST" action="{{ route('alumno.academia.enviar_email') }}">
                @csrf
                <input type="hidden" name="academia_id" id="academia_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="email" class="form-control" id="recipientEmail" name="email" value="" required>
                        <small class="form-text text-muted">Puedes editar este campo si es necesario</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label fw-bold">Asunto:</label>
                        <input type="text" class="form-control" id="emailSubject" name="asunto" value="" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label fw-bold">Mensaje:</label>
                        <textarea class="form-control" id="emailMessage" name="mensaje" rows="8" required></textarea>
                    </div>

                    <!-- Información del curso (solo lectura) -->
                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Información del Curso:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Academia:</strong> <span id="modalAcademiaNombre">-</span></p>
                                    <p class="mb-1"><strong>Curso:</strong> <span id="modalCursoNombre">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Municipio:</strong> <span id="modalMunicipio">-</span></p>
                                    <p class="mb-1"><strong>Fechas:</strong> <span id="modalFechas">-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Meta tags para JavaScript -->
<meta name="user-name" content="{{ Auth::user()->name ?? '' }}">
<meta name="user-email" content="{{ Auth::user()->email ?? '' }}">

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
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    }
    
    input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
</style>

@endsection

@push('scripts')
    <script src="{{ asset('public/js/alumno.js?v=' . time()) }}"></script>
@endpush