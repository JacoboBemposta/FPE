@extends('layouts.app')

@section('content')

<style>
    /* Estilos específicos para la página de inicio - Versión con degradado de texto */
    .hero-section {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        padding: 100px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
        min-height: 80vh;
        display: flex;
        align-items: center;
        height: 100vh;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 120L0 16.48 0 0 1200 0 1200 120z" fill="%23f8fafc"/></svg>') bottom center no-repeat;
        background-size: 100% auto;
        opacity: 0.1; /* Reducir opacidad para mejor contraste */
    }
    
    .hero-section .container {
        position: relative;
        z-index: 2;
        max-width: 900px;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #4cc9f0 100%); /* Texto con degradado */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        display: inline-block;
    }
    
    .hero-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #4cc9f0, #ffffff);
        border-radius: 2px;
    }
    
    .hero-subtitle {
        font-size: 1.8rem;
        font-weight: 300;
        margin-bottom: 2rem;
        color: rgba(255, 255, 255, 0.95);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.4;
    }
    
    .hero-lead {
        font-size: 1.2rem;
        margin-bottom: 3rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
        background: rgba(0, 0, 0, 0.2); /* Fondo sutil para mejor contraste */
        padding: 15px 25px;
        border-radius: 12px;
        border-left: 4px solid #4cc9f0;
    }
    
    .hero-btn {
        background: #ffffff;
        color: #4361ee;
        border: 2px solid transparent;
        padding: 16px 40px;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .hero-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50px;
    }
    
    .hero-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        color: white;
        border-color: transparent;
    }
    
    .hero-btn:hover::before {
        opacity: 1;
    }
    
    /* Estilos para las demás secciones */
    .roles-section {
        padding: 100px 0;
        background: #f8fafc;
        position: relative;
    }
    
    .roles-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-align: center;
        color: var(--dark-color);
        position: relative;
        padding-bottom: 20px;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: 2px;
    }
    
    .section-subtitle {
        text-align: center;
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 4rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .role-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 2.5rem;
        height: 100%;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .role-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .role-card:hover {
        transform: translateY(-15px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }
    
    .role-card:hover::before {
        transform: scaleX(1);
    }
    
    .role-icon {
        width: 120px;
        height: 120px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.2);
        transition: all 0.3s ease;
    }
    
    .role-card:hover .role-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 30px rgba(67, 97, 238, 0.3);
    }
    
    .role-icon i {
        font-size: 3.5rem;
        color: white;
    }
    
    .role-card h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--dark-color);
    }
    
    .role-card p {
        color: #64748b;
        margin-bottom: 2rem;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .role-btn {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 12px 32px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 160px;
        margin: 0 auto;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
    }
    
    .role-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        color: white;
    }
    
    .role-btn:disabled {
        background: #94a3b8;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
    }
    
    .role-btn-secondary {
        background: #64748b;
    }
    
    .role-btn-secondary:hover {
        background: #475569;
    }
    
    .statistics-section {
        padding: 80px 0;
        background: white;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem;
        border-radius: var(--border-radius);
        background: #f8fafc;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .features-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }
    
    .feature-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        height: 100%;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .feature-icon i {
        font-size: 2rem;
        color: white;
    }
    
    /* Animaciones */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .floating {
        animation: float 6s ease-in-out infinite;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .role-card {
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .role-icon {
            width: 100px;
            height: 100px;
        }
        
        .role-icon i {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .hero-section {
            padding: 60px 0;
            min-height: 70vh;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .hero-btn {
            padding: 14px 30px;
            font-size: 1rem;
        }
    }
</style>

<!-- Hero Section Mejorada -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Bienvenido a RedFPE</h1>
        <h2 class="hero-subtitle">La plataforma inteligente que transforma la gestión académica</h2>
        <p class="hero-lead">Conecta academias, docentes y alumnos en un ecosistema educativo completo y eficiente</p>
        <a href="#roles" class="hero-btn">
            <i class="fas fa-rocket me-1"></i> Comienza tu experiencia
        </a>
    </div>
</section>

<!-- Sección de Estadísticas (Opcional) -->
{{-- <section class="statistics-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-card floating" style="animation-delay: 0s;">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Academias Activas</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card floating" style="animation-delay: 0.2s;">
                    <div class="stat-number">2,500+</div>
                    <div class="stat-label">Docentes</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card floating" style="animation-delay: 0.4s;">
                    <div class="stat-number">25,000+</div>
                    <div class="stat-label">Alumnos</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card floating" style="animation-delay: 0.6s;">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfacción</div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- Roles Section Mejorada -->
<section id="roles" class="roles-section">
    <div class="container">
        <h2 class="section-title">Descubre tu Espacio Educativo</h2>
        <p class="section-subtitle">Selecciona tu rol para acceder a herramientas personalizadas que potenciarán tu experiencia académica</p>
        
        <div class="row g-4">
            <!-- Card para Academias -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h4>Academia</h4>
                    <p>Crea cursos, asigna docentes, administra estudiantes.</p>
                    
                    @auth
                        @if(Auth::user()->rol === 'academia')
                            <a href="{{ route('academia.miscursos') }}" class="role-btn">
                                <i class="fas fa-sign-in-alt me-1"></i> Acceder al Panel
                            </a>
                        @else
                            <button class="role-btn role-btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i> Acceso Restringido
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="role-btn">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Card para Profesores -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4>Docente</h4>
                    <p>Gestiona tus cursos, contacta con las academias.</p>

                    @auth
                        @if(Auth::user()->rol === 'profesor')
                            <a href="{{ route('profesor.miscursos') }}" class="role-btn">
                                <i class="fas fa-sign-in-alt me-1"></i> Acceder al Panel
                            </a>
                        @else
                            <button class="role-btn role-btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i> Acceso Restringido
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="role-btn">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Card para Alumnos -->
            <div class="col-lg-4 col-md-6">
                <div class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4>Alumno</h4>
                    <p>Accede a los cursos que te interesan en tu localidad.</p>

                    @auth
                        @if(Auth::user()->rol === 'alumno')
                            <a href="{{ route('alumno.index') }}" class="role-btn">
                                <i class="fas fa-sign-in-alt me-1"></i> Acceder al Panel
                            </a>
                        @else
                            <button class="role-btn role-btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i> Acceso Restringido
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="role-btn">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Características -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">¿Por qué elegir RedFPE?</h2>
        <p class="section-subtitle">Es la única plataforma diseñada específicamente para conectar academias, docentes y alumnos</p>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Rápida Implementación</h4>
                    <p>Comienza a usar la plataforma en minutos, sin necesidad de complejas configuraciones.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Seguridad Garantizada</h4>
                    <p>Tus datos están protegidos con los más altos estándares de encriptación y seguridad.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Soporte Personalizado</h4>
                    <p>Nuestro equipo está disponible para ayudarte en lo que necesites.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Animación para los elementos al hacer scroll
document.addEventListener('DOMContentLoaded', function() {
    // Animación para las tarjetas de roles al aparecer en pantalla
    const roleCards = document.querySelectorAll('.role-card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    roleCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Smooth scroll para el botón del hero
    document.querySelector('.hero-btn').addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });

    // Efecto hover mejorado para las tarjetas
    roleCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.role-icon i');
            icon.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.role-icon i');
            icon.style.transform = 'scale(1) rotate(0deg)';
        });
    });
});
</script>

@endsection