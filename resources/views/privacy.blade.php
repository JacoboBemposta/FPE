@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10 text-center">
            <h1 class="display-4 fw-bold mb-4" style="color: #3a0ca3;">
                Política de <span style="background: linear-gradient(135deg, #4361ee, #3a0ca3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Privacidad</span>
            </h1>
            <p class="lead fs-5 text-dark mb-4">
                Transparencia total en el tratamiento de datos. Tu privacidad es nuestra prioridad.
            </p>
            <div class="d-flex justify-content-center align-items-center">
                <div class="bg-primary rounded-circle p-3 me-3">
                    <i class="fas fa-user-shield fa-2x text-white"></i>
                </div>
                <div class="text-start">
                    <p class="mb-0"><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>
                    <p class="mb-0"><small class="text-muted">Esta política se aplica a todos los usuarios de RedFPE</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Introducción -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px; border-left: 5px solid #4361ee;">
                <h3 class="fw-bold mb-4" style="color: #4361ee;">
                    <i class="fas fa-info-circle me-2"></i>Introducción
                </h3>
                <p class="fs-5 text-dark mb-0">
                    En <strong>RedFPE</strong> nos comprometemos a proteger tu privacidad y tus datos personales. 
                    Esta política explica cómo recopilamos, utilizamos y protegemos la información que nos proporcionas, 
                    siempre en cumplimiento con el Reglamento General de Protección de Datos (RGPD) y la legislación española.
                </p>
            </div>
        </div>
    </div>


    <!-- Recopilación de datos -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg p-5 mb-4" style="border-radius: 20px;">
                <h2 class="fw-bold mb-4" style="color: #3a0ca3;">
                    <i class="fas fa-database me-2"></i>¿Qué datos recopilamos?
                </h2>
                <p class="fs-5 text-dark mb-4">
                    Recopilamos únicamente la información estrictamente necesaria para proporcionarte nuestros servicios. 
                    Nuestro principio fundamental es la <strong>minimización de datos</strong>.
                </p>

                <!-- Tabla de recopilación de datos -->
                <div class="table-responsive mt-4">
                    <table class="table table-hover border" style="border-radius: 10px; overflow: hidden;">
                        <thead style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white;">
                            <tr>
                                <th class="p-3">Tipo de Usuario</th>
                                <th class="p-3">Datos Recopilados</th>
                                <th class="p-3">Finalidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-3 fw-bold">Todos los usuarios</td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Email</li>
                                        <li>Contraseña (encriptada)</li>
                                        <li>Nombre completo</li>
                                    </ul>
                                </td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Autenticación y acceso</li>
                                        <li>Comunicación esencial</li>
                                        <li>Personalización de experiencia</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 fw-bold">Academias</td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Datos de contacto</li>
                                        <li>Cursos que imparten y su ubicación</li>
                                    </ul>
                                </td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Comunicación con docentes</li>
                                        <li>Gestión de cursos y matrículas</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 fw-bold">Docentes</td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Especialidades y experiencia</li>
                                        <li>Datos de contacto</li>
                                    </ul>
                                </td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Comunicación con academias</li>
                                        <li>Gestión de asignaciones y cursos</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="background-color: rgba(67, 97, 238, 0.05);">
                                <td class="p-3 fw-bold">Alumnos (cursos académicos)</td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>Datos cifrados y protegidos</li>
                                        <li>DNI/NIE</li>
                                        <li>Nombre completo</li>
                                        <li>Teléfono de contacto</li>
                                    </ul>
                                </td>
                                <td class="p-3">
                                    <ul class="mb-0">
                                        <li>La academia es responsable de obtener el consentimiento de los alumnos o sus representantes legales</li>
                                        <li>Gestión de matrículas en cursos académicos</li>
                                        <li>Documentación académica</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4" style="border-left: 4px solid #4361ee; background-color: rgba(67, 97, 238, 0.1);">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3" style="color: #4361ee;"></i>
                        <div>
                            <h5 class="mb-1">Nota importante sobre alumnos</h5>
                            <p class="mb-0">
                                Los datos de DNI y teléfono de los alumnos son recabados únicamente por las academias que los matriculan en cursos académicos oficiales. 
                                RedFPE actúa como encargado técnico del tratamiento, garantizando la seguridad y confidencialidad de la información. 
                                Las academias declaran que disponen del consentimiento de los alumnos o, en su caso, de los representantes legales para el tratamiento de estos datos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Política de Cookies -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg p-5 mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <h2 class="fw-bold mb-4" style="color: #3a0ca3;">
                    <i class="fas fa-cookie-bite me-2"></i>Política de Cookies
                </h2>
                
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h4 class="fw-bold mb-3">Transparencia total: No usamos cookies de seguimiento</h4>
                        <p class="text-dark mb-4">
                            En RedFPE respetamos tu privacidad de manera absoluta. <strong>No utilizamos cookies de seguimiento, 
                            de analítica de terceros, ni de publicidad comportamental.</strong>
                        </p>
                        
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Solo cookies técnicas esenciales:</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background-color: white;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-user-check text-primary"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0">Sesión de usuario (laravel_session)</h6>
                                        </div>
                                        <p class="text-muted mb-0 small">
                                            Mantiene tu sesión activa mientras usas la plataforma
                                        </p>
                                        <p class="text-muted mb-0 small">
                                            <b>Duración:</b> Sesión
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background-color: white;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-shield-alt text-primary"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0">Seguridad (XSRF-TOKEN)</h6>
                                        </div>
                                        <p class="text-muted mb-0 small">
                                            Protege los formularios y peticiones contra ataques CSRF
                                        </p>
                                        <p class="text-muted mb-0 small">
                                            <b>Duración:</b> Sesión
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-white p-4 rounded-3 shadow-sm">
                            <div class="bg-success bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                                <i class="fas fa-ban fa-3x text-success"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Sin seguimiento</h5>
                            <p class="text-muted small">No rastreamos tu actividad fuera de RedFPE</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Uso de datos -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <h2 class="fw-bold mb-4" style="color: #4361ee;">
                    <i class="fas fa-chart-line me-2"></i>¿Cómo utilizamos tus datos?
                </h2>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-4 rounded h-100" style="background-color: rgba(67, 97, 238, 0.05);">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-cogs fa-lg text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Funcionamiento básico</h5>
                            </div>
                            <ul class="text-dark">
                                <li class="mb-2">Proporcionar acceso a la plataforma</li>
                                <li class="mb-2">Gestionar tu cuenta y perfil</li>
                                <li class="mb-2">Facilitar la comunicación entre usuarios</li>
                                <li>Mantener la seguridad de la plataforma</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-4 rounded h-100" style="background-color: rgba(67, 97, 238, 0.05);">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-graduation-cap fa-lg text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Servicios educativos</h5>
                            </div>
                            <ul class="text-dark">
                                <li class="mb-2">Conectar docentes con academias</li>
                                <li class="mb-2">Gestionar matrículas y cursos</li>
                                <li class="mb-2">Emitir certificados y documentación</li>
                                <li>Proporcionar soporte académico</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Derechos del usuario -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg p-5" style="border-radius: 20px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                <h2 class="fw-bold mb-4 text-white">
                    <i class="fas fa-user-lock me-2"></i>Tus derechos de protección de datos
                </h2>
                
                <p class="text-white mb-4" style="opacity: 0.9;">
                    De acuerdo con el RGPD, tienes los siguientes derechos sobre tus datos personales:
                </p>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="bg-white p-4 rounded-3 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Derecho de acceso</h5>
                            </div>
                            <p class="text-muted mb-0">Puedes solicitar una copia de todos los datos que tenemos sobre ti.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bg-white p-4 rounded-3 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Derecho de rectificación</h5>
                            </div>
                            <p class="text-muted mb-0">Puedes solicitar la corrección de datos inexactos o incompletos.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bg-white p-4 rounded-3 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-trash-alt text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Derecho de supresión</h5>
                            </div>
                            <p class="text-muted mb-0">Puedes solicitar la eliminación de tus datos personales.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bg-white p-4 rounded-3 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-download text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Derecho de portabilidad</h5>
                            </div>
                            <p class="text-muted mb-0">Puedes solicitar una copia de tus datos en formato electrónico.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-white">
                    <p class="mb-2"><strong>Para ejercer estos derechos:</strong></p>
                    <ol class="mb-0" style="opacity: 0.9;">
                        <li>Accede a la configuración de tu cuenta en RedFPE</li>
                        <li>O contacta con nuestro Delegado de Protección de Datos en <strong>contacto@redfpe.com</strong></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Contacto y vigencia -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 20px;">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-3" style="color: #3a0ca3;">
                            <i class="fas fa-headset me-2"></i>Contacto y soporte
                        </h3>
                        <p class="text-dark mb-4">
                            Si tienes preguntas sobre esta política de privacidad o sobre el tratamiento de tus datos, 
                            no dudes en contactarnos.
                        </p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-envelope mt-1 me-3" style="color: #4361ee;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Delegado de Protección de Datos</h6>
                                        <p class="mb-0">contacto@redfpe.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-phone mt-1 me-3" style="color: #4361ee;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Atención al usuario</h6>
                                        <p class="mb-0">+34 635 15 62 63</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-3">
                            <i class="fas fa-file-contract fa-4x mb-3" style="color: #4361ee;"></i>
                            <h5 class="fw-bold mb-2">Política vigente</h5>
                            <p class="text-muted small mb-0">Actualizada el {{ date('d/m/Y') }}</p>
                            <p class="text-muted small">Nos reservamos el derecho de modificar esta política</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.1) !important;
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
    
    .rounded-3 {
        border-radius: 1rem !important;
    }
    
    .alert {
        border-radius: 12px;
        border: none;
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
        
        // Resaltar la fila de alumnos al pasar el mouse
        const studentRow = document.querySelector('tr[style*="background-color: rgba(67, 97, 238, 0.05);"]');
        if (studentRow) {
            studentRow.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(67, 97, 238, 0.15)';
                this.style.transition = 'background-color 0.3s ease';
            });
            
            studentRow.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
            });
        }
    });
</script>
@endsection