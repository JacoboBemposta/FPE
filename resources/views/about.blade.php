@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header Hero -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4" style="color: #3a0ca3;">
                Sobre <span style="background: linear-gradient(135deg, #4361ee, #3a0ca3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">RedFPE</span>
            </h1>
            <p class="lead fs-4 text-dark mb-4">
                Conectando el futuro de la educación a través de la tecnología y la experiencia docente
            </p>
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded-circle p-3 me-3">
                    <i class="fas fa-graduation-cap fa-2x text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0">Plataforma Educativa Inteligente</h5>
                    <p class="text-muted mb-0">Desarrollada por educadores para educadores</p>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-6">
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Educación y tecnología" 
                     class="img-fluid rounded-3 shadow-lg">
                <div class="position-absolute bottom-0 start-0 bg-primary text-white p-4 rounded-end" style="transform: translateY(20px);">
                    <h4 class="mb-0">+500 Academias</h4>
                    <small>Conectadas en nuestra red</small>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Nuestra Historia -->
    <div class="row justify-content-center mb-6">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg p-5 mb-5" style="border-radius: 20px;">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-4" style="color: #4361ee;">Nuestra Historia</h2>
                        <p class="fs-5 text-dark mb-4">
                            RedFPE nació de una experiencia personal compartida por un grupo de <strong>docentes recién incorporados</strong> al mundo educativo. Al enfrentarnos a los desafíos de encontrar oportunidades laborales estables y conectar con instituciones educativas, identificamos una brecha significativa en el mercado.
                        </p>
                        <p class="fs-5 text-dark mb-4">
                            Como profesionales con <strong>poca experiencia pero mucho entusiasmo</strong>, comprendimos que el proceso tradicional de búsqueda de empleo docente era fragmentado, lento y poco eficiente. Las academias tenían dificultades para encontrar docentes cualificados, mientras que los docentes luchaban por visibilizar su potencial.
                        </p>
                        <p class="fs-5 text-dark mb-0">
                            Esta dualidad de necesidades nos inspiró a crear una <strong>solución integral</strong> que transformara la forma en que el sector educativo se conecta, contrata y colabora.
                        </p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-gradient-primary rounded-circle p-5 d-inline-block mb-3">
                            <i class="fas fa-lightbulb fa-4x text-white"></i>
                        </div>
                        <h5 class="fw-bold">De la necesidad a la innovación</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Valores -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #3a0ca3;">Nuestros Valores Fundamentales</h2>
            <p class="text-muted lead">Los principios que guían cada decisión en RedFPE</p>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-4 text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                    <i class="fas fa-handshake fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-3">Compromiso Docente</h5>
                <p class="text-muted">Desarrollada por educadores, entendemos las necesidades reales del sector desde dentro.</p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-4 text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                    <i class="fas fa-shield-alt fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-3">Transparencia Total</h5>
                <p class="text-muted">Creemos en procesos claros, sin intermediarios y con comunicación directa entre instituciones y docentes.</p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-4 text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                    <i class="fas fa-rocket fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-3">Innovación Accesible</h5>
                <p class="text-muted">Tecnología avanzada con interfaz intuitiva, diseñada para usuarios sin experiencia técnica.</p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-4 text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-3">Comunidad Colaborativa</h5>
                <p class="text-muted">Fomentamos el crecimiento conjunto donde todos los actores educativos se benefician mutuamente.</p>
            </div>
        </div>
    </div>

    <!-- Misión y Visión -->
    <div class="row mb-6 g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow h-100 p-5" style="border-radius: 20px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <div class="text-white">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-white rounded-circle p-3 me-3">
                            <i class="fas fa-bullseye fa-2x text-primary"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-white">Nuestra Misión</h3>
                    </div>
                    <p class="fs-5 mb-4" style="opacity: 0.9;">
                        Democratizar el acceso a oportunidades educativas mediante una plataforma que elimine las barreras tradicionales entre instituciones y profesionales de la enseñanza.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle me-3 mt-1 text-white"></i>
                            <span>Facilitar la conexión directa entre academias y docentes</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle me-3 mt-1 text-white"></i>
                            <span>Simplificar los procesos de contratación y gestión educativa</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fas fa-check-circle me-3 mt-1 text-white"></i>
                            <span>Empoderar a nuevos docentes para que muestren su valor desde el primer día</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow h-100 p-5" style="border-radius: 20px; background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);">
                <div class="text-white">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-white rounded-circle p-3 me-3">
                            <i class="fas fa-eye fa-2x" style="color: #4361ee;"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-white">Nuestra Visión</h3>
                    </div>
                    <p class="fs-5 mb-4" style="opacity: 0.9;">
                        Convertirnos en el ecosistema educativo de referencia en España, donde cada academia encuentre al docente ideal y cada docente desarrolle su carrera plenamente.
                    </p>
                        {{-- <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded p-3 text-center">
                                    <h2 class="fw-bold text-dark mb-1">2025</h2>
                                    <small class="text-dark">Líder en 5 comunidades</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded p-3 text-center">
                                    <h2 class="fw-bold text-dark mb-1">10k+</h2>
                                    <small class="text-dark">Docentes activos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded p-3 text-center">
                                    <h2 class="fw-bold text-dark mb-1">100%</h2>
                                    <small class="text-dark">Cobertura nacional</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white bg-opacity-20 rounded p-3 text-center">
                                    <h2 class="fw-bold text-dark mb-1">1k+</h2>
                                    <small class="text-dark">Academias asociadas</small>
                                </div>
                            </div>
                        </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Cómo lo hacemos -->
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #3a0ca3;">Cómo Transformamos la Educación</h2>
            <p class="text-muted lead">Una solución integral para cada actor del sector educativo</p>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-university fa-lg text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Para Academias</h5>
                </div>
                <p class="text-muted">
                    Acceso a una amplia base de datos de docentes filtrados por especialidad, ubicación y disponibilidad. Gestión simplificada de cursos y contrataciones.
                </p>
                <ul class="text-muted">
                    <li>Publicación instantánea de vacantes</li>
                    <li>Sistema de valoración transparente</li>
                    <li>Herramientas de gestión integral</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-chalkboard-teacher fa-lg text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Para Docentes</h5>
                </div>
                <p class="text-muted">
                    Plataforma diseñada específicamente para nuevos educadores. Perfiles profesionales que destacan tu potencial más allá de la experiencia.
                </p>
                <ul class="text-muted">
                    <li>Creación de perfil atractivo</li>
                    <li>Alertas de empleo personalizadas</li>
                    <li>Recursos para desarrollo profesional</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-user-graduate fa-lg text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Para Alumnos</h5>
                </div>
                <p class="text-muted">
                    Encuentra los mejores cursos y academias cerca de ti. Sistema de valoraciones reales y transparencia en la oferta educativa.
                </p>
                <ul class="text-muted">
                    <li>Búsqueda inteligente de cursos</li>
                    <li>Comparación de academias</li>
                    <li>Gestión centralizada de matriculación</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- CTA Final -->
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="card border-0 shadow-lg p-5" style="border-radius: 20px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold mb-4" style="color: #3a0ca3;">Únete a la Revolución Educativa</h2>
                <p class="fs-5 text-dark mb-4">
                    Forma parte de la comunidad que está transformando la forma en que se conectan educadores e instituciones. 
                    Ya seas una academia en busca de talento o un docente con ganas de cambiar vidas, tenemos un espacio para ti.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @auth
                        <a href="{{ Auth::user()->rol === 'academia' ? route('academia.miscursos') : (Auth::user()->rol === 'profesor' ? route('profesor.miscursos') : route('alumno.index')) }}" 
                           class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-rocket me-2"></i>Ir a mi Panel
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-user-plus me-2"></i>Crear Cuenta Gratis
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                    @endauth
                </div>
                <p class="text-muted mt-4">
                    <small><i class="fas fa-shield-alt me-1"></i> Sin compromiso • Cancelación gratuita • Soporte personalizado</small>
                </p>
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
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .fs-5 {
        line-height: 1.6;
    }
    
    .mb-6 {
        margin-bottom: 5rem !important;
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
    });
</script>
@endsection