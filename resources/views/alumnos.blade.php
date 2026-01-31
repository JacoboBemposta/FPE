@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Encabezado con gradiente -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10 text-center">
            <div class="bg-gradient-primary text-white rounded-4 p-5 shadow-lg mb-4">
                <div class="d-flex align-items-center justify-content-center mb-4 flex-column flex-md-row">
                    <div class="bg-white rounded-circle p-4 me-3">
                        <i class="fas fa-user-graduate fa-3x text-primary"></i>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold mb-2">Para Alumnos</h1>
                        <div class="badge bg-light text-primary px-4 py-2 mb-3 fs-5">
                            <i class="fas fa-crown me-2"></i>Suscripción Siempre Gratuita
                        </div>
                    </div>
                </div>
                <p class="lead mb-4" style="opacity: 0.9;">
                    Accede a educación de calidad sin costos. <br>Encuentra los mejores cursos,
                    conecta con profesionales y avanza en tu carrera.
                </p>
            </div>
        </div>
    </div>

    <!-- Tarjetas de beneficios -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 h-100 shadow-sm hover-shadow">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper bg-gradient-accent rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-graduation-cap fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Cursos Ilimitados</h4>
                    <p class="text-muted">
                        Accede a todos los cursos disponibles sin restricciones. 
                        Desde tecnología hasta humanidades, elige lo que necesitas.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 h-100 shadow-sm hover-shadow">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper bg-gradient-primary rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-certificate fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Certificaciones</h4>
                    <p class="text-muted">
                        Obtén certificados reconocidos al completar tus cursos. 
                        Mejora tu currículum y destaca en el mercado laboral.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 h-100 shadow-sm hover-shadow">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper bg-success rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-handshake fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Conexiones</h4>
                    <p class="text-muted">
                        Conéctate con profesionales, compañeros y profesores. 
                        Construye tu red profesional mientras aprendes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de gratuidad -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <div class="p-4 p-lg-5">
                <h2 class="fw-bold mb-4">¿Por qué es gratuita para alumnos?</h2>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <i class="fas fa-check-circle text-success me-3 mt-1 fs-5"></i>
                        <div>
                            <h5 class="fw-bold">Compromiso con la educación</h5>
                            <p class="text-muted mb-0">Creemos que la educación debe ser accesible para todos.</p>
                        </div>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-check-circle text-success me-3 mt-1 fs-5"></i>
                        <div>
                            <h5 class="fw-bold">Modelo sostenible</h5>
                            <p class="text-muted mb-0">Las academias y profesores mantienen la plataforma activa.</p>
                        </div>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-check-circle text-success me-3 mt-1 fs-5"></i>
                        <div>
                            <h5 class="fw-bold">Enfoque en el aprendizaje</h5>
                            <p class="text-muted mb-0">Te concentras en aprender sin preocupaciones económicas.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="bg-gradient-accent text-white rounded-4 p-5 shadow-lg">
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white rounded-circle p-3 me-3">
                        <i class="fas fa-lock fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-2">Totalmente Seguro</h3>
                        <p class="mb-0" style="opacity: 0.9;">
                            Tu privacidad está protegida. No compartimos tus datos con terceros 
                            y no usamos cookies de seguimiento.
                        </p>
                    </div>
                </div>
                <div class="alert alert-light border-0 mt-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-3 fa-lg"></i>
                        <div>
                            <strong>Recordatorio importante:</strong> La suscripción gratuita 
                            para alumnos es un compromiso permanente de RedFPE.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA para registrarse -->
    <div class="text-center bg-light rounded-4 p-5 mb-5">
        <h2 class="fw-bold mb-4">Comienza tu viaje de aprendizaje</h2>
        <p class="lead text-muted mb-5">
            Únete a miles de alumnos que ya están avanzando en sus carreras con RedFPE.
        </p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3 me-3">
                <i class="fas fa-user-plus me-2"></i>Regístrate Gratis
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </a>
        @else
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-5 py-3 me-3">
                <i class="fas fa-rocket me-2"></i>Explorar Cursos
            </a>
            @if(Auth::user()->rol === 'alumno')
                <a href="{{ route('alumno.dashboard') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                    <i class="fas fa-tachometer-alt me-2"></i>Mi Panel
                </a>
            @endif
        @endguest
    </div>

    <!-- Preguntas frecuentes -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h3 class="fw-bold text-center mb-5">Preguntas Frecuentes</h3>
            <div class="accordion shadow-sm" id="faqAccordion">
                <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq1">
                            <strong>¿Realmente es gratis para siempre?</strong>
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sí, los alumnos siempre tendrán acceso gratuito a RedFPE. 
                            Nuestro modelo se sustenta a través de las suscripciones de 
                            academias y profesores que utilizan la plataforma para gestionar 
                            sus cursos y estudiantes.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq2">
                            <strong>¿Necesito tarjeta de crédito para registrarme?</strong>
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            No, no necesitas tarjeta de crédito ni realizar ningún pago. 
                            El registro como alumno es completamente gratuito y sin 
                            requisitos de pago.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq3">
                            <strong>¿Qué tipo de cursos puedo encontrar?</strong>
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Encontrarás cursos de diversas áreas: programación, diseño, 
                            marketing, negocios, idiomas, ciencias, humanidades y más. 
                            Las academias y profesores publican cursos constantemente, 
                            por lo que siempre hay nuevo contenido disponible.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
    }
    
    .bg-gradient-accent {
        background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%) !important;
    }
    
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(67, 97, 238, 0.25);
    }
</style>

@endsection