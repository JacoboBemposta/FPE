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

                    <div class="col-md-4">
                        <div class="form-floating">
                            <select name="docente_asignado" class="form-control" id="docenteAsignadoSelect">
                                <option value="todos" {{ request('docente_asignado', 'todos') == 'todos' ? 'selected' : '' }}>Todos los cursos</option>
                                <option value="con" {{ request('docente_asignado') == 'con' ? 'selected' : '' }}>Con docente asignado</option>
                                <option value="sin" {{ request('docente_asignado') == 'sin' ? 'selected' : '' }}>Sin docente asignado</option>
                            </select>
                            <label for="docenteAsignadoSelect"><i class="fas fa-chalkboard-teacher me-2"></i>Docente asignado</label>
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
                    <strong>{{ $cursosAcademicos->firstItem() ?? 0 }}-{{ $cursosAcademicos->lastItem() ?? 0 }}</strong> 
                    de <strong>{{ $cursosAcademicos->total() ?? 0 }}</strong> resultados
                </div>
            </div>

            <!-- Tabla de Academias con diseño moderno -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            {{-- <th><i class="fas fa-school me-2"></i>Academia</th> --}}
                            <th><i class="fas fa-hashtag me-2"></i>Código</th>
                            <th><i class="fas fa-book me-2"></i>Curso</th>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Municipio</th>
                            <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                            <th><i class="fas fa-calendar-alt me-2"></i>Inicio</th>
                            <th><i class="fas fa-calendar-check me-2"></i>Fin</th>
                            <th><i class="fas fa-chalkboard-teacher me-2"></i>Docente</th>
                            <th><i class="fas fa-envelope me-2"></i>Contacto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursosAcademicos as $cursoAcademico) 
                            <tr>
                                
                                {{-- <td>{{ $cursoAcademico->academia_nombre ?? 'N/A' }}</td> --}}
                                <td>{{ $cursoAcademico->curso_codigo ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso_nombre ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td>
                                <td class="docente-col">
                                    @if($cursoAcademico->docente_nombre)
                                        <span class="badge bg-success text-dark">asignado</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Sin asignar</span>
                                    @endif
                                </td>
<td>
    @if($cursoAcademico->ya_enviado_cv)
        <span class="badge bg-success" style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">
            <i class="fas fa-check-circle me-1"></i> Mail Enviado
        </span>
    @else
        <button type="button" class="btn btn-primary btn-sm contact-btn" 
                data-bs-toggle="modal" 
                data-bs-target="#contactModal"
                data-academia-id="{{ $cursoAcademico->academia_id ?? '' }}"
                data-academia-nombre="{{ $cursoAcademico->academia_nombre ?? 'N/A' }}"
                data-curso-acad-id="{{ $cursoAcademico->curso_acad_id ?? '' }}"
                data-curso-nombre="{{ $cursoAcademico->curso_nombre ?? 'N/A' }}"
                data-municipio="{{ $cursoAcademico->municipio ?? 'N/A' }}"
                data-inicio="{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}"
                data-fin="{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}">
            <i class="fas fa-envelope me-1"></i> Contactar
        </button>
    @endif
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-university fa-2x text-muted mb-3"></i>
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
                
                <nav aria-label="Paginación de academias">
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
                    <form method="GET" action="{{ route('profesor.ver_academias') }}" class="d-flex">
                        <input type="hidden" name="academia_nombre" value="{{ request('academia_nombre') }}">
                        <input type="hidden" name="curso_codigo" value="{{ request('curso_codigo') }}">
                        <input type="hidden" name="curso_nombre" value="{{ request('curso_nombre') }}">
                        <input type="hidden" name="provincia" value="{{ request('provincia') }}">
                        <input type="hidden" name="municipio" value="{{ request('municipio') }}">
                        <input type="hidden" name="docente_asignado" value="{{ request('docente_asignado', 'todos') }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        
                        
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
                <a href="{{ route('profesor.miscursos') }}" class="btn btn-success px-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Mis Cursos
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
                    <i class="fas fa-envelope me-2"></i>Enviar Candidatura Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contactForm" method="POST" action="{{ route('profesor.enviar_candidatura') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="curso_acad_id" id="cursoAcadIdInput">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipientEmail" class="form-label fw-bold">Para:</label>
                        <input type="email" class="form-control" id="recipientEmail" name="email" value="" required>
                        <small class="form-text text-muted">Puedes editar este campo para enviar a tu email de prueba</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label fw-bold">Asunto:</label>
                        <input type="text" class="form-control" id="emailSubject" name="subject" value="Candidatura Docente" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label fw-bold">Mensaje:</label>
                        <textarea class="form-control" id="emailMessage" name="message" rows="8" required></textarea>
                    </div>

                    <!-- Campo para adjuntar archivo -->
                    <div class="mb-3">
                        <label for="cvAttachment" class="form-label fw-bold">
                            <i class="fas fa-paperclip me-1"></i>Adjuntar CV (Opcional)
                        </label>
                        <input type="file" class="form-control" id="cvAttachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">
                            Formatos aceptados: PDF, DOC, DOCX, JPG, PNG. Tamaño máximo: 10MB.
                        </small>
                        <div id="filePreview" class="mt-2" style="display: none;">
                            <div class="alert alert-info py-2">
                                <i class="fas fa-file me-2"></i>
                                <span id="fileName"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
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
                        <i class="fas fa-paper-plane me-1"></i> Enviar Candidatura
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript mejorado para el buscador y modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  
    const userName = @json(auth()->user()->name);
    const userEmail = @json(auth()->user()->email);

    // ========== LIMPIAR FORMULARIO DE BÚSQUEDA ==========
    const clearBtn = document.getElementById('clearBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            const form = document.getElementById('searchForm');
            const inputs = form.querySelectorAll('input[type="text"]');
            const selects = form.querySelectorAll('select');
            
            // Limpiar inputs de texto
            inputs.forEach(input => input.value = '');
            
            // Resetear selects a valores por defecto
            selects.forEach(select => {
                if (select.name === 'per_page') {
                    select.value = '10';
                } else if (select.name === 'docente_asignado') {
                    select.value = 'todos';
                }
            });
            
            form.submit();
        });
    }

    // ========== VALIDACIÓN DEL FORMULARIO DE BÚSQUEDA ==========
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const academiaInput = document.getElementById('academiaInput');
            const cursoNombreInput = document.getElementById('nombreCursoInput');
            const cursoCodigoInput = document.getElementById('codigoInput');
            const provinciaInput = document.getElementById('provinciaInput');
            const municipioInput = document.getElementById('municipioInput');
            
            // Normalizar todos los campos de texto a minúsculas y trim
            if(academiaInput && academiaInput.value) {
                academiaInput.value = academiaInput.value.trim().toLowerCase();
            }
            
            if(cursoNombreInput && cursoNombreInput.value) {
                cursoNombreInput.value = cursoNombreInput.value.trim().toLowerCase();
            }
            
            if(cursoCodigoInput && cursoCodigoInput.value) {
                cursoCodigoInput.value = cursoCodigoInput.value.trim().toLowerCase();
            }
            
            if(provinciaInput && provinciaInput.value) {
                provinciaInput.value = provinciaInput.value.trim().toLowerCase();
            }
            
            if(municipioInput && municipioInput.value) {
                municipioInput.value = municipioInput.value.trim().toLowerCase();
            }
        });
    }

// ========== MANEJO DEL MODAL DE CONTACTO CON AJAX ==========
document.addEventListener('click', async function(e) {
    if (e.target.closest('.contact-btn')) {
        const button = e.target.closest('.contact-btn');
  
        
        // Obtener datos del botón
        const academiaId = button.getAttribute('data-academia-id');
        const cursoAcadId = button.getAttribute('data-curso-acad-id');
        const academiaNombre = button.getAttribute('data-academia-nombre');
        const cursoNombre = button.getAttribute('data-curso-nombre');
        const municipio = button.getAttribute('data-municipio');
        const inicio = button.getAttribute('data-inicio');
        const fin = button.getAttribute('data-fin');



        // Actualizar información del curso en el modal (sin email aún)
        const cursoAcadIdInput = document.getElementById('cursoAcadIdInput');
        if (cursoAcadIdInput && cursoAcadId) {
            cursoAcadIdInput.value = cursoAcadId;

        }
        const modalAcademiaNombre = document.getElementById('modalAcademiaNombre');
        const modalCursoNombre = document.getElementById('modalCursoNombre');
        const modalMunicipio = document.getElementById('modalMunicipio');
        const modalFechas = document.getElementById('modalFechas');
        
        if (modalAcademiaNombre) modalAcademiaNombre.textContent = academiaNombre;
        if (modalCursoNombre) modalCursoNombre.textContent = cursoNombre;
        if (modalMunicipio) modalMunicipio.textContent = municipio;
        if (modalFechas) modalFechas.textContent = `${inicio} - ${fin}`;

        // Limpiar y preparar campo de email
        const emailInput = document.getElementById('recipientEmail');
        if (emailInput) {
            emailInput.value = '';
            emailInput.placeholder = "Cargando email de la academia...";
            emailInput.disabled = true;
        }

        try {
            // Obtener email de la academia por AJAX
            if (academiaId) {
       
                
                // Usar la ruta correcta - IMPORTANTE
                const url = `/profesor/obtener-email/${academiaId}`;
        
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
       
                
                if (response.ok) {
                    const data = await response.json();
               
                    
                    if (data.email) {
                        // Email obtenido correctamente
                        if (emailInput) {
                            emailInput.value = data.email;
                            emailInput.placeholder = "";
                            emailInput.disabled = false;
                  
                        }
                    } else if (data.error) {
                        // Error del servidor
                        console.error('Error del servidor:', data.error);
                        if (emailInput) {
                            emailInput.value = '';
                            emailInput.placeholder = `Error: ${data.error}. Ingresa manualmente.`;
                            emailInput.disabled = false;
                        }
                    }
                } else {
                    // Error HTTP
                    console.error('Error HTTP:', response.status, response.statusText);
                    if (emailInput) {
                        emailInput.value = '';
                        emailInput.placeholder = `Error ${response.status}. Ingresa manualmente.`;
                        emailInput.disabled = false;
                    }
                }
            } else {
                // No hay academiaId
                console.error('No se encontró academiaId en el botón');
                if (emailInput) {
                    emailInput.value = '';
                    emailInput.placeholder = "Error: ID de academia no encontrado. Ingresa manualmente.";
                    emailInput.disabled = false;
                }
            }
        } catch (error) {
            console.error('Error en la petición AJAX:', error);
            if (emailInput) {
                emailInput.value = '';
                emailInput.placeholder = "Error de conexión. Ingresa manualmente.";
                emailInput.disabled = false;
            }
        }

        // Generar mensaje predeterminado
        const emailMessage = document.getElementById('emailMessage');
        if (emailMessage) {
            const mensajePredeterminado = `Estimados señores de ${academiaNombre},

Me dirijo a ustedes para expresar mi interés en la plaza docente para el curso "${cursoNombre}" que se imparte en ${municipio}.

He revisado la información del curso con fechas de ${inicio} a ${fin} y considero que mi perfil y experiencia son adecuados para impartir esta formación.

Adjunto mi CV para su consideración y quedo a su disposición para una entrevista personal.

Agradeciendo de antemano su atención, reciban un cordial saludo.

Atentamente,
    ${userName}
    Email: ${userEmail}
    Teléfono: [Su teléfono de contacto]`;

            emailMessage.value = mensajePredeterminado;
        }
        
        // Limpiar archivo adjunto
        const cvAttachment = document.getElementById('cvAttachment');
        const filePreview = document.getElementById('filePreview');
        if (cvAttachment) cvAttachment.value = '';
        if (filePreview) filePreview.style.display = 'none';
    }
});

    // ========== VISTA PREVIA DEL ARCHIVO ADJUNTO ==========
    const cvAttachment = document.getElementById('cvAttachment');
    if (cvAttachment) {
        cvAttachment.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            
            if (file && filePreview && fileName) {
                // Validar tamaño (10MB máximo)
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
                    this.value = '';
                    return;
                }
                
                // Mostrar vista previa
                fileName.textContent = file.name;
                filePreview.style.display = 'block';
            }
        });
    }

    // ========== FUNCIÓN PARA ELIMINAR ARCHIVO ==========
    window.removeFile = function() {
        const cvAttachment = document.getElementById('cvAttachment');
        const filePreview = document.getElementById('filePreview');
        if (cvAttachment) cvAttachment.value = '';
        if (filePreview) filePreview.style.display = 'none';
    }

    // ========== VALIDACIÓN DEL FORMULARIO DE CONTACTO ==========
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const mensaje = document.getElementById('emailMessage');
            const archivo = document.getElementById('cvAttachment');
            
            if (mensaje && !mensaje.value.trim()) {
                e.preventDefault();
                alert('Por favor, complete el mensaje de candidatura.');
                return;
            }
            
            // Validar tamaño del archivo
            if (archivo && archivo.files[0]) {
                const maxSize = 10 * 1024 * 1024;
                if (archivo.files[0].size > maxSize) {
                    e.preventDefault();
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
                    return;
                }
            }
            
            // Mostrar indicador de envío
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
                submitBtn.disabled = true;
            }
        });
    }

    // ========== EFECTOS VISUALES (OPCIONALES) ==========
    const tableRows = document.querySelectorAll('.table-hover tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
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
    
    /* Estilos para paginación */
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
</style>
@endsection