@extends('layouts.app')

@section('content')
<style>
    /* Copia los estilos de la vista original o hazlos globales */
    .header-curso {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        padding: 20px 0;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .btn-action {
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s;
        margin: 0 5px;
        border: none;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .table-custom {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        border: none;
        font-size: 0.85rem;
    }
    .table-custom thead {
        background: linear-gradient(135deg, #495057 0%, #343a40 100%);
        color: white;
    }
    .table-custom th {
        border: none;
        padding: 12px 10px;
        font-weight: 600;
        font-size: 0.8rem;
        white-space: nowrap;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 12px 10px;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(108, 117, 125, 0.03);
    }
    .badge-archivado {
        background-color: #6c757d;
        font-size: 0.75rem;
        padding: 0.4em 0.7em;
    }
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    .action-btn {
        padding: 7px 10px;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.2s;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .btn-restore {
        background-color: #28a745;
        color: white;
    }
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }
    .action-tooltip {
        position: relative;
    }
    .action-tooltip .tooltip-text {
        visibility: hidden;
        width: max-content;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 8px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.7rem;
        white-space: nowrap;
    }
    .action-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
    .curso-nombre {
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .docente-col {
        min-width: 120px;
    }
    .acciones-col {
        min-width: 140px;
    }
    .archivado-col {
        min-width: 100px;
    }
</style>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="header-curso text-center">
        <h1 class="mb-2">Cursos Archivados</h1>
        <p class="mb-0 opacity-75">Cursos archivados - Puedes restaurarlos o eliminarlos definitivamente</p>
    </div>

    <!-- Barra de acciones -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('academia.miscursos') }}" class="btn btn-primary btn-action">
                <i class="fas fa-arrow-left me-2"></i>Volver a Mis Cursos
            </a>
        </div>
        <div class="text-muted">
            Total: {{ $cursosArchivados->count() }} cursos archivados
        </div>
    </div>

    <!-- Tabla de cursos archivados -->
    @if($cursosArchivados && count($cursosArchivados) > 0)
    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-2"></i>Código</th>
                    <th><i class="fas fa-book me-2"></i>Curso</th>
                    <th><i class="fas fa-map-marker-alt me-2"></i>Municipio</th>
                    <th><i class="fas fa-map-marked-alt me-2"></i>Provincia</th>
                    <th><i class="fas fa-calendar-alt me-2"></i>Inicio</th>
                    <th><i class="fas fa-calendar-check me-2"></i>Fin</th>
                    <th><i class="fas fa-chalkboard-teacher me-2"></i>Docente</th>
                    <th class="archivado-col"><i class="fas fa-calendar me-2"></i>Archivado el</th>
                    <th class="acciones-col text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cursosArchivados as $cursoAcademico)
                <tr>
                    <td><strong>{{ $cursoAcademico->curso->codigo ?? 'N/A' }}</strong></td>
                    <td class="curso-nombre" title="{{ $cursoAcademico->curso->nombre ?? 'N/A' }}">
                        {{ $cursoAcademico->curso->nombre ?? 'N/A' }}
                    </td>
                    <td>{{ $cursoAcademico->municipio ?? 'N/A' }}</td>
                    <td>{{ $cursoAcademico->provincia ?? 'N/A' }}</td>
                    <td>
                        {{ $cursoAcademico->inicio ? \Carbon\Carbon::parse($cursoAcademico->inicio)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td>
                        {{ $cursoAcademico->fin ? \Carbon\Carbon::parse($cursoAcademico->fin)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="docente-col">
                        @php
                            $docente = $cursoAcademico->alumnos->where('es_profesor', 1)->first();
                        @endphp
                        @if($docente)
                            <span class="badge badge-docente">{{ $docente->nombre }}</span>
                        @else
                            <span class="badge bg-warning text-dark">Sin asignar</span>
                        @endif
                    </td>
                    <td class="archivado-col">
                        @if($cursoAcademico->archivado_en)
                            {{ \Carbon\Carbon::parse($cursoAcademico->archivado_en)->format('d/m/Y') }}
                        @else
                            <span class="badge badge-archivado">Archivado</span>
                        @endif
                    </td>
                    <td class="acciones-col">
                        <div class="action-buttons">
                            <!-- Botón para restaurar curso -->
                            <div class="action-tooltip">
                                <form action="{{ route('academia.curso_academico.restore', $cursoAcademico->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-restore action-btn" onclick="return confirm('¿Restaurar este curso?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                <span class="tooltip-text">Restaurar curso</span>
                            </div>
                            
                            <!-- Botón para eliminar definitivamente -->
                            <div class="action-tooltip">
                                <form action="{{ route('academia.curso_academico.destroy', $cursoAcademico->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete action-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar definitivamente este curso? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <span class="tooltip-text">Eliminar definitivamente</span>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state bg-light rounded-3 p-5">
        <i class="fas fa-archive"></i>
        <h3 class="text-muted">No hay cursos archivados</h3>
        <p class="text-muted">Los cursos que archives aparecerán aquí.</p>
        <a href="{{ route('academia.miscursos') }}" class="btn btn-primary mt-3">
            <i class="fas fa-arrow-left me-2"></i>Volver a Mis Cursos
        </a>
    </div>
    @endif
</div>
@endsection