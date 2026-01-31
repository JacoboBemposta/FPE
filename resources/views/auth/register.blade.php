@extends('layouts.app')

@section('content')
<style>
    /* Estilos específicos para la página de registro */
    .register-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    
    .register-card {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        border: none;
        overflow: hidden;
        width: 100%;
        max-width: 800px;
    }
    
    .register-header {
        background: var(--gradient-primary);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .register-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M500,50 C300,150 700,150 500,50 Z" fill="rgba(255,255,255,0.1)"/></svg>') center no-repeat;
    }
    
    .register-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }
    
    .register-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }
    
    .register-body {
        padding: 3rem;
    }
    
    .form-group-advanced {
        margin-bottom: 2rem;
        position: relative;
    }
    
    .form-label-custom {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }
    
    .form-label-custom i {
        color: var(--primary-color);
        font-size: 1.2rem;
        width: 24px;
    }
    
    .form-control-custom {
        border: 2px solid #e2e8f0;
        border-radius: var(--border-radius);
        padding: 1rem 1.5rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }
    
    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        background: white;
        transform: translateY(-2px);
    }
    
    .form-control-custom.is-invalid {
        border-color: var(--danger-color);
        background: rgba(239, 68, 68, 0.05);
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1.2rem;
    }
    
    .input-with-icon .form-control-custom {
        padding-left: 3.5rem;
    }
    
    .password-toggle {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: var(--primary-color);
    }
    
    .role-options {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .role-option-register {
        flex: 1;
        min-width: 150px;
        border: 2px solid #e2e8f0;
        border-radius: var(--border-radius);
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        position: relative;
    }
    
    .role-option-register:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }
    
    .role-option-register.selected {
        border-color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }
    
    .role-option-register.selected::before {
        content: '✓';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    
    .role-option-register input[type="radio"] {
        display: none;
    }
    
    .role-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }
    
    .role-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .role-description {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.4;
    }
    
    .register-btn {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 1.2rem 3rem;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        margin-top: 2rem;
        box-shadow: 0 5px 20px rgba(67, 97, 238, 0.2);
    }
    
    .register-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
    }
    
    .register-btn:active {
        transform: translateY(-1px);
    }
    
    .login-link {
        text-align: center;
        margin-top: 2rem;
        color: #64748b;
    }
    
    .login-link a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .login-link a:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }
    
    .error-message {
        color: var(--danger-color);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .form-note {
        font-size: 0.9rem;
        color: #64748b;
        margin-top: 0.5rem;
        padding-left: 2rem;
    }
    
    @media (max-width: 768px) {
        .register-container {
            padding: 1rem;
        }
        
        .register-header {
            padding: 2rem 1rem;
        }
        
        .register-body {
            padding: 2rem 1.5rem;
        }
        
        .register-title {
            font-size: 2rem;
        }
        
        .role-options {
            flex-direction: column;
        }
        
        .role-option-register {
            min-width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .register-header {
            padding: 1.5rem 1rem;
        }
        
        .register-body {
            padding: 1.5rem 1rem;
        }
        
        .register-title {
            font-size: 1.8rem;
        }
        
        .form-control-custom {
            padding: 0.8rem 1.2rem;
        }
        
        .input-with-icon .form-control-custom {
            padding-left: 3rem;
        }
        
        .input-icon {
            left: 1.2rem;
        }
    }
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h1 class="register-title">
                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
            </h1>
            <p class="register-subtitle">Únete a nuestra plataforma educativa y comienza tu experiencia</p>
        </div>
        
        <div class="register-body">
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <!-- Campo Nombre -->
                <div class="form-group-advanced">
                    <label class="form-label-custom">
                        <i class="fas fa-user"></i>
                        <span>Nombre Completo</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-user input-icon"></i>
                        <input id="name" type="text" class="form-control-custom @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autofocus 
                               placeholder="Ingresa tu nombre completo">
                    </div>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Campo Email -->
                <div class="form-group-advanced">
                    <label class="form-label-custom">
                        <i class="fas fa-envelope"></i>
                        <span>Correo Electrónico</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" type="email" class="form-control-custom @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required 
                               placeholder="ejemplo@correo.com">
                    </div>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Campo Contraseña -->
                <div class="form-group-advanced">
                    <label class="form-label-custom">
                        <i class="fas fa-lock"></i>
                        <span>Contraseña</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" type="password" class="form-control-custom @error('password') is-invalid @enderror" 
                               name="password" required placeholder="Mínimo 8 caracteres">
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    <div class="form-note">
                        <i class="fas fa-info-circle me-1"></i>
                        La contraseña debe tener al menos 8 caracteres
                    </div>
                </div>
                
                <!-- Campo Confirmar Contraseña -->
                <div class="form-group-advanced">
                    <label class="form-label-custom">
                        <i class="fas fa-lock"></i>
                        <span>Confirmar Contraseña</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password-confirm" type="password" class="form-control-custom" 
                               name="password_confirmation" required placeholder="Repite tu contraseña">
                        <button type="button" class="password-toggle" data-target="password-confirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Campo Rol (Versión mejorada) -->
                <div class="form-group-advanced">
                    <label class="form-label-custom">
                        <i class="fas fa-user-tag"></i>
                        <span>Selecciona tu Tipo de Cuenta</span>
                    </label>
                    <p class="mb-3 text-muted">Elige el rol que mejor describa tu actividad en la plataforma:</p>
                    
                    <div class="role-options">
                        <!-- Opción Academia -->
                        <div class="role-option-register {{ old('rol') == 'academia' ? 'selected' : '' }}" data-role="academia">
                            <input type="radio" id="rol-academia" name="rol" value="academia" 
                                   {{ old('rol') == 'academia' ? 'checked' : '' }} required>
                            <div class="role-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="role-label">Academia</div>
                            <div class="role-description">Gestiono una institución educativa</div>
                        </div>
                        
                        <!-- Opción Profesor -->
                        <div class="role-option-register {{ old('rol') == 'profesor' ? 'selected' : '' }}" data-role="profesor">
                            <input type="radio" id="rol-profesor" name="rol" value="profesor" 
                                   {{ old('rol') == 'profesor' ? 'checked' : '' }}>
                            <div class="role-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="role-label">Profesor</div>
                            <div class="role-description">Imparto formación a estudiantes</div>
                        </div>
                        
                        <!-- Opción Alumno -->
                        <div class="role-option-register {{ old('rol') == 'alumno' ? 'selected' : '' }}" data-role="alumno">
                            <input type="radio" id="rol-alumno" name="rol" value="alumno" 
                                   {{ old('rol') == 'alumno' ? 'checked' : '' }}>
                            <div class="role-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="role-label">Alumno</div>
                            <div class="role-description">Recibo formación académica</div>
                        </div>
                    </div>
                    
                    @error('rol')
                        <div class="error-message mt-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Botón de Registro -->
                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>Crear Cuenta</span>
                </button>
                
                <!-- Enlace para iniciar sesión -->
                <div class="login-link">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para mostrar/ocultar contraseña
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                targetInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Selección de roles con estilo
    const roleOptions = document.querySelectorAll('.role-option-register');
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radioInput = this.querySelector('input[type="radio"]');
            
            // Remover selección de todas las opciones
            roleOptions.forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Agregar selección a la opción clickeada
            this.classList.add('selected');
            radioInput.checked = true;
            
            // Efecto de animación
            this.style.transform = 'translateY(-5px)';
            setTimeout(() => {
                this.style.transform = 'translateY(-3px)';
            }, 150);
        });
    });
    
    // Validación del formulario
    const registerForm = document.getElementById('registerForm');
    registerForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password-confirm').value;
        const selectedRole = document.querySelector('input[name="rol"]:checked');
        
        let hasError = false;
        
        // Validar nombre
        if (name.length < 2) {
            showError('name', 'El nombre debe tener al menos 2 caracteres');
            hasError = true;
        }
        
        // Validar email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('email', 'Por favor ingresa un correo electrónico válido');
            hasError = true;
        }
        
        // Validar contraseña
        if (password.length < 8) {
            showError('password', 'La contraseña debe tener al menos 8 caracteres');
            hasError = true;
        }
        
        // Validar confirmación de contraseña
        if (password !== passwordConfirm) {
            showError('password-confirm', 'Las contraseñas no coinciden');
            hasError = true;
        }
        
        // Validar rol seleccionado
        if (!selectedRole) {
            showError('rol', 'Por favor selecciona un tipo de cuenta');
            hasError = true;
        }
        
        if (hasError) {
            e.preventDefault();
            
            // Animación de shake para el formulario
            registerForm.style.animation = 'none';
            setTimeout(() => {
                registerForm.style.animation = 'shake 0.5s ease-in-out';
            }, 10);
            
            setTimeout(() => {
                registerForm.style.animation = '';
            }, 500);
        } else {
            // Cambiar el botón a estado de carga
            const submitBtn = registerForm.querySelector('.register-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creando cuenta...</span>';
            submitBtn.disabled = true;
        }
    });
    
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
        
        // Remover error previo si existe
        const existingError = field.parentElement.nextElementSibling;
        if (existingError && existingError.classList.contains('error-message')) {
            existingError.remove();
        }
        
        field.classList.add('is-invalid');
        field.parentElement.after(errorDiv);
        
        // Scroll al primer error
        if (!window.firstErrorScrolled) {
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            window.firstErrorScrolled = true;
        }
    }
    
    // Limpiar errores al escribir
    const inputs = document.querySelectorAll('.form-control-custom');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.parentElement.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });
    });
    
    // Animación para el formulario
    const formGroups = document.querySelectorAll('.form-group-advanced');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        group.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
    });
});

// Keyframes para animación de shake
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>
@endsection