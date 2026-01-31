@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-6">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4" style="color: #3a0ca3;">
                Para <span style="background: linear-gradient(135deg, #4361ee, #3a0ca3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Academias</span>
            </h1>
            <p class="lead fs-4 text-dark mb-4">
                Conecta con docentes y gestiona tus cursos de forma eficiente
            </p>
            <p class="text-dark mb-4">100%
                RedFPE es la plataforma que facilita la conexión entre academias y docentes. 
                <strong class="text-primary">Actualmente gratuita para todas las academias.</strong>
                Disfruta de todas las funcionalidades sin coste.
            </p>
            <div class="d-flex flex-wrap gap-3">
                @auth
                    @if(Auth::user()->rol === 'academia')
                        <a href="{{ route('academia.miscursos') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i>Ir a mi Panel
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-university me-2"></i>Crear Cuenta de Academia
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
            <h2 class="fw-bold mb-3" style="color: #4361ee;">Academias en RedFPE</h2>
            <p class="text-muted lead">Únete a la comunidad educativa en crecimiento</p>
        </div>
        
        {{-- <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #4361ee;">+50</h2>
                <p class="text-muted mb-0">Academias registradas</p>
            </div>
        </div>
        
        <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #3a0ca3;">+200</h2>
                <p class="text-muted mb-0">Docentes disponibles</p>
            </div>
        </div>
        
        <div class="col-md-3 col-6 text-center mb-4">
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold display-5 mb-2" style="color: #4cc9f0;">+1,000</h2>
                <p class="text-muted mb-0">Alumnos potenciales</p>
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
            <p class="text-muted lead">Beneficios reales para tu academia</p>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4 hover-lift">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Conexión Directa</h4>
                </div>
                <p class="text-muted">
                    Contacta directamente con docentes que buscan oportunidades en tu zona.
                </p>
                <ul class="text-muted">
                    <li>Busca docentes por ubicación</li>
                    <li>Visualiza sus certificaciones</li>
                    <li>Comunícate de forma directa</li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4 hover-lift">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Gestión Sencilla</h4>
                </div>
                <p class="text-muted">
                    Crea y gestiona tus cursos desde un panel intuitivo y fácil de usar.
                </p>
                <ul class="text-muted">
                    <li>Crea cursos en segundos</li>
                    <li>Gestiona inscripciones</li>
                    <li>Asigna docentes fácilmente</li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4 hover-lift">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-wallet fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Sin Costes Iniciales</h4>
                </div>
                <p class="text-muted">
                    Plataforma completamente gratuita en su versión actual. Sin suscripciones obligatorias.
                </p>
                <ul class="text-muted">
                    <li>Registro gratuito</li>
                    <li>Sin límite de cursos</li>
                    <li>Funcionalidades completas</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Funcionalidades Detalladas -->
    <div class="row mb-6 align-items-center">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg p-5" style="border-radius: 20px;">
                <h3 class="fw-bold mb-4" style="color: #4361ee;">
                    <i class="fas fa-list-check me-2"></i>Funcionalidades Clave
                </h3>
                
                <div class="accordion" id="featuresAccordion">
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature1">
                                <i class="fas fa-book me-3 text-primary"></i>
                                <strong>Creación de Cursos</strong>
                            </button>
                        </h2>
                        <div id="feature1" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Publica tus cursos fácilmente: define fechas y detalles específicos. Los docentes podrán ver tus ofertas y postularse.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature2">
                                <i class="fas fa-user-tie me-3 text-primary"></i>
                                <strong>Búsqueda de Docentes</strong>
                            </button>
                        </h2>
                        <div id="feature2" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Explora docentes disponibles en tu área, revisa las certificaciones de profesionalidad que tienen asignadas y contacta con ellos directamente.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#feature3">
                                <i class="fas fa-users me-3 text-primary"></i>
                                <strong>Gestión de Inscripciones</strong>
                            </button>
                        </h2>
                        <div id="feature3" class="accordion-collapse collapse" data-bs-parent="#featuresAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">Administra las inscripciones de alumnos, mantén un registro organizado y facilita el proceso de matrícula.</p>
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
                                <p class="mb-0">Comunícate directamente con docentes y alumnos a través de la plataforma para coordinar detalles y resolver dudas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg p-5 text-center" style="border-radius: 20px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="text-white">
                    <i class="fas fa-graduation-cap fa-4x mb-4"></i>
                    <h3 class="fw-bold mb-3">Versión Actual Gratuita</h3>
                    <p class="mb-4" style="opacity: 0.9;">Disfruta de todas las funcionalidades sin coste</p>
                    
                    <div class="text-start mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Acceso completo a docentes</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Creación ilimitada de cursos</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Gestión de inscripciones</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span>Comunicación directa</span>
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
                            para funcionalidades premium, pero las academias actuales tendrán condiciones especiales.
                        </small>
                    </div>
                    
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 mt-3">
                        <i class="fas fa-rocket me-2"></i>Empezar Ahora
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Futuras Mejoras -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #3a0ca3;">Visión Futura</h2>
            <p class="text-muted lead">Posibles mejoras que podrían implementarse</p>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Analíticas Avanzadas</h5>
                </div>
                <p class="text-muted">
                    Dashboards detallados con métricas de rendimiento, ocupación de cursos y análisis de tendencias.
                </p>
                <div class="mt-3">
                    <span class="badge bg-info">Posible funcionalidad premium</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-file-invoice-dollar fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Facturación Integrada</h5>
                </div>
                <p class="text-muted">
                    Sistema completo de facturación automática, gestión de pagos y sincronización con contabilidad.
                </p>
                <div class="mt-3">
                    <span class="badge bg-info">Posible funcionalidad premium</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-headset fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Soporte Prioritario</h5>
                </div>
                <p class="text-muted">
                    Atención personalizada, tiempos de respuesta garantizados y asistencia técnica avanzada.
                </p>
                <div class="mt-3">
                    <span class="badge bg-info">Posible funcionalidad premium</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Preguntas Frecuentes -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #4361ee;">Preguntas Frecuentes</h2>
            <p class="text-muted lead">Todo lo que necesitas saber sobre RedFPE para academias</p>
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
                            <p class="mb-0">Sí, RedFPE es completamente gratuita para academias y docentes en su versión actual. 
                            No hay suscripciones, comisiones por contratación ni costes ocultos. Es una plataforma creada por 
                            docentes para facilitar la conexión en el sector educativo.</p>
                            <div class="alert alert-info mt-3 mb-0 p-3">
                                <small><i class="fas fa-info-circle me-1"></i> 
                                <strong>Nota sobre el futuro:</strong> Podríamos implementar un sistema de suscripciones 
                                para funcionalidades premium en el futuro, pero las academias que se registren ahora 
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
                            <p class="mb-0">Los docentes se registran en la plataforma y se asignan manualmente 
                            los certificados de profesionalidad que están autorizados a impartir. Las academias pueden ver estos 
                            certificados en el perfil de cada docente y contactar con ellos directamente.</p>
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
                            <p class="mb-0">Si en el futuro implementamos un sistema de suscripciones, las academias que ya estén 
                            registradas recibirán:</p>
                            <ul class="mb-0">
                                <li>Un período de transición generoso</li>
                                <li>Condiciones preferenciales como usuarios pioneros</li>
                                <li>La opción de mantener funcionalidades básicas gratuitas</li>
                                <li>Comunicación transparente con mucha antelación</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item border-0 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            <strong>¿Hay algún tipo de verificación de docentes?</strong>
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Actualmente no realizamos verificaciones exhaustivas de los docentes. 
                            Las academias deben realizar sus propias comprobaciones y entrevistas antes de contratar. 
                            La plataforma solo facilita el contacto inicial y muestra las certificaciones asignadas 
                            por los administradores.</p>
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
                    Regístrate hoy y disfruta de todas las funcionalidades sin coste.
                </p>
                
                <div class="alert alert-warning mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-3 fa-lg text-warning"></i>
                        <div>
                            <h5 class="mb-1">¡Oferta actual!</h5>
                            <p class="mb-0">Plataforma 100% gratuita. Aprovecha esta oportunidad mientras dura.</p>
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
                    
                    {{-- <div class="col-md-6">
                        <div class="bg-white p-4 rounded-3 text-center">
                            <i class="fas fa-envelope fa-3x mb-3" style="color: #4361ee;"></i>
                            <h5 class="fw-bold mb-2">Contacto</h5>
                            <p class="text-muted small mb-3">Para academias y centros educativos</p>
                            <a href="mailto:contacto@redfpe.com" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Enviar Email
                            </a>
                        </div>
                    </div> --}}
                </div>
                
                <div class="mt-5">
                    <p class="text-muted mb-3">Crea tu cuenta ahora y empieza a conectar con docentes:</p>
                    @auth
                        @if(Auth::user()->rol === 'academia')
                            <a href="{{ route('academia.miscursos') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-tachometer-alt me-2"></i>Ir a mi Panel
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-user-plus me-2"></i>Crear Cuenta de Academia
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