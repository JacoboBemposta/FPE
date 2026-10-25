@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10 text-center">
            <h1 class="display-4 fw-bold mb-4" style="color: #3a0ca3;">
                Términos y <span style="background: linear-gradient(135deg, #4361ee, #3a0ca3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Condiciones</span>
            </h1>
            <p class="lead fs-5 text-dark mb-4">
                Por favor, lee detenidamente estas condiciones antes de usar nuestra plataforma
            </p>
            <div class="d-flex justify-content-center align-items-center">
                <div class="bg-primary rounded-circle p-3 me-3">
                    <i class="fas fa-file-contract fa-2x text-white"></i>
                </div>
                <div class="text-start">
                    <p class="mb-0"><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>
                    <p class="mb-0"><small class="text-muted">Al usar RedFPE, aceptas estos términos en su totalidad</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Introducción -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px; border-left: 5px solid #4361ee;">
                <h3 class="fw-bold mb-4" style="color: #4361ee;">
                    <i class="fas fa-gavel me-2"></i>Introducción
                </h3>
                <p class="fs-5 text-dark mb-0">
                    Bienvenido a <strong>RedFPE</strong>. Estos Términos y Condiciones regulan el uso de nuestra plataforma 
                    educativa. Al registrarte y utilizar nuestros servicios, aceptas cumplir con todos los términos aquí 
                    establecidos. Si no estás de acuerdo con alguna parte de estos términos, por favor, no uses nuestra plataforma.
                </p>
            </div>
        </div>
    </div>

    <!-- Contenido de términos -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <!-- Sección 1: Aceptación -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">1</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Aceptación de Términos</h3>
                </div>
                
                <div class="ms-5">
                    <p class="text-dark mb-3">
                        Al acceder y utilizar RedFPE, aceptas estar legalmente vinculado por estos Términos y Condiciones, 
                        nuestra Política de Privacidad y todas las leyes y regulaciones aplicables.
                    </p>
                    <div class="alert alert-primary" style="background-color: rgba(67, 97, 238, 0.1); border: none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3 fa-lg" style="color: #4361ee;"></i>
                            <div>
                                <h5 class="mb-1">Condiciones específicas por rol</h5>
                                <p class="mb-0">
                                    Dependiendo de si eres <strong>Academia, Docente o Alumno</strong>, podrían aplicarte condiciones adicionales 
                                    específicas para tu tipo de cuenta.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Descripción del servicio -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">2</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Descripción del Servicio</h3>
                </div>
                
                <div class="ms-5">
                    <p class="text-dark mb-4">
                        RedFPE es una plataforma educativa que facilita la conexión entre:
                    </p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-4 rounded" style="background-color: rgba(67, 97, 238, 0.05);">
                                <i class="fas fa-university fa-2x mb-3" style="color: #4361ee;"></i>
                                <h5 class="fw-bold mb-2">Academias</h5>
                                <p class="text-muted small mb-0">
                                    Publican cursos y buscan docentes cualificados
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-4 rounded" style="background-color: rgba(67, 97, 238, 0.05);">
                                <i class="fas fa-chalkboard-teacher fa-2x mb-3" style="color: #4361ee;"></i>
                                <h5 class="fw-bold mb-2">Docentes</h5>
                                <p class="text-muted small mb-0">
                                    Ofrecen sus servicios y encuentran oportunidades laborales
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-4 rounded" style="background-color: rgba(67, 97, 238, 0.05);">
                                <i class="fas fa-user-graduate fa-2x mb-3" style="color: #4361ee;"></i>
                                <h5 class="fw-bold mb-2">Alumnos</h5>
                                <p class="text-muted small mb-0">
                                    Buscan y se matriculan en cursos educativos
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-dark mb-0">
                        La plataforma actúa como <strong>intermediaria tecnológica</strong>, facilitando la conexión pero sin ser parte 
                        en los acuerdos contractuales que puedan establecerse entre los usuarios.
                    </p>
                </div>
            </div>

            <!-- Sección 3: Registro y Cuenta -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">3</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Registro y Cuenta de Usuario</h3>
                </div>
                
                <div class="ms-5">
                    <h5 class="fw-bold mb-3" style="color: #4361ee;">Requisitos para el registro:</h5>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" style="border-radius: 10px; overflow: hidden;">
                            <thead style="background-color: rgba(67, 97, 238, 0.1);">
                                <tr>
                                    <th class="p-3">Tipo de Usuario</th>
                                    <th class="p-3">Información Requerida</th>
                                    <th class="p-3">Verificación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-3 fw-bold">Todos los usuarios</td>
                                    <td class="p-3">
                                        <ul class="mb-0">
                                            <li>Nombre completo real</li>
                                            <li>Email válido</li>
                                            <li>Contraseña segura</li>
                                        </ul>
                                    </td>
                                    <td class="p-3">Email de confirmación</td>
                                </tr>
                                <tr>
                                    <td class="p-3 fw-bold">Academias</td>
                                    <td class="p-3">
                                        <ul class="mb-0">
                                            <li>Datos del centro educativo</li>
                                        </ul>
                                    </td>
                                    <td class="p-3">Verificación manual del equipo</td>
                                </tr>
                                <tr>
                                    <td class="p-3 fw-bold">Docentes</td>
                                    <td class="p-3">
                                        <ul class="mb-0">
                                            <li>Datos docente</li>
                                        </ul>
                                    </td>
                                    <td class="p-3">Verificación manual del equipo</td>
                                </tr>                                
                                <tr>
                                    <td class="p-3 fw-bold">Alumnos (cursos oficiales)</td>
                                    <td class="p-3">
                                        <ul class="mb-0">
                                            <li>DNI/NIE para verificación</li>
                                            <li>Teléfono de contacto</li>
                                            <li>Datos académicos relevantes</li>
                                        </ul>
                                    </td>
                                    <td class="p-3">Verificación por la academia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-warning" style="border-left: 4px solid #f59e0b;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3" style="color: #f59e0b;"></i>
                            <div>
                                <h5 class="mb-1">Responsabilidad de la cuenta</h5>
                                <p class="mb-0">
                                    Eres responsable de mantener la confidencialidad de tu cuenta y contraseña, y de todas las 
                                    actividades que ocurran bajo tu cuenta. <strong>Notifica inmediatamente</strong> cualquier uso no 
                                    autorizado de tu cuenta.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Conducta del Usuario -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">4</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Conducta del Usuario</h3>
                </div>
                
                <div class="ms-5">
                    <p class="text-dark mb-4">
                        Al usar RedFPE, te comprometes a:
                    </p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Proporcionar información veraz</h6>
                                    <p class="text-muted small mb-0">Todos los datos proporcionados deben ser exactos y actualizados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Respetar a otros usuarios</h6>
                                    <p class="text-muted small mb-0">Comunicación profesional y respetuosa en todo momento</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Uso legítimo</h6>
                                    <p class="text-muted small mb-0">Solo para fines educativos y profesionales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Cumplir leyes aplicables</h6>
                                    <p class="text-muted small mb-0">Incluyendo protección de datos y propiedad intelectual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-3" style="color: #4361ee;">Prohibiciones:</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-ban mt-1 me-3 text-danger"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Suplantación de identidad</h6>
                                    <p class="text-muted small mb-0">No te hagas pasar por otra persona o entidad</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-ban mt-1 me-3 text-danger"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Contenido inapropiado</h6>
                                    <p class="text-muted small mb-0">No publiques contenido ofensivo, ilegal o spam</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-ban mt-1 me-3 text-danger"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Actividades comerciales no autorizadas</h6>
                                    <p class="text-muted small mb-0">No uses la plataforma para ventas no relacionadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-ban mt-1 me-3 text-danger"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Acceso no autorizado</h6>
                                    <p class="text-muted small mb-0">No intentes acceder a áreas restringidas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Propiedad Intelectual -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">5</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Propiedad Intelectual</h3>
                </div>
                
                <div class="ms-5">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-3" style="color: #4361ee;">Derechos de RedFPE:</h5>
                            <p class="text-dark mb-0">
                                La plataforma RedFPE, incluyendo su software, diseño, logotipos, contenido y funcionalidades, 
                                está protegida por derechos de autor, marcas registradas y otras leyes de propiedad intelectual. 
                                Otorgamos una licencia limitada para usar el servicio según estos términos.
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-primary bg-opacity-10 p-4 rounded-3">
                                <i class="fas fa-copyright fa-3x mb-3" style="color: #4361ee;"></i>
                                <p class="fw-bold mb-0">© {{ date('Y') }} RedFPE</p>
                                <p class="text-muted small">Todos los derechos reservados</p>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-3" style="color: #4361ee;">Contenido del usuario:</h5>
                    <div class="alert alert-info" style="background-color: rgba(67, 97, 238, 0.1); border: none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lightbulb me-3 fa-lg" style="color: #4361ee;"></i>
                            <div>
                                <h5 class="mb-1">Conservas tus derechos</h5>
                                <p class="mb-0">
                                    Al publicar contenido en RedFPE, <strong>conservas todos los derechos de propiedad intelectual</strong> sobre 
                                    dicho contenido. Sin embargo, nos concedes una licencia mundial, no exclusiva y libre de regalías 
                                    para usar, mostrar y distribuir dicho contenido en relación con nuestros servicios.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 6: Limitación de Responsabilidad -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">6</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Limitación de Responsabilidad</h3>
                </div>
                
                <div class="ms-5">
                    <p class="text-dark mb-4">
                        RedFPE actúa como una plataforma de conexión entre usuarios. Es importante entender que:
                    </p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-4 rounded h-100" style="background-color: rgba(67, 97, 238, 0.05);">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-handshake text-white"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Acuerdos entre usuarios</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    Los acuerdos, contratos o transacciones que realices con otros usuarios son <strong>responsabilidad exclusiva</strong> 
                                    de las partes involucradas.
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-4 rounded h-100" style="background-color: rgba(67, 97, 238, 0.05);">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-user-check text-white"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Verificación de usuarios</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    Aunque realizamos verificaciones, <strong>no garantizamos</strong> la identidad, credenciales o intenciones 
                                    de otros usuarios.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning" style="border-left: 4px solid #f59e0b;">
                        <h5 class="fw-bold mb-2">Exención de responsabilidad</h5>
                        <p class="mb-2">
                            En la máxima medida permitida por la ley, RedFPE no será responsable por:
                        </p>
                        <ul class="mb-0">
                            <li>Daños indirectos, incidentales o consecuentes</li>
                            <li>Pérdida de datos o interrupción del servicio</li>
                            <li>Conducta de otros usuarios de la plataforma</li>
                            <li>Contenido publicado por terceros</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sección 7: Modificaciones -->
            <div class="card border-0 shadow-sm p-5 mb-4" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">7</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Modificaciones de los Términos</h3>
                </div>
                
                <div class="ms-5">
                    <p class="text-dark mb-4">
                        Nos reservamos el derecho de modificar estos Términos y Condiciones en cualquier momento. 
                        Los cambios entrarán en vigor inmediatamente después de su publicación en la plataforma.
                    </p>
                    
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-bell mt-1 me-3 text-primary"></i>
                                <div>
                                    <h5 class="fw-bold mb-1">Notificación de cambios</h5>
                                    <p class="text-muted mb-0">
                                        Te notificaremos sobre cambios significativos mediante:
                                    </p>
                                    <ul class="mb-0">
                                        <li>Email a la dirección registrada</li>
                                        <li>Notificación dentro de la plataforma</li>
                                        <li>Actualización de la fecha en esta página</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-history fa-2x mb-2" style="color: #4361ee;"></i>
                                <p class="fw-bold mb-0 small">Continuar usando RedFPE después de los cambios constituye aceptación de los nuevos términos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 8: Legislación Aplicable -->
            <div class="card border-0 shadow-sm p-5" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <span class="fw-bold fs-4 text-primary">8</span>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #3a0ca3;">Legislación Aplicable y Resolución de Disputas</h3>
                </div>
                
                <div class="ms-5">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="p-4 rounded h-100" style="background-color: rgba(67, 97, 238, 0.05);">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-balance-scale fa-lg me-3" style="color: #4361ee;"></i>
                                    <h5 class="fw-bold mb-0">Legislación aplicable</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    Estos términos se rigen por las leyes de España. Cualquier disputa relacionada con estos 
                                    términos estará sujeta a la jurisdicción exclusiva de los tribunales de Madrid.
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-4 rounded h-100" style="background-color: rgba(67, 97, 238, 0.05);">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-handshake fa-lg me-3" style="color: #4361ee;"></i>
                                    <h5 class="fw-bold mb-0">Resolución de disputas</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    Nos comprometemos a resolver cualquier controversia de manera amistosa. Antes de emprender 
                                    acciones legales, te pedimos que nos contactes para intentar resolver el problema.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="d-inline-block p-4 rounded" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                            <h5 class="fw-bold mb-3" style="color: #3a0ca3;">¿Tienes preguntas sobre estos términos?</h5>
                            <p class="text-muted mb-3">
                                Contacta con nuestro equipo legal en <strong>contacto@redfpe.com</strong>
                            </p>
                            <a href="mailto:contacto@redfpe.com" class="btn btn-primary">
                                <i class="fas fa-envelope me-2"></i>Contactar al departamento legal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aceptación final -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-5 text-center" style="border-radius: 20px;">
                <i class="fas fa-file-signature fa-4x mb-4" style="color: #4361ee;"></i>
                <h3 class="fw-bold mb-3" style="color: #3a0ca3;">Aceptación de Términos</h3>
                <p class="text-dark mb-4">
                    Al crear una cuenta en RedFPE o continuar usando nuestros servicios, confirmas que:
                </p>
                
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                            <span>Has leído y comprendido estos Términos y Condiciones</span>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                            <span>Aceptas estar legalmente vinculado por estos términos</span>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle mt-1 me-3 text-success"></i>
                            <span>Aceptas nuestra Política de Privacidad</span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info" style="background-color: rgba(67, 97, 238, 0.1);">
                    <p class="mb-0">
                        <strong>Importante:</strong> Estos términos constituyen el acuerdo completo entre tú y RedFPE 
                        respecto al uso de la plataforma y reemplazan cualquier acuerdo anterior.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table th {
        background-color: rgba(67, 97, 238, 0.1) !important;
        border-bottom: 2px solid #4361ee;
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
    
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05) !important;
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
        
        // Navegación suave para secciones internas (si se implementan anclas)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId !== '#') {
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