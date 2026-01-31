@extends('layouts.app')
<style>
/* Estilos para mejorar la jerarquía visual */
.curso-header {
    font-size: 1.1rem !important;
    padding: 0.75rem 1rem !important;
}

.curso-title {
    font-size: 1rem;
    font-weight: 600;
}

.modulo-container {
    margin-left: 1.5rem;
    border-left: 3px solid #e9ecef;
    padding-left: 1rem;
}

.modulo-item {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6 !important;
}

.unidades-container {
    margin-left: 1rem;
}

.badge-sm {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}

.accordion-body {
    padding: 1rem 1.25rem;
}

.card-body {
    padding: 1rem;
}

/* Mejorar la jerarquía visual */
.familia-profesional {
    font-size: 1.2rem;
    font-weight: 600;
}

.curso-profesional {
    font-size: 1rem;
    font-weight: 500;
}

.modulo-profesional {
    font-size: 1rem;
    font-weight: 400;
}

.unidad-profesional {
    font-size: 0.9rem;
}

</style>
@section('content')


<div class="container">

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-envelope"></i> Estadísticas de Emails</h5>
    </div>
    <div class="card-body">
        <p>Consulta las estadísticas de la plataforma:</p>
        <a href="{{ route('admin.stats') }}" class="btn btn-primary">
            <i class="fas fa-chart-bar"></i> Ver Estadísticas
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Sistema de Suscripciones</h5>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" 
                   id="sistemaSuscripciones" 
                   {{ $sistema_suscripciones_activo ? 'checked' : '' }}
                   onchange="toggleSistemaSuscripciones(this)">
            <label class="form-check-label" for="sistemaSuscripciones">
                Activar sistema de suscripciones
            </label>
        </div>
        <small class="text-muted">
            Cuando activo, los usuarios necesitan suscripción para contactar.
            <span id="estadoActual" class="badge ms-2 {{ $sistema_suscripciones_activo ? 'bg-success' : 'bg-secondary' }}">
                {{ $sistema_suscripciones_activo ? 'ACTIVO' : 'INACTIVO' }}
            </span>
        </small>
    </div>
</div>
    <h1>Administración de Cursos</h1>

    <!-- Botones para crear nuevos elementos -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearFamiliaModal">
            <i class="fas fa-plus"></i> Crear Familia Profesional
        </button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearCursoModal">
            <i class="fas fa-plus"></i> Crear Curso
        </button>
    </div>

    <!-- Listado jerárquico de Familias Profesionales -->
    <div class="accordion" id="familiasAccordion">
        @foreach($familiasProfesionales as $familia)
        <div class="accordion-item">
            <h2 class="accordion-header" id="familiaHeading{{ $familia->id }}">
                <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" 
                    data-bs-target="#familiaCollapse{{ $familia->id }}"
                    aria-expanded="false" 
                    aria-controls="familiaCollapse{{ $familia->id }}"
                    onclick="loadCursos({{ $familia->id }})">
                    <span class="d-flex justify-content-between align-items-center w-100">
                        <span>
                            <strong>{{ $familia->codigo }}</strong> - {{ $familia->nombre }}
                        </span>
                        <span class="badge bg-primary ms-3">{{ $familia->cursos_count ?? $familia->cursos->count() }} cursos</span>
                    </span>
                </button>
            </h2>

            <div id="familiaCollapse{{ $familia->id }}" 
                 class="accordion-collapse collapse" 
                 aria-labelledby="familiaHeading{{ $familia->id }}" 
                 data-bs-parent="#familiasAccordion">
                <div class="accordion-body">
                    <div id="cursos-container-{{ $familia->id }}" 
                         class="cursos-container"
                         data-url="{{ route('admin.familias.cursos', ['familia' => $familia->id]) }}"> 
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando cursos...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando cursos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- MODALES BÁSICOS -->

<!-- Modal Crear Familia -->
<div class="modal fade" id="crearFamiliaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.familias-profesionales.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Familia Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Crear Curso -->
<div class="modal fade" id="crearCursoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Familia Profesional</label>
                        <select name="familia_profesional_id" class="form-control" required>
                            @foreach($familiasProfesionales as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->codigo }} - {{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cualificacion</label>
                        <input type="text" name="cualificacion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horas totales</label>
                        <input type="number" name="horas" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALES DINÁMICOS -->
@foreach($familiasProfesionales as $familia)
    @foreach($familia->cursos as $curso)
        <!-- Modal Editar Curso -->
        <div class="modal fade" id="editarCursoModal{{ $curso->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.cursos.update', $curso->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Curso: {{ $curso->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Familia Profesional</label>
                                <select name="familia_profesional_id" class="form-control" required>
                                    @foreach($familiasProfesionales as $familiaOption)
                                        <option value="{{ $familiaOption->id }}" {{ $curso->familia_profesional_id == $familiaOption->id ? 'selected' : '' }}>
                                            {{ $familiaOption->codigo }} - {{ $familiaOption->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Código</label>
                                <input type="text" name="codigo" class="form-control" value="{{ $curso->codigo }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $curso->nombre }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cualificación</label>
                                <input type="text" name="cualificacion" class="form-control" value="{{ $curso->cualificacion }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Horas totales</label>
                                <input type="number" name="horas" class="form-control" value="{{ $curso->horas }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para agregar módulo -->
        <div class="modal fade" id="agregarModuloModal{{ $curso->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.cursos.modulos.store', $curso->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Módulo a: {{ $curso->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Código del Módulo</label>
                                <input type="text" name="codigo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre del Módulo</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Horas del Módulo</label>
                                <input type="number" name="horas" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Módulo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para agregar unidad formativa -->
        @foreach($curso->modulos as $modulo)
        <div class="modal fade" id="agregarUnidadModal{{ $modulo->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.unidades.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="modulo_id" value="{{ $modulo->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Añadir Unidad a: {{ $modulo->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Código</label>
                                <input type="text" name="codigo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Horas</label>
                                <input type="number" name="horas" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar Unidad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endforeach

<!-- Script de inicialización mejorado -->
<script>
// Función para cargar cursos de una familia
function loadCursos(familiaId) {
    const container = document.getElementById(`cursos-container-${familiaId}`);
    const url = container.getAttribute('data-url');
    


    // Si ya se cargaron los cursos, no hacer nada
    if (container.getAttribute('data-loaded') === 'true') {
 
        return;
    }

    // Mostrar loading
    container.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando cursos...</span>
            </div>
            <p class="mt-2 text-muted">Cargando cursos...</p>
        </div>
    `;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
 
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(cursos => {

        
        if (cursos.length === 0) {
            container.innerHTML = `
                <div class="alert alert-warning text-center">
                    <i class="fas fa-info-circle"></i> No hay cursos en esta familia profesional.
                </div>
            `;
        } else {
            container.innerHTML = cursos.map(curso => `
                <div class="curso-item card mb-3" data-curso-id="${curso.id}">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-book me-2"></i>
                                <strong>${curso.codigo}</strong> - ${curso.nombre} 
                                <span class="badge bg-secondary ms-2">${curso.horas}h</span>
                            </h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editarCursoModal${curso.id}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form action="/admin/cursos/${curso.id}" 
                                      method="POST" 
                                      class="d-inline">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este curso?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="border-bottom pb-2">
                            <i class="fas fa-list-ul me-2"></i>Módulos del Curso 
                            <span class="badge bg-info">${curso.modulos_count}</span>
                        </h6>
                        
                        <div id="modulos-container-${curso.id}" 
                             class="modulos-container"
                             data-url="/admin/cursos/${curso.id}/modulos">
                            <button class="btn btn-outline-primary btn-sm" onclick="loadModulos(${curso.id})">
                                <i class="fas fa-sync"></i> Cargar Módulos
                            </button>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#agregarModuloModal${curso.id}">
                                <i class="fas fa-plus"></i> Añadir Módulo
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        container.setAttribute('data-loaded', 'true');
    })
    .catch(error => {
        console.error('❌ Error cargando cursos:', error);
        container.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i> Error al cargar los cursos.
                <br><small>${error.message}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-warning" onclick="loadCursos(${familiaId})">
                        <i class="fas fa-redo"></i> Reintentar
                    </button>
                </div>
            </div>
        `;
    });
}

    // Función para cargar cursos de una familia
function loadCursos(familiaId) {
    const container = document.getElementById(`cursos-container-${familiaId}`);
    const url = `/admin/familias/${familiaId}/cursos`;
    


    // Si ya se cargaron los cursos, no hacer nada
    if (container.getAttribute('data-loaded') === 'true') {

        return;
    }

    // Mostrar loading
    container.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando cursos...</span>
            </div>
            <p class="mt-2 text-muted">Cargando cursos...</p>
        </div>
    `;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
 
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }).catch(() => {
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(cursos => {
  
        
        // Verificar si es un error
        if (cursos.error) {
            throw new Error(cursos.message || cursos.error);
        }
        
        if (cursos.length === 0) {
            container.innerHTML = `
                <div class="alert alert-warning text-center">
                    <i class="fas fa-info-circle"></i> No hay cursos en esta familia profesional.
                </div>
            `;
        } else {
            container.innerHTML = cursos.map(curso => `
    <div class="curso-item card mb-3" data-curso-id="${curso.id}">
        <div class="card-header bg-light curso-header" style="cursor: pointer;" onclick="toggleModulos(${curso.id})">
            <div class="d-flex justify-content-between align-items-center">
                <div class="curso-title">
                    <i class="fas fa-book me-2"></i>
                    <strong class="curso-profesional">${curso.codigo}</strong> - <span class="curso-profesional">${curso.nombre}</span>
                    <span class="badge bg-secondary badge-sm ms-2">${curso.horas}h</span>
                    <span class="badge bg-info badge-sm ms-2">${curso.modulos_count} módulos</span>
                    <i class="fas fa-chevron-down ms-2 toggle-icon" id="toggle-icon-${curso.id}"></i>
                </div>
                <div class="btn-group" onclick="event.stopPropagation()">
                    <button class="btn btn-sm btn-outline-warning" 
                            style="display: inline-flex; align-items: center; height: 31px;"
                            data-bs-toggle="modal" 
                            data-bs-target="#editarCursoModal${curso.id}">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <form action="/admin/cursos/${curso.id}" 
                          method="POST" 
                          class="d-inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este curso?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body modulos-body" id="modulos-body-${curso.id}" style="display: none;">
            <h6 class="border-bottom pb-2" style="font-size: 0.95rem;">
                <i class="fas fa-list-ul me-2"></i>Módulos del Curso 
                <span class="badge bg-info badge-sm">${curso.modulos_count}</span>
            </h6>
            
            <div id="modulos-container-${curso.id}" 
                 class="modulos-container modulo-container">
                <div class="text-center">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Cargando módulos...</span>
                    </div>
                    <span class="text-muted ms-2">Cargando módulos...</span>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#agregarModuloModal${curso.id}">
                    <i class="fas fa-plus"></i> Añadir Módulo
                </button>
            </div>
        </div>
    </div>
`).join('');
            
            // Cargar módulos automáticamente para el primer curso (opcional)
            // loadModulos(cursos[0].id);
        }
        container.setAttribute('data-loaded', 'true');
    })
    .catch(error => {
        console.error('❌ Error cargando cursos:', error);
        container.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i> Error al cargar los cursos.
                <br><small>${error.message}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-warning" onclick="loadCursos(${familiaId})">
                        <i class="fas fa-redo"></i> Reintentar
                    </button>
                </div>
            </div>
        `;
    });
}

// Función para mostrar/ocultar módulos
function toggleModulos(cursoId) {
    const modulosBody = document.getElementById(`modulos-body-${cursoId}`);
    const toggleIcon = document.getElementById(`toggle-icon-${cursoId}`);
    
    if (modulosBody.style.display === 'none') {
        // Mostrar módulos
        modulosBody.style.display = 'block';
        toggleIcon.classList.remove('fa-chevron-down');
        toggleIcon.classList.add('fa-chevron-up');
        
        // Cargar módulos si no están cargados
        const modulosContainer = document.getElementById(`modulos-container-${cursoId}`);
        if (modulosContainer.getAttribute('data-loaded') !== 'true') {
            loadModulos(cursoId);
        }
    } else {
        // Ocultar módulos
        modulosBody.style.display = 'none';
        toggleIcon.classList.remove('fa-chevron-up');
        toggleIcon.classList.add('fa-chevron-down');
    }
}

// Función para cargar módulos de un curso
function loadModulos(cursoId) {
    
    
    const container = document.getElementById(`modulos-container-${cursoId}`);
    const url = `/admin/cursos/${cursoId}/modulos`;
    

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {

        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('❌ Error response text:', text);
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(modulos => {
   
        
        if (!Array.isArray(modulos)) {
            console.error('❌ modulos no es array:', modulos);
            throw new Error('La respuesta no es un array');
        }
        
        if (modulos.length === 0) {
            container.innerHTML = '<p class="text-muted">Este curso no tiene módulos.</p>';
        } else {
            // Generar el HTML con logs para cada formulario
            container.innerHTML = modulos.map(modulo => {
                const deleteUrl = `/admin/cursos/${cursoId}/modulos/${modulo.id}`;

                
                return `
    <div class="modulo-item border rounded p-3 mb-2 modulo-container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong class="modulo-profesional">
                    ${modulo.codigo} - ${modulo.nombre}
                </strong>
                <span class="badge bg-light text-dark badge-sm ms-2">${modulo.horas}h</span>
                <span class="badge bg-info badge-sm ms-2">${modulo.unidades_count} unidades</span>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                        style="display: inline-flex; align-items: center; height: 25px;"
                        data-bs-toggle="modal" 
                        data-bs-target="#agregarUnidadModal${modulo.id}">
                    <i class="fas fa-plus"></i> Unidad
                </button>
                <form action="${deleteUrl}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="console.log('📌 Enviando formulario para módulo ${modulo.id}, curso: ${cursoId}')">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar módulo?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div id="unidades-container-${modulo.id}" class="unidades-container mt-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="loadUnidades(${modulo.id})">
                <i class="fas fa-sync"></i> Cargar Unidades
            </button>
        </div>
    </div>`;
            }).join('');
        }
        container.setAttribute('data-loaded', 'true');
    })
    .catch(error => {
        console.error('❌ Error cargando módulos:', error);
        container.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Error al cargar los módulos.
                <br><small>${error.message}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-warning" onclick="loadModulos(${cursoId})">
                        <i class="fas fa-redo"></i> Reintentar
                    </button>
                </div>
            </div>
        `;
    });
}
// Función para cargar unidades de un módulo (sin cambios)
    function loadUnidades(moduloId) {
        const container = document.getElementById(`unidades-container-${moduloId}`);
        const url = `/admin/modulos/${moduloId}/unidades`;
        

        // Mostrar spinner
        container.innerHTML = `
            <div class="text-center py-1">
                <div class="spinner-border spinner-border-sm text-secondary" role="status">
                    <span class="visually-hidden">Cargando unidades...</span>
                </div>
                <span class="text-muted ms-2">Cargando unidades...</span>
            </div>
        `;

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {

            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }).catch(() => {
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(unidades => {
          
            
            // Verificar si es un error
            if (unidades.error) {
                throw new Error(unidades.message || unidades.error);
            }
            
            if (unidades.length === 0) {
                container.innerHTML = '<p class="text-muted mb-0"><small>No hay unidades formativas</small></p>';
            } else {
                // Ordenar unidades por código
                const unidadesOrdenadas = unidades.sort((a, b) => a.codigo.localeCompare(b.codigo));
                
                container.innerHTML = `
    <div class="unidades-container">
        <strong class="text-muted unidad-profesional">Unidades formativas:</strong>
        <ul class="list-group mt-1">
            ${unidadesOrdenadas.map(unidad => `
                <li class="list-group-item d-flex justify-content-between align-items-center py-2 unidad-profesional">
                    <span>
                        <strong>${unidad.codigo}</strong> - ${unidad.nombre}
                        <span class="badge bg-light text-dark badge-sm ms-2">${unidad.horas}h</span>
                    </span>
                    <form action="/admin/unidades/${unidad.id}" method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar unidad?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </li>
            `).join('')}
        </ul>
    </div>
`;
            }
        })
        .catch(error => {
            console.error('❌ Error cargando unidades:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar las unidades.
                    <br><small>${error.message}</small>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-warning" onclick="loadUnidades(${moduloId})">
                            <i class="fas fa-redo"></i> Reintentar
                        </button>
                    </div>
                </div>
            `;
        });
    }

function toggleSistemaSuscripciones(checkbox) {
    // Obtener el elemento de estado
    const estadoSpan = document.getElementById('estadoActual');
    
    // Mostrar estado temporal
    estadoSpan.textContent = 'CAMBIO...';
    estadoSpan.className = 'badge bg-warning ms-2';
    
    // Deshabilitar temporalmente
    checkbox.disabled = true;
    
    fetch('/admin/toggle-suscripciones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            activo: checkbox.checked
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar estado visual
            if (checkbox.checked) {
                estadoSpan.textContent = 'ACTIVO';
                estadoSpan.className = 'badge bg-success ms-2';
            } else {
                estadoSpan.textContent = 'INACTIVO';
                estadoSpan.className = 'badge bg-secondary ms-2';
            }
            
            // Mostrar notificación de éxito
            mostrarNotificacion('Sistema ' + (checkbox.checked ? 'activado' : 'desactivado') + ' correctamente', 'success');
            
            // Recargar después de 2 segundos para asegurar que todas las vistas se actualicen
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Error al actualizar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revertir el cambio
        checkbox.checked = !checkbox.checked;
        
        // Restaurar estado anterior
        if (checkbox.checked) {
            estadoSpan.textContent = 'ACTIVO';
            estadoSpan.className = 'badge bg-success ms-2';
        } else {
            estadoSpan.textContent = 'INACTIVO';
            estadoSpan.className = 'badge bg-secondary ms-2';
        }
        
        mostrarNotificacion('Error: ' + error.message, 'error');
    })
    .finally(() => {
        checkbox.disabled = false;
    });
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    notificacion.style.top = '20px';
    notificacion.style.right = '20px';
    notificacion.style.zIndex = '9999';
    notificacion.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notificacion);
    
    // Auto-eliminar después de 3 segundos
    setTimeout(() => {
        if (notificacion.parentNode) {
            notificacion.remove();
        }
    }, 3000);
}


</script>
@endsection