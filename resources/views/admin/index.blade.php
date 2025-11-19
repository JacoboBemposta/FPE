@extends('layouts.app')

@section('content')
<div class="container">
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
                aria-controls="familiaCollapse{{ $familia->id }}">
                <span class="d-flex justify-content-between align-items-center w-100">
                    <span>
                        <strong>{{ $familia->codigo }}</strong> - {{ $familia->nombre }}
                    </span>
                    <span class="badge bg-primary ms-3">{{ $familia->cursos->count() }} cursos</span>
                </span>
            </button>
        </h2>

        <div id="familiaCollapse{{ $familia->id }}" 
             class="accordion-collapse collapse" 
             aria-labelledby="familiaHeading{{ $familia->id }}" 
             data-bs-parent="#familiasAccordion">
            <div class="accordion-body">
                @if($familia->cursos->isEmpty())
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-info-circle"></i> No hay cursos en esta familia profesional.
                    </div>
                @else
                    <!-- CONTENIDO DE CURSOS - SIN ACORDDIONS ANIDADOS -->
                    <div class="cursos-list">
                        @foreach($familia->cursos as $curso)
                        <div class="curso-item card mb-3" data-curso-id="{{ $curso->id }}">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-book me-2"></i>
                                        <strong>{{ $curso->codigo }}</strong> - {{ $curso->nombre }} 
                                        <span class="badge bg-secondary ms-2">{{ $curso->horas }}h</span>
                                    </h5>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editarCursoModal{{ $curso->id }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form action="{{ route('admin.cursos.destroy', $curso->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
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
                                    <span class="badge bg-info">{{ $curso->modulos->count() }}</span>
                                </h6>
                                
                                @if($curso->modulos->count() > 0)
                                    <!-- MÓDULOS COMO LISTA SIMPLE - SIN ACORDDION -->
                                    <div class="modulos-list">
                                        @foreach($curso->modulos as $modulo)
                                        <div class="modulo-item border rounded p-3 mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>
                                                    {{ $modulo->codigo }} - {{ $modulo->nombre }}
                                                    <span class="badge bg-light text-dark ms-2">{{ $modulo->horas }}h</span>
                                                </strong>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#agregarUnidadModal{{ $modulo->id }}">
                                                        <i class="fas fa-plus"></i> Unidad
                                                    </button>
                                                    <form action="{{ route('admin.cursos.modulos.destroy', ['curso' => $curso->id, 'modulo' => $modulo->id]) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar módulo?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <!-- UNIDADES -->
                                            @if($modulo->unidades->count() > 0)
                                                <div class="unidades-list">
                                                    <strong class="text-muted">Unidades:</strong>
                                                    <ul class="list-group mt-1">
                                                        @foreach($modulo->unidades->sortBy([['codigo'], ['nombre']]) as $unidad)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center py-1">
                                                            <span>
                                                                <strong>{{ $unidad->codigo }}</strong> - {{ $unidad->nombre }}
                                                                <span class="badge bg-light text-dark ms-2">{{ $unidad->horas }}h</span>
                                                            </span>
                                                            <form action="{{ route('admin.unidades.destroy', $unidad->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar unidad?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <p class="text-muted mb-0"><small>No hay unidades formativas</small></p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Este curso no tiene módulos.</p>
                                @endif

                                <div class="mt-3">
                                    <button class="btn btn-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#agregarModuloModal{{ $curso->id }}">
                                        <i class="fas fa-plus"></i> Añadir Módulo
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Inicializando componentes Bootstrap...');
    
    // Pequeño delay para asegurar que el DOM esté completamente renderizado
    setTimeout(function() {
        if (typeof bootstrap !== 'undefined') {
            console.log('✅ Bootstrap disponible');
            
            // Inicializar collapses
            const collapses = document.querySelectorAll('.accordion-collapse');
            collapses.forEach(collapse => {
                try {
                    new bootstrap.Collapse(collapse, { toggle: false });
                } catch (e) {
                    console.log('Error inicializando collapse:', e);
                }
            });
            
            // Inicializar modales
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                try {
                    new bootstrap.Modal(modal);
                } catch (e) {
                    console.log('Error inicializando modal:', e);
                }
            });
            
            console.log(`✅ ${collapses.length} collapses y ${modals.length} modales inicializados`);
        } else {
            console.error('❌ Bootstrap no disponible');
        }
    }, 100);
});
</script>

<!-- Script para forzar el comportamiento correcto de los accordions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Forzando comportamiento correcto de accordions...');
    
    // Esperar a que Bootstrap esté completamente cargado
    setTimeout(function() {
        if (typeof bootstrap !== 'undefined') {
            // 1. Cerrar TODOS los accordions al inicio
            const allCollapses = document.querySelectorAll('.accordion-collapse');
            allCollapses.forEach(collapse => {
                const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                } else {
                    new bootstrap.Collapse(collapse, { toggle: false }).hide();
                }
            });
            
            // 2. Asegurar que los botones tengan la clase 'collapsed'
            const allButtons = document.querySelectorAll('.accordion-button');
            allButtons.forEach(button => {
                button.classList.add('collapsed');
                button.setAttribute('aria-expanded', 'false');
            });
            
            console.log('✅ Accordions forzados a estado colapsado');
            
            // 3. Agregar event listeners para debug
            document.querySelectorAll('.accordion-button').forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-bs-target');
                    console.log('🔍 Click en accordion:', target);
                });
            });
        }
    }, 500);
});

// Script adicional para manejar problemas de renderizado
window.addEventListener('load', function() {
    console.log('🔄 Página completamente cargada - Verificando accordions...');
    
    // Verificar que todos los accordions estén colapsados
    const visibleCollapses = document.querySelectorAll('.accordion-collapse.show');
    console.log(`📊 Accordions visibles: ${visibleCollapses.length}`);
    
    if (visibleCollapses.length > 0) {
        console.log('⚠️  Algunos accordions están visibles cuando deberían estar ocultos');
        // Forzar a ocultar
        visibleCollapses.forEach(collapse => {
            collapse.classList.remove('show');
            collapse.style.display = 'none';
        });
    }
});
</script>

@endsection