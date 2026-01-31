@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary bg-opacity-75 text-white">
            <h2 class="text-center my-3"><i class="fas fa-chalkboard-teacher me-2"></i>Docentes Disponibles</h2>
        </div>

        <div class="card-body">
            <!-- Buscador Avanzado con diseño moderno -->
            <form id="searchForm" method="GET" action="{{ route('academia.ver_docentes') }}" class="mb-5">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="codigo" class="form-control" id="codigoInput" placeholder="Código" value="{{ request('codigo') }}">
                            <label for="codigoInput"><i class="fas fa-hashtag me-2"></i>Código del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="nombre" class="form-control" id="nombreInput" placeholder="Nombre" value="{{ request('nombre') }}">
                            <label for="nombreInput"><i class="fas fa-book me-2"></i>Nombre del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="docente_nombre" class="form-control" id="docenteInput" placeholder="Docente" value="{{ request('docente_nombre') }}">
                            <label for="docenteInput"><i class="fas fa-user-tie me-2"></i>Nombre del Docente</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="provincia" class="form-control" id="provinciaInput" placeholder="Provincia" value="{{ request('provincia') }}">
                            <label for="provinciaInput"><i class="fas fa-map-marked-alt me-2"></i>Provincia</label>
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
                    <button type="button" id="clearBtn" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-broom me-2"></i>Limpiar
                    </button>
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
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>Código</th>
                            <th><i class="fas fa-book me-2"></i>Curso</th>
                            <th><i class="fas fa-chalkboard-teacher me-2"></i>Docente</th>
                            <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                            <th><i class="fas fa-envelope me-2"></i>Contacto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($docentesConCursos as $docente)
                            <tr class="align-middle">
                                <td>{{ $docente->curso_codigo }}</td>
                                <td>{{ $docente->curso_nombre }}</td>
                                <td>{{ $docente->docente_nombre }}</td>
                                <td>{{ $docente->provincia ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm contact-btn" 
                                            data-docente-id="{{ $docente->docente_id }}"
                                            data-docente-nombre="{{ $docente->docente_nombre }}"
                                            data-curso-codigo="{{ $docente->curso_codigo }}"
                                            data-curso-nombre="{{ $docente->curso_nombre }}"
                                            data-provincia="{{ $docente->provincia ?? 'N/A' }}">
                                        <i class="fas fa-envelope me-1"></i> Contactar
                                    </button>
                                </td>
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

<!-- Paginación MEJORADA -->
@if($docentesConCursos->hasPages())
@php
    // Obtener todos los parámetros de búsqueda
    $queryParams = request()->except('page');
    
    // Función para generar URLs con parámetros
    function paginationUrl($paginator, $page, $params) {
        if ($page === null) return '#';
        $url = $paginator->url($page);
        if (!empty($params)) {
            $separator = (strpos($url, '?') === false) ? '?' : '&';
            $url .= $separator . http_build_query($params);
        }
        return $url;
    }
@endphp

<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Página <strong>{{ $docentesConCursos->currentPage() }}</strong> de <strong>{{ $docentesConCursos->lastPage() }}</strong>
    </div>
    
    <nav aria-label="Paginación de docentes">
        <ul class="pagination justify-content-center mb-0">
            <!-- Enlace anterior -->
            <li class="page-item {{ $docentesConCursos->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ paginationUrl($docentesConCursos, $docentesConCursos->currentPage() - 1, $queryParams) }}" aria-label="Anterior">
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
                    <a class="page-link" href="{{ paginationUrl($docentesConCursos, 1, $queryParams) }}">1</a>
                </li>
                @if($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ paginationUrl($docentesConCursos, $i, $queryParams) }}">{{ $i }}</a>
                </li>
            @endfor

            @if($end < $last)
                @if($end < $last - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ paginationUrl($docentesConCursos, $last, $queryParams) }}">{{ $last }}</a>
                </li>
            @endif

            <!-- Enlace siguiente -->
            <li class="page-item {{ !$docentesConCursos->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ paginationUrl($docentesConCursos, $docentesConCursos->currentPage() + 1, $queryParams) }}" aria-label="Siguiente">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Selector de página rápida (ya funciona correctamente) -->
    <div class="d-flex align-items-center">
        <span class="text-muted me-2">Ir a:</span>
        <form method="GET" action="{{ route('academia.ver_docentes') }}" class="d-flex">
            @foreach(request()->except(['page', '_token']) as $key => $value)
                @if($value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            
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

<!-- Modal de Contacto -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-envelope me-2"></i>Contactar Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contactForm" method="POST" action="{{ route('academia.enviar_mensaje_docente') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipientEmail" class="form-label fw-bold">Para:</label>
                        <input type="email" class="form-control" id="recipientEmail" name="email" value="" required>
                        <small class="form-text text-muted">Puedes editar este campo si necesitas</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label fw-bold">Asunto:</label>
                        <input type="text" class="form-control" id="emailSubject" name="subject" value="Oferta de trabajo como docente" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label fw-bold">Mensaje:</label>
                        <textarea class="form-control" id="emailMessage" name="message" rows="8" required></textarea>
                    </div>

                    <!-- Información del curso y docente (solo lectura) -->
                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Información del contacto:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Docente:</strong> <span id="modalDocenteNombre">-</span></p>
                                    <p class="mb-1"><strong>Curso:</strong> <span id="modalCursoNombre">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Código:</strong> <span id="modalCursoCodigo">-</span></p>
                                    <p class="mb-1"><strong>Provincia:</strong> <span id="modalProvincia">-</span></p>
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

<script>
// Pasar el estado del sistema desde PHP a JavaScript
window.sistemaSuscripcionesActivo = @json($sistema_suscripciones_activo);
window.userRol = @json($user->rol);

// Función para abrir el modal de contacto (solo se llama cuando sistema está inactivo)
function abrirModalContactoDocente(docenteId, docenteNombre, cursoCodigo, cursoNombre, provincia) {
    // Llenar los datos en el modal
    document.getElementById('modalDocenteNombre').textContent = docenteNombre;
    document.getElementById('modalCursoNombre').textContent = cursoNombre;
    document.getElementById('modalCursoCodigo').textContent = cursoCodigo;
    document.getElementById('modalProvincia').textContent = provincia;
    
    // Obtener el email del docente
    const emailInput = document.getElementById('recipientEmail');
    emailInput.value = '';
    emailInput.placeholder = "Cargando email del docente...";
    emailInput.disabled = true;
    
    // Llamar a AJAX para obtener el email del docente
    fetch(`/academia/obtener-email-docente/${docenteId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.email) {
                emailInput.value = data.email;
                emailInput.placeholder = "";
            } else {
                emailInput.value = '';
                emailInput.placeholder = "Error al obtener email. Ingrese manualmente.";
            }
            emailInput.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            emailInput.value = '';
            emailInput.placeholder = "Error de conexión. Ingrese manualmente.";
            emailInput.disabled = false;
        });
    
    // Generar mensaje predeterminado
    const userName = "{{ Auth::user()->name }}";
    const userEmail = "{{ Auth::user()->email }}";
    const academiaNombre = "{{ Auth::user()->ident }}";
    const emailMessage = document.getElementById('emailMessage');
    
    if (emailMessage) {
        const mensajePredeterminado = `Estimado/a ${docenteNombre},\n\n` +
            `Nos ponemos en contacto con usted para ofrecerle la posibilidad de impartir el curso "${cursoNombre}" (Código: ${cursoCodigo}) en nuestra academia "${academiaNombre}".\n\n` +
            `Hemos visto su perfil y consideramos que podría adaptarse adecuadamente a este curso. \n\n` +
            `Quedamos a su disposición para cualquier información adicional.\n\n` +
            `Atentamente,\n${userName}\n${userEmail}`;
        
        emailMessage.value = mensajePredeterminado;
    }
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('contactModal'));
    modal.show();
}

// Manejador de clic para el botón "Contactar"
document.addEventListener('click', function(e) {
    if (e.target.closest('.contact-btn')) {
        const button = e.target.closest('.contact-btn');
        
        // Verificar primero si el sistema está activo
        if (window.sistemaSuscripcionesActivo) {
            // Sistema ACTIVO: redirigir a la vista de planes
            window.location.href = '{{ route("suscripcion.planes") }}';
            return; // Salir de la función
        }
        
        // Sistema INACTIVO: abrir modal de contacto
        
        // Obtener datos del botón
        const docenteId = button.getAttribute('data-docente-id');
        const docenteNombre = button.getAttribute('data-docente-nombre');
        const cursoCodigo = button.getAttribute('data-curso-codigo');
        const cursoNombre = button.getAttribute('data-curso-nombre');
        const provincia = button.getAttribute('data-provincia');
        
        // Llamar a la función para abrir el modal
        abrirModalContactoDocente(docenteId, docenteNombre, cursoCodigo, cursoNombre, provincia);
    }
});

// ========== VALIDACIÓN DEL FORMULARIO DE CONTACTO ==========
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        const mensaje = document.getElementById('emailMessage');
        
        if (mensaje && !mensaje.value.trim()) {
            e.preventDefault();
            alert('Por favor, complete el mensaje.');
            return;
        }
        
        // Mostrar indicador de envío
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
            submitBtn.disabled = true;
        }
    });
}

// ========== LIMPIAR FILTROS ==========
const clearBtn = document.getElementById('clearBtn');
if (clearBtn) {
    clearBtn.addEventListener('click', function() {
        // Redirigir a la ruta sin parámetros
        window.location.href = "{{ route('academia.ver_docentes') }}";
        
        // Alternativa: Resetear formulario y enviar
        // document.getElementById('searchForm').reset();
        // document.getElementById('searchForm').submit();
    });
} else {
    console.error('ERROR: No se encontró el botón con id="clearBtn" en vista docentes');
}
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
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.65em;
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
</style>
@endsection