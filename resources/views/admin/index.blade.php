@extends('layouts.app')

@section('styles')
<style>
/* Estilos para mejorar la jerarquía visual */
.curso-header {
    font-size: 1.1rem !important;
    padding: 0.75rem 1rem !important;
    cursor: pointer;
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
.familia-profesional { font-size: 1.2rem; font-weight: 600; }
.curso-profesional { font-size: 1rem; font-weight: 500; }
.modulo-profesional { font-size: 1rem; font-weight: 400; }
.unidad-profesional { font-size: 0.9rem; }
.accordion-button:not(.collapsed) {
    background-color: #e7f1ff;
}
.curso-item {
    transition: all 0.2s ease;
}
.curso-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.spinner-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem 0;
}
</style>
@endsection

@section('content')
<div class="container">
    <!-- Tarjeta de estadísticas -->
    <div class="card mb-3">
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

    <!-- Tarjeta de suscripciones -->
    <div class="card mb-3">
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

    <!-- Botones para crear -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearFamiliaModal">
            <i class="fas fa-plus"></i> Crear Familia Profesional
        </button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearCursoModal">
            <i class="fas fa-plus"></i> Crear Curso
        </button>
    </div>

    <!-- Acordeón de familias -->
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
                        <span class="badge bg-primary ms-3">{{ $familia->cursos_count ?? 0 }} cursos</span>
                    </span>
                </button>
            </h2>

            <div id="familiaCollapse{{ $familia->id }}" 
                 class="accordion-collapse collapse" 
                 aria-labelledby="familiaHeading{{ $familia->id }}" 
                 data-bs-parent="#familiasAccordion">
                <div class="accordion-body">
                    <!-- Usamos URL directa: /admin/familias/{id}/cursos -->
                    <div id="cursos-container-{{ $familia->id }}" 
                         class="cursos-container"
                         data-url="{{ url('/admin/familias/'.$familia->id.'/cursos') }}">
                        <div class="spinner-wrapper">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando cursos...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- ========================================== -->
<!-- MODALES GENÉRICOS (reutilizables)          -->
<!-- ========================================== -->

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
                        <label class="form-label">Cualificación</label>
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

<!-- Modal Editar Curso (genérico) -->
<div class="modal fade" id="editarCursoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editarCursoForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="curso_id" id="editCursoId">
                    <div class="mb-3">
                        <label class="form-label">Familia Profesional</label>
                        <select name="familia_profesional_id" id="editCursoFamilia" class="form-control" required>
                            @foreach($familiasProfesionales as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->codigo }} - {{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" id="editCursoCodigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="editCursoNombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cualificación</label>
                        <input type="text" name="cualificacion" id="editCursoCualificacion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horas totales</label>
                        <input type="number" name="horas" id="editCursoHoras" class="form-control">
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

<!-- Modal Agregar Módulo (genérico) -->
<div class="modal fade" id="agregarModuloModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="agregarModuloForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="curso_id" id="addModuloCursoId">
                    <div class="mb-3">
                        <label class="form-label">Código del Módulo</label>
                        <input type="text" name="codigo" id="addModuloCodigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Módulo</label>
                        <input type="text" name="nombre" id="addModuloNombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horas del Módulo</label>
                        <input type="number" name="horas" id="addModuloHoras" class="form-control" required>
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

<!-- Modal Agregar Unidad (genérico) -->
<div class="modal fade" id="agregarUnidadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="agregarUnidadForm" method="POST" action="{{ route('admin.unidades.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Unidad Formativa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="modulo_id" id="addUnidadModuloId">
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" id="addUnidadCodigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="addUnidadNombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horas</label>
                        <input type="number" name="horas" id="addUnidadHoras" class="form-control">
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

@endsection

@section('scripts')
    <script src="{{ asset('public/js/admin.js?v=' . time()) }}"></script>
@endsection