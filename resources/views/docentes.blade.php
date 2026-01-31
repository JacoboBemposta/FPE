@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-6">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4" style="color: #3a0ca3;">
                Para <span style="background: linear-gradient(135deg, #4361ee, #3a0ca3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Docentes</span>
            </h1>
            <p class="lead fs-4 text-dark mb-4">
                Encuentra oportunidades de enseñanza y conecta con academias
            </p>
            <p class="text-dark mb-4">
                RedFPE es la plataforma que conecta docentes con academias que buscan profesionales como tú. 
                <strong class="text-primary">Actualmente gratuita para todos los docentes.</strong> 
                Crea tu perfil y empieza a recibir ofertas sin coste.
            </p>
            <div class="d-flex flex-wrap gap-3">
                @auth
                    @if(Auth::user()->rol === 'profesor')
                        <a href="{{ route('profesor.miscursos') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i>Ir a mi Panel
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Crear Perfil de Docente
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="fas fa-rocket me-2"></i>Comenzar Gratis
                    </a>
                @endauth
            </div>
        </div>

    </div>

    <!-- Estadísticas -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #4361ee;">Docentes en RedFPE</h2>
            <p class="text-muted lead">Únete a la comunidad educativa en crecimiento</p>
        </div>
        
        {{-- <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #4361ee;">+200</h2>
                <p class="text-muted mb-0">Docentes activos</p>
            </div>
        </div>
        
        <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #3a0ca3;">+50</h2>
                <p class="text-muted mb-0">Academias registradas</p>
            </div>
        </div>
        
        <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #4cc9f0;">+300</h2>
                <p class="text-muted mb-0">Ofertas mensuales</p>
            </div>
        </div>
        
        <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #7209b7;">€0</h2>
                <p class="text-muted mb-0">Coste actual</p>
            </div>
        </div>
    </div> --}}

    <!-- Beneficios Principales -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #3a0ca3;">¿Por qué usar RedFPE?</h2>
            <p class="text-muted lead">Beneficios para profesionales de la enseñanza</p>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4 hover-lift">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-briefcase fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Encuentra Oportunidades</h4>
                </div>
                <p class="text-muted">
                    Accede a ofertas de trabajo de academias en tu zona que buscan docentes con tu perfil.
                </p>
                <ul class="text-muted">
                    <li>Ofertas de academias locales</li>
                    <li>Filtra por especialidad y ubicación</li>
                    <li>Postula directamente a las ofertas</li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4 hover-lift">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Perfil Profesional</h4>
                </div>
                <p class="text-muted">
                    Crea un perfil atractivo que muestre tus certificaciones.
                </p>
                <ul class="text-muted">
                    <li>Asigna tus certificados de profesionalidad</li>
                    <li>Destaca tu experiencia</li>
                    <li>Personaliza tu perfil</li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4 hover-lift">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-handshake fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Conexión Directa</h4>
                </div>
                <p class="text-muted">
                    Contacta directamente con academias sin intermediarios ni comisiones.
                </p>
                <ul class="text-muted">
                    <li>Comunicación directa</li>
                    <li>Sin comisiones por contratación</li>
                    <li>Contacto transparente</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Funcionalidades Detalladas -->
    <div class="row mb-6 align-items-center">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg p-5" style="border-radius: 20px;">
                <h3 class="fw-bold mb-4" style="color: #4361ee;">
                    <i class="fas fa-list-check me-2"></i>Funcionalidades para Docentes
                </h3>
                
                <div class="accordion" id="featuresAccordion">
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature1">
                                <i class="fas fa-user-edit me-3 text-primary"></i>
                                <strong>Creación de Perfil</strong>
                            </button>
                        </h2>
                        <div id="feature1" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Crea un perfil completo con tus especialidades. Asigna los certificados de profesionalidad que puedes impartir directamente desde tu panel.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature2">
                                <i class="fas fa-search me-3 text-primary"></i>
                                <strong>Búsqueda de Ofertas</strong>
                            </button>
                        </h2>
                        <div id="feature2" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Explora ofertas de trabajo publicadas por academias. Filtra por ubicación, tipo de curso y nivel.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature3">
                                <i class="fas fa-paper-plane me-3 text-primary"></i>
                                <strong>Postulación a Cursos</strong>
                            </button>
                        </h2>
                        <div id="feature3" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Postula directamente a los cursos que te interesen. Envía tu perfil a las academias y espera su respuesta.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature4">
                                <i class="fas fa-comments me-3 text-primary"></i>
                                <strong>Comunicación Directa</strong>
                            </button>
                        </h2>
                        <div id="feature4" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Comunícate directamente con las academias a través de la plataforma para resolver dudas, concertar entrevistas o discutir detalles de los cursos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg p-5 text-center" style="border-radius: 20px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="text-white">
                    <i class="fas fa-chalkboard-teacher fa-4x mb-4"></i>
                    <h3 class="fw-bold mb-3">Versión Actual Gratuita</h3>
                    <p class="mb-4" style="opacity: 0.9;">Disfruta de todas las funcionalidades sin coste</p>
                    
                    <div class="text-start mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Creación y gestión de perfil</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Acceso a todas las ofertas</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Postulación ilimitada a cursos</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Comunicación directa con academias</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Soporte básico incluido</span>
                        </div>
                    </div>
                    
                    <div class="bg-white text-dark p-3 rounded-3 mb-3">
                        <h2 class="fw-bold mb-0">Gratuita</h2>
                        <small class="text-muted">Versión actual - Sin suscripciones</small>
                    </div>
                    
                    <div class="alert alert-light mt-3" style="border-left: 3px solid #4cc9f0;">
                        <small class="text-dark">
                            <i class="fas fa-info-circle me-1"></i> 
                            <strong>Nota:</strong> En el futuro podríamos implementar un sistema de suscripciones 
                            para funcionalidades premium, pero los docentes actuales tendrán condiciones especiales de transición.
                        </small>
                    </div>
                    
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 mt-3">
                        <i class="fas fa-rocket me-2"></i>Crear Perfil Gratis
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cómo Funciona -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #3a0ca3;">¿Cómo Funciona?</h2>
            <p class="text-muted lead">4 pasos sencillos para empezar</p>
        </div>
        
        <div class="col-lg-10 mx-auto">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-3">1</span>
                        </div>
                        <h5 class="fw-bold mb-2">Regístrate</h5>
                        <p class="text-muted small">Crea tu cuenta gratuita como docente</p>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-3">2</span>
                        </div>
                        <h5 class="fw-bold mb-2">Completa tu perfil</h5>
                        <p class="text-muted small">Asigna tus certificados de profesionalidad</p>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-3">3</span>
                        </div>
                        <h5 class="fw-bold mb-2">Busca ofertas</h5>
                        <p class="text-muted small">Explora cursos disponibles en tu zona</p>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-3">4</span>
                        </div>
                        <h5 class="fw-bold mb-2">Postúlate</h5>
                        <p class="text-muted small">Contacta con academias y consigue trabajo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Preguntas Frecuentes -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #4361ee;">Preguntas Frecuentes</h2>
            <p class="text-muted lead">Todo lo que necesitas saber sobre RedFPE para docentes</p>
        </div>
        
        <div class="col-lg-8 mx-auto">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            <strong>¿Es gratuita actualmente?</strong>
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Sí, RedFPE es completamente gratuita para docentes en su versión actual. 
                            No hay suscripciones, comisiones por contratación ni costes ocultos.</p>
                            <div class="alert alert-info mt-3 mb-0 p-3">
                                <small><i class="fas fa-info-circle me-1"></i> 
                                <strong>Nota sobre el futuro:</strong> Podríamos implementar un sistema de suscripciones 
                                para funcionalidades premium en el futuro, pero los docentes que se registren ahora 
                                tendrán condiciones especiales de transición.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            <strong>¿Cómo funcionan los certificados de profesionalidad?</strong>
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Los docentes pueden asignarse los certificados de profesionalidad 
                            que están autorizados a impartir directamente desde su panel de control. 
                            Las academias podrán ver estos certificados en tu perfil y contactar contigo 
                            si buscan docentes con esas especialidades.</p>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            <strong>¿Qué pasa si en el futuro implementan suscripciones?</strong>
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Si en el futuro implementamos un sistema de suscripciones, los docentes que ya estén 
                            registrados podrán seguir recibiendo ofertas de empleo por parte de las academias, ya que el perfil seguirá siendo público.</p>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item border-0 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            <strong>¿Hay algún tipo de verificación?</strong>
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Actualmente no realizamos verificaciones exhaustivas de los docentes. 
                            Las academias son responsables de verificar la documentación y experiencia de los docentes 
                            antes de contratarlos. Te recomendamos mantener tu perfil actualizado y veraz para 
                            aumentar tus oportunidades de contratación.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Final -->
    <div id="contacto" class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-5 text-center" style="border-radius: 20px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold mb-3" style="color: #3a0ca3;">¿Listo para empezar?</h2>
                <p class="text-dark mb-4">
                    Únete ahora mientras la plataforma es completamente gratuita. 
                    Regístrate hoy y empieza a conectar con academias sin coste.
                </p>
                
                <div class="alert alert-warning mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-3 fa-lg text-warning"></i>
                        <div>
                            <h5 class="mb-1">¡Oferta actual!</h5>
                            <p class="mb-0">Plataforma 100% gratuita para docentes. Aprovecha esta oportunidad mientras dura.</p>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6">
                        <div class="bg-white p-4 rounded-3 text-center">
                            <i class="fas fa-question-circle fa-3x mb-3" style="color: #4361ee;"></i>
                            <h5 class="fw-bold mb-2">¿Tienes dudas?</h5>
                            <p class="text-muted small mb-3">Consulta nuestras preguntas frecuentes</p>
                            <a href="#faqAccordion" class="btn btn-outline-primary scroll-to-faq">
                                <i class="fas fa-info-circle me-2"></i>Ver FAQs
                            </a>
                        </div>
                    </div>
                    

                </div>
                
                <div class="mt-5">
                    <p class="text-muted mb-3">Crea tu perfil ahora y empieza a encontrar oportunidades:</p>
                    @auth
                        @if(Auth::user()->rol === 'profesor')
                            <a href="{{ route('profesor.miscursos') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-tachometer-alt me-2"></i>Ir a mi Panel
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-user-plus me-2"></i>Crear Perfil de Docente
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-rocket me-2"></i>Registrarme Gratis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1) !important;
    }
    
    .accordion-button {
        background-color: rgba(67, 97, 238, 0.05);
        border: none;
        font-weight: 500;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(67, 97, 238, 0.1);
        color: #4361ee;
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(67, 97, 238, 0.1);
    }
    
    .accordion-body {
        background-color: rgba(67, 97, 238, 0.02);
    }
    
    .rounded-3 {
        border-radius: 1rem !important;
    }
    
    .mb-6 {
        margin-bottom: 5rem !important;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .badge.bg-info {
        background-color: #4cc9f0 !important;
        color: #000;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación para las tarjetas al hacer scroll
        const cards = document.querySelectorAll('.card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
        
        // Scroll suave para FAQs
        document.querySelectorAll('.scroll-to-faq').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const faqSection = document.querySelector('#faqAccordion');
                if (faqSection) {
                    window.scrollTo({
                        top: faqSection.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endsection