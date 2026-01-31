@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="page-header-modern bg-gradient-primary rounded-3 p-4 mb-4 shadow-sm">
        <h1 class="text-white mb-2"><i class="fas fa-university me-2"></i>Cursos que se van a impartir</h1>
        <p class="text-white-50 mb-0"><small><i class="fas fa-info-circle me-1"></i> Solo se muestran cursos que no han iniciado</small></p>
    </div>
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <!-- Buscador Avanzado  -->
            <form id="searchForm" method="GET" action="{{ route('profesor.ver_academias') }}" class="mb-5">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" name="curso_codigo" class="form-control" id="codigoInput" placeholder="Código" value="{{ request('curso_codigo') }}">
                            <label for="codigoInput"><i class="fas fa-hashtag me-2"></i>Código del Curso</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" name="curso_nombre" class="form-control" id="nombreCursoInput" placeholder="Nombre" value="{{ request('curso_nombre') }}">
                            <label for="nombreCursoInput"><i class="fas fa-book me-2"></i>Nombre del Curso</label>
                        </div>
                    </div>
                    
                    <!-- NUEVO: Filtro por nivel de cualificación -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select name="nivel_cualificacion" class="form-control" id="nivelCualificacionSelect">
                                <option value="todos" {{ request('nivel_cualificacion', 'todos') == 'todos' ? 'selected' : '' }}>Todos los niveles</option>
                                <option value="1" {{ request('nivel_cualificacion') == '1' ? 'selected' : '' }}>Nivel 1</option>
                                <option value="2" {{ request('nivel_cualificacion') == '2' ? 'selected' : '' }}>Nivel 2</option>
                                <option value="3" {{ request('nivel_cualificacion') == '3' ? 'selected' : '' }}>Nivel 3</option>
                            </select>
                            <label for="nivelCualificacionSelect"><i class="fas fa-layer-group me-2"></i>Nivel de cualificación</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" name="provincia" class="form-control" id="provinciaInput" placeholder="Provincia" value="{{ request('provincia') }}">
                            <label for="provinciaInput"><i class="fas fa-map-marked-alt me-2"></i>Provincia</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" name="municipio" class="form-control" id="municipioInput" placeholder="Municipio" value="{{ request('municipio') }}">
                            <label for="municipioInput"><i class="fas fa-map-marker-alt me-2"></i>Municipio</label>
                        </div>
                    </div>

                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select name="per_page" class="form-control" id="perPageSelect" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 por página</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 por página</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 por página</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 por página</option>
                            </select>
                            <label for="perPageSelect"><i class="fas fa-list-ol me-2"></i>Resultados por página</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <!-- Contador de resultados activos -->
                    @if(request()->hasAny(['curso_codigo', 'curso_nombre', 'nivel_cualificacion', 'provincia', 'municipio', 'docente_asignado']))
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary rounded-pill me-2">Filtros activos</span>
                        <small class="text-muted">
                            @php
                                $filtrosActivos = 0;
                                if(request('curso_codigo')) $filtrosActivos++;
                                if(request('curso_nombre')) $filtrosActivos++;
                                if(request('nivel_cualificacion') && request('nivel_cualificacion') != 'todos') $filtrosActivos++;
                                if(request('provincia')) $filtrosActivos++;
                                if(request('municipio')) $filtrosActivos++;
                                if(request('docente_asignado') && request('docente_asignado') != 'todos') $filtrosActivos++;
                            @endphp
                            {{ $filtrosActivos }} filtro(s) aplicado(s)
                        </small>
                    </div>
                    @else
                    <div></div> <!-- Espaciador -->
                    @endif
                    
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary me-2 px-4 btn-modern-action">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                        <button type="button" id="clearBtn" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-broom me-2"></i>Limpiar
                        </button>
                    </div>
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
                            <th><i class="fas fa-hashtag me-2"></i>Código</th>
                            <th><i class="fas fa-book me-2"></i>Curso</th>
                            <th><i class="fas fa-layer-group me-2"></i>Nivel</th>
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
                                <td>{{ $cursoAcademico->curso_codigo ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->curso_nombre ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $nivel = '';
                                        if(isset($cursoAcademico->cualificacion)) {
                                            $ultimoDigito = substr($cursoAcademico->cualificacion, -1);
                                            if(in_array($ultimoDigito, ['1', '2', '3'])) {
                                                $nivel = $ultimoDigito;
                                            }
                                        }
                                    @endphp
                                    @if($nivel)
                                        <span class="badge nivel-badge nivel-{{ $nivel }}">
                                            Nivel {{ $nivel }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                                <td>{{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}</td>
                                <td class="docente-col">
                                    @if($cursoAcademico->docente_nombre)
                                        <span class="badge bg-success">Asignado</span>
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
                                        <button type="button" class="btn btn-primary btn-sm contact-btn btn-modern-action" 
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
                        <input type="hidden" name="curso_codigo" value="{{ request('curso_codigo') }}">
                        <input type="hidden" name="curso_nombre" value="{{ request('curso_nombre') }}">
                        <input type="hidden" name="nivel_cualificacion" value="{{ request('nivel_cualificacion', 'todos') }}">
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
                        <button type="submit" class="btn btn-sm btn-primary ms-2 btn-modern-action">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Botón Volver -->
            <div class="text-center mt-4 pt-3 border-top">
                @if(Auth::user()?->rol === 'academia')
                    <a href="{{ route('academia.miscursos') }}" class="btn btn-secondary btn-modern-back">
                        <i class="fas fa-arrow-left me-2"></i> Volver a Mis Cursos
                    </a>
                @elseif(Auth::user()?->rol === 'profesor')
                    <a href="{{ route('profesor.miscursos') }}" class="btn btn-secondary btn-modern-back">
                        <i class="fas fa-arrow-left me-2"></i> Volver a Mis Cursos
                    </a>
                @else
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-modern-back">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                @endif
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

                        <input type="email" class="form-control" id="recipientEmail" name="email" value="" hidden>

                    </div>
                    
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label fw-bold">Asunto:</label>
                        <input type="text" class="form-control" id="emailSubject" name="subject" value="Candidatura Docente" >
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
                    <button type="submit" class="btn btn-primary btn-modern-action">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Candidatura
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
    function abrirModalContactoDocente(academiaId, academiaNombre, cursoAcadId, cursoNombre, municipio, inicio, fin) {
        // Llenar los datos en el modal
        document.getElementById('modalAcademiaNombre').textContent = academiaNombre;
        document.getElementById('modalCursoNombre').textContent = cursoNombre;
        document.getElementById('modalMunicipio').textContent = municipio;
        document.getElementById('modalFechas').textContent = `${inicio} - ${fin}`;
        
        // Establecer el ID del curso académico
        document.getElementById('cursoAcadIdInput').value = cursoAcadId;
        
        // Obtener el email de la academia
        const emailInput = document.getElementById('recipientEmail');
        emailInput.value = '';
        emailInput.placeholder = "Cargando email de la academia...";
        emailInput.disabled = true;
        
        // Llamar a AJAX para obtener el email de la academia
        fetch(`/profesor/obtener-email/${academiaId}`)
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
        const emailMessage = document.getElementById('emailMessage');
        
        if (emailMessage) {
            const mensajePredeterminado = `Estimado equipo de ${academiaNombre},

            Me pongo en contacto con ustedes para manifestar mi interés en la plaza docente correspondiente al curso «${cursoNombre}», que se impartirá en ${municipio}.

            Tras revisar la información disponible sobre la formación, prevista entre las fechas ${inicio} y ${fin}, considero que mi perfil profesional y experiencia docente se ajustan adecuadamente a los objetivos del curso.

            Adjunto mi currículum vitae para su valoración y quedo a su disposición para ampliar cualquier información o concertar una entrevista cuando lo estimen oportuno.

            Agradeciendo de antemano su atención, reciban un cordial saludo.


            ${userName}
            Email: ${userEmail}
            Teléfono: [Su teléfono de contacto]`;

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
        
            e.preventDefault();
            e.stopPropagation();
                    
            // Verificar primero si el sistema está activo
            if (window.sistemaSuscripcionesActivo) {

                window.location.href = '{{ route("suscripcion.planes") }}';
                return; // Salir de la función
            }

            
            // Obtener datos del botón
            const academiaId = button.getAttribute('data-academia-id');
            const academiaNombre = button.getAttribute('data-academia-nombre');
            const cursoAcadId = button.getAttribute('data-curso-acad-id');
            const cursoNombre = button.getAttribute('data-curso-nombre');
            const municipio = button.getAttribute('data-municipio');
            const inicio = button.getAttribute('data-inicio');
            const fin = button.getAttribute('data-fin');
            
            // Llamar a la función para abrir el modal
            abrirModalContactoDocente(academiaId, academiaNombre, cursoAcadId, cursoNombre, municipio, inicio, fin);
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
    // ========== LIMPIAR FILTROS ==========
    document.addEventListener('DOMContentLoaded', function() {
        const clearBtn = document.getElementById('clearBtn');
        
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
  
                window.location.href = "{{ route('profesor.ver_academias') }}";
                
            });
        }
    });
</script>

<!-- Estilos CSS personalizados -->
<style>
    .page-header-modern {
        background: linear-gradient(135deg, #4361ee, #3a0ca3) !important;
    }
    
    .card {
        border: none;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
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
        color: #4361ee;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        border-color: #4361ee;
    }
    
    .table th {
        white-space: nowrap;
        background-color: #4361ee;
        color: white;
        border: none;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.1) !important;
    }
    
    /* Estilos para badges de nivel */
    .nivel-badge {
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    
    .nivel-1 {
        background-color: #4ade80;
        color: #065f46;
    }
    
    .nivel-2 {
        background-color: #60a5fa;
        color: #1e40af;
    }
    
    .nivel-3 {
        background-color: #f87171;
        color: #7f1d1d;
    }
    

    .btn-modern-action {
        background-color: #4361ee;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-modern-action:hover {
        background-color: #3a56d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        color: white;
    }
    
    .btn-modern-back {
        border-radius: 6px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        background-color: #6c757d;
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-modern-back:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        color: white;
    }
    
    /* Mejora para inputs de búsqueda -->
    #nombreCursoInput, #codigoInput {
        text-transform: lowercase;
    }
    
    /* Estilos para paginación -->
    .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .page-link {
        color: #4361ee;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin: 0 2px;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        color: white;
        background-color: #4361ee;
        border-color: #4361ee;
        transform: translateY(-1px);
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
    }
    
    /* Badge para filtros activos -->
    .badge.bg-primary {
        background-color: #4361ee !important;
    }
    
    /* Responsive -->
    @media (max-width: 768px) {
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between > div:first-child {
            order: 2;
        }
        
        .d-flex.justify-content-between > div:last-child {
            order: 1;
        }
    }
</style>
@endsection