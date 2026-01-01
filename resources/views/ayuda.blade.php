@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Encabezado con gradiente -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10 text-center">
            <div class="bg-gradient-primary text-white rounded-4 p-5 shadow-lg mb-4">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div class="bg-white rounded-circle p-4 me-3">
                        <i class="fas fa-question-circle fa-3x text-primary"></i>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold mb-2">Centro de Ayuda</h1>
                        <p class="lead mb-0" style="opacity: 0.9;">
                            Encuentra respuestas rápidas y soporte personalizado
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buscador de ayuda -->
    {{-- <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center">¿En qué podemos ayudarte?</h3>
                    <div class="input-group input-group-lg mb-4">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0" 
                               placeholder="Buscar en preguntas frecuentes..." 
                               id="helpSearch">
                        <button class="btn btn-primary px-4">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">
                            Ejemplo: "registro", "pagos", "cursos", "contraseña"
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Categorías de ayuda -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100 shadow-sm hover-card text-center">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-gradient-primary rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-user-plus fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Cuenta y Registro</h5>
                    <p class="text-muted small">
                        Creación de cuenta, verificación, inicio de sesión y recuperación de contraseña.
                    </p>
                    <a href="#cuenta" class="text-primary text-decoration-none fw-semibold">
                        Ver guías <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100 shadow-sm hover-card text-center">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-gradient-accent rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-university fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Para Academias</h5>
                    <p class="text-muted small">
                        Gestión de cursos, facturación, docentes y configuración de la academia.
                    </p>
                    <a href="#academias" class="text-primary text-decoration-none fw-semibold">
                        Ver guías <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100 shadow-sm hover-card text-center">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-success rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-chalkboard-teacher fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Para Docentes</h5>
                    <p class="text-muted small">
                        Creación de cursos, evaluación de alumnos, materiales y herramientas de enseñanza.
                    </p>
                    <a href="#docentes" class="text-primary text-decoration-none fw-semibold">
                        Ver guías <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 h-100 shadow-sm hover-card text-center">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-warning rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-user-graduate fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Para Alumnos</h5>
                    <p class="text-muted small">
                        Matrícula en cursos, certificados, seguimiento de progreso y herramientas de estudio.
                    </p>
                    <a href="#alumnos" class="text-primary text-decoration-none fw-semibold">
                        Ver guías <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Preguntas Frecuentes -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <h3 class="fw-bold mb-4 text-center">Preguntas Frecuentes</h3>
            <div class="accordion shadow-sm" id="faqAccordion">
                <!-- FAQ 1 -->
                <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq1">
                            <strong><i class="fas fa-user-circle me-2 text-primary"></i>¿Cómo cambio mi contraseña?</strong>
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Para cambiar tu contraseña:</p>
                            <p class="mb-0"><small>Si no recuerdas tu contraseña, usa la opción "¿Olvidaste tu contraseña?" en la pantalla de inicio de sesión.</small></p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq2">
                            <strong><i class="fas fa-credit-card me-2 text-primary"></i>¿Cómo funciona el sistema de pagos?</strong>
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>RedFPE ofrece diferentes modelos de pago según tu rol:</p>
                            <ul>
                                <li><strong>Alumnos:</strong> Acceso completamente gratuito</li>
                                <li><strong>Academias:</strong> Suscripción mensual según el plan elegido</li>
                                <li><strong>Docentes:</strong> Suscripción mensual según el plan elegido</li>
                            </ul>
                            <p>Los pagos se procesan de forma segura a través de Stripe. Puedes usar tarjetas de crédito/débito o PayPal.</p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                {{-- <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq3">
                            <strong><i class="fas fa-certificate me-2 text-primary"></i>¿Cómo obtengo mi certificado?</strong>
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Para obtener tu certificado:</p>
                            <ol>
                                <li>Completa todos los módulos del curso</li>
                                <li>Aprobar las evaluaciones requeridas</li>
                                <li>Ve a "Mis Cursos" en tu panel</li>
                                <li>Haz clic en el curso completado</li>
                                <li>Selecciona "Descargar Certificado"</li>
                            </ol>
                            <p class="mb-0"><small>Los certificados son digitales y tienen un código de verificación único que puede ser validado por empleadores.</small></p>
                        </div>
                    </div>
                </div>
                 --}}
                <!-- FAQ 4 -->
                {{-- <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq4">
                            <strong><i class="fas fa-video me-2 text-primary"></i>¿Cómo funcionan las clases en vivo?</strong>
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Las clases en vivo se realizan a través de nuestra plataforma integrada:</p>
                            <ul>
                                <li>Recibirás una notificación antes de cada clase</li>
                                <li>Accede desde "Mis Cursos" → "Clases en Vivo"</li>
                                <li>Puedes interactuar con el profesor y compañeros</li>
                                <li>Todas las clases quedan grabadas para revisión posterior</li>
                            </ul>
                            <p><strong>Requisitos técnicos:</strong></p>
                            <ul>
                                <li>Conexión a internet estable (mínimo 5 Mbps)</li>
                                <li>Navegador actualizado (Chrome, Firefox, Safari)</li>
                                <li>Micrófono y cámara opcionales</li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
                
                <!-- FAQ 5 -->
                <div class="accordion-item border-0 mb-3 rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-3 collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq5">
                            <strong><i class="fas fa-shield-alt me-2 text-primary"></i>¿Cómo protegen mis datos?</strong>
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>En RedFPE tomamos tu privacidad muy en serio:</p>
                            <ul>
                                <li><strong>Encriptación:</strong> Todos los datos se transmiten con encriptación SSL/TLS</li>
                                <li><strong>Sin cookies de seguimiento:</strong> Solo usamos cookies esenciales</li>
                                <li><strong>Protección de pagos:</strong> No almacenamos información de tarjetas de crédito</li>
                                <li><strong>Transparencia:</strong> Puedes solicitar la eliminación de tus datos en cualquier momento</li>
                            </ul>
                            <p class="mb-0">Para más información, consulta nuestra <a href="{{ route('privacy') }}" class="text-decoration-none">Política de Privacidad</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Canales de soporte -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="bg-light rounded-4 p-5">
                <h3 class="fw-bold mb-4 text-center">Canales de Soporte</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Correo Electrónico</h5>
                            <p class="text-muted small mb-2">Respuesta en 24 horas</p>
                            <a href="mailto:contacto@redfpe.com" class="text-decoration-none">
                                contacto@redfpe.com
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-comments fa-2x text-success"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Chat en Vivo</h5>
                            <p class="text-muted small mb-2">Accede a través de nuestras RRSS</p>
                            {{-- <button class="btn btn-outline-success btn-sm" id="startChatBtn">
                                <i class="fas fa-comment-dots me-2"></i>Iniciar Chat
                            </button> --}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-phone-alt fa-2x text-warning"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Teléfono</h5>
                            <p class="text-muted small mb-2">Soporte telefónico</p>
                            <a href="tel:+34635156263" class="text-decoration-none">
                                +34 635 15 62 63
                            </a>
                            <p class="small text-muted mt-1 mb-0">España (UTC+1)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de contacto adicional -->
    {{-- <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center">¿No encontraste lo que buscabas?</h3>
                    <p class="text-muted text-center mb-4">Envía tu consulta y te responderemos personalmente</p>
                    
                    <form id="helpForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nombre completo</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-12">
                                <label for="category" class="form-label fw-semibold">Categoría</label>
                                <select class="form-select" id="category" required>
                                    <option value="">Selecciona una categoría</option>
                                    <option value="cuenta">Cuenta y registro</option>
                                    <option value="pagos">Pagos y facturación</option>
                                    <option value="cursos">Cursos y contenido</option>
                                    <option value="tecnico">Problemas técnicos</option>
                                    <option value="otros">Otros</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold">Mensaje</label>
                                <textarea class="form-control" id="message" rows="5" 
                                          placeholder="Describe tu problema o pregunta en detalle..." 
                                          required></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        Acepto que RedFPE procese mis datos para responder a mi consulta.
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary px-5 py-3">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar consulta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
    }
    
    .bg-gradient-accent {
        background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%) !important;
    }
    
    .hover-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    
    .hover-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(67, 97, 238, 0.25);
    }
    
    #helpSearch {
        background-color: #f8fafc;
    }
    
    #helpSearch:focus {
        background-color: white;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    /* Chat simulation */
    .chat-modal {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Buscador de ayuda
    const helpSearch = document.getElementById('helpSearch');
    const faqItems = document.querySelectorAll('.accordion-item');
    
    if (helpSearch) {
        helpSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            faqItems.forEach(item => {
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
                
                if (searchTerm === '' || question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Simulación de chat
    const startChatBtn = document.getElementById('startChatBtn');
    if (startChatBtn) {
        startChatBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Chat de Soporte',
                html: `
                    <div class="text-start">
                        <p>Nuestro chat en vivo está disponible de <strong>Lunes a Viernes, 9:00-18:00</strong>.</p>
                        <p>Fuera de este horario, puedes enviarnos un correo a <strong>contacto@redfpe.com</strong>.</p>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Para una atención más rápida, proporciona tu número de usuario si lo tienes.
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#4361ee'
            });
        });
    }
    
    // Formulario de ayuda
    const helpForm = document.getElementById('helpForm');
    if (helpForm) {
        helpForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validación simple
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const category = document.getElementById('category').value;
            const message = document.getElementById('message').value.trim();
            const terms = document.getElementById('terms').checked;
            
            if (!name || !email || !category || !message || !terms) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, completa todos los campos y acepta los términos.',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
            
            // Validación de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Email inválido',
                    text: 'Por favor, ingresa un correo electrónico válido.',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
            
            // Simular envío (en producción, aquí iría una petición AJAX real)
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
            submitBtn.disabled = true;
            
            // Simular delay de red
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Consulta enviada!',
                    html: `
                        <div class="text-start">
                            <p>Hemos recibido tu consulta con éxito.</p>
                            <p>Te responderemos a <strong>${email}</strong> en un plazo máximo de 24 horas.</p>
                            <div class="alert alert-light mt-3">
                                <strong>Número de referencia:</strong> REF-${Date.now().toString().slice(-6)}
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#4361ee'
                });
                
                // Reset form
                helpForm.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }
    
    // Scroll suave para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId.startsWith('#') && targetId.length > 1) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
</script>

@endsection