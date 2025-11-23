@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <!-- Header moderno -->
    <div class="page-header-modern bg-primary rounded-3 p-4 mb-4 shadow-sm">
        <h1 class="text-white mb-2">Cursos Disponibles para Asignar</h1>
        <p class="text-white-50 mb-0">Explora y selecciona entre todas las opciones formativas disponibles</p>
    </div>

    <!-- Listado de Familias Profesionales con Cursos y Módulos -->
    <div class="accordion" id="familiasAccordion">
        @foreach($familias_profesionales as $index => $familia)
        <div class="card family-card-modern mb-3">
            <div class="card-header family-header-modern p-0" id="familiaHeading{{ $familia->id }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center p-3 w-100 collapsed" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#familiaCollapse{{ $familia->id }}" 
                            aria-expanded="false" 
                            aria-controls="familiaCollapse{{ $familia->id }}">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-folder-open text-primary me-2"></i>
                            <span>
                                <strong class="family-code-modern">{{ $familia->codigo }}</strong> - {{ $familia->nombre }}
                            </span>
                        </span>
                        <span class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-2">{{ $familia->cursos->count() }} cursos</span>
                            <i class="fas fa-chevron-down accordion-icon-modern"></i>
                        </span>
                    </button>
                </h2>
            </div>

            <div id="familiaCollapse{{ $familia->id }}" class="collapse" 
                 aria-labelledby="familiaHeading{{ $familia->id }}" 
                 data-bs-parent="#familiasAccordion">
                <div class="card-body p-3">
                    @foreach($familia->cursos as $curso)
                    <div class="card course-card-modern mb-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-1">
                                        <span class="course-code-modern">{{ $curso->codigo }}</span> - {{ $curso->nombre }}
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="far fa-clock me-1"></i> {{ $curso->horas }} horas
                                        @if(isset($curso->modulos) && $curso->modulos->count() > 0)
                                        <span class="ms-2">
                                            <i class="fas fa-layer-group me-1"></i> {{ $curso->modulos->count() }} módulos
                                        </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                    <form action="{{ Auth::user()->rol == 'academia' 
                                        ? route('academia.asignar_curso', $curso->id) 
                                        : route('profesor.asignar_curso', $curso->id) }}" 
                                    method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-modern-action">
                                        <i class="fas fa-plus-circle me-1"></i> Asignar Curso
                                    </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Botón Volver -->
    <div class="text-center mt-4 pt-3 border-top">
        @if(Auth::user()->rol === 'academia')
            <a href="{{ route('academia.miscursos') }}" class="btn btn-secondary btn-modern-back">
                <i class="fas fa-arrow-left me-2"></i> Volver a Mis Cursos
            </a>
        @elseif(Auth::user()->rol === 'profesor')
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

<style>
    .page-header-modern {
        background: linear-gradient(135deg, #4361ee, #3a0ca3) !important;
    }
    
    .family-card-modern {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .family-card-modern:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    }
    
    .family-header-modern {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        cursor: pointer;
    }
    
    .family-header-modern .btn-link {
        text-decoration: none;
        color: #333;
        font-weight: 600;
        transition: all 0.3s ease;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
    }
    
    .family-header-modern .btn-link:hover {
        color: #4361ee;
    }
    
    .family-header-modern .btn-link:not(.collapsed) {
        color: #4361ee;
        background-color: rgba(67, 97, 238, 0.05);
        box-shadow: none;
    }
    
    .family-header-modern .btn-link:focus {
        box-shadow: none;
        outline: none;
    }
    
    .family-code-modern {
        color: #4361ee;
    }
    
    .accordion-icon-modern {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }
    
    .family-header-modern .btn-link:not(.collapsed) .accordion-icon-modern {
        transform: rotate(180deg);
    }
    
    .course-card-modern {
        border: none;
        border-radius: 8px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-left: 3px solid #4361ee;
    }
    
    .course-card-modern:hover {
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .course-code-modern {
        color: #4361ee;
        font-weight: 600;
    }
    
    .btn-modern-action {
        background-color: #4361ee;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-modern-action:hover {
        background-color: #3a56d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
    }
    
    .btn-modern-back {
        border-radius: 6px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }
    
    .empty-state-modern {
        border: 2px dashed #dee2e6;
    }
    
    /* Asegurar que el acordeón funcione correctamente */
    .accordion > .card {
        overflow: visible;
    }
    
    .accordion > .card > .card-header {
        margin-bottom: 0;
        border-radius: 0.375rem !important;
    }
    
    .collapse {
        visibility: visible !important;
    }
    
    @media (max-width: 768px) {
        .course-card-modern .card-body .row {
            flex-direction: column;
            text-align: center;
        }
        
        .course-card-modern .card-body .col-md-4 {
            margin-top: 1rem;
        }
        
        .family-header-modern .btn-link {
            padding: 1rem 0.75rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando acordeón...');
    
    // Inicializar manualmente el acordeón de Bootstrap
    const accordionButtons = document.querySelectorAll('.family-header-modern .btn-link');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Botón de acordeón clickeado');
            
            // Obtener el target del collapse
            const targetId = this.getAttribute('data-bs-target');
            const targetCollapse = document.querySelector(targetId);
            
            if (targetCollapse) {
                // Usar la API de Bootstrap Collapse
                const collapse = new bootstrap.Collapse(targetCollapse, {
                    toggle: true
                });
            }
            
            // Actualizar el ícono
            const icon = this.querySelector('.accordion-icon-modern');
            if (this.classList.contains('collapsed')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });
        
        // Asegurar que el estado inicial sea correcto
        const icon = button.querySelector('.accordion-icon-modern');
        if (button.classList.contains('collapsed')) {
            icon.style.transform = 'rotate(0deg)';
        } else {
            icon.style.transform = 'rotate(180deg)';
        }
    });
    
    // Debug: Verificar que Bootstrap esté cargado
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap cargado correctamente');
    } else {
        console.error('Bootstrap no está cargado');
    }
});
</script>
@endsection