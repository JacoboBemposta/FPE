@extends('layouts.app')

@section('content')

<style>
    /* =========================================
       ESTILOS PÁGINA DE REGISTRO
       ========================================= */

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

    /* =========================================
       CABECERA
       ========================================= */

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

    /* =========================================
       CUERPO
       ========================================= */

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

    /* =========================================
       CAMPOS
       ========================================= */

    .form-control-custom {
        border: 2px solid #e2e8f0;
        border-radius: var(--border-radius);
        padding: 1rem 1.5rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        width: 100%;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        background: white;
        transform: translateY(-2px);
        outline: none;
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
        pointer-events: none;
    }

    .input-with-icon .form-control-custom {
        padding-left: 3.5rem;
    }

    /* =========================================
       MOSTRAR / OCULTAR CONTRASEÑA
       ========================================= */

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

    /* =========================================
       ROLES
       ========================================= */

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
        display: block;
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

    .role-option-register:has(input[type="radio"]:checked) {
        border-color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }

    .role-option-register:has(input[type="radio"]:checked)::before {
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

    /*
     * El radio NO se elimina con display:none.
     * Se mantiene accesible para la validación HTML5.
     */
    .role-option-register input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        margin: 0;
        pointer-events: none;
    }

    .role-option-register:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
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

    /* =========================================
       BOTÓN REGISTRO
       ========================================= */

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
        cursor: pointer;
    }

    .register-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
    }

    .register-btn:active {
        transform: translateY(-1px);
    }

    .register-btn:disabled {
        opacity: 0.7;
        cursor: wait;
        transform: none;
    }

    /* =========================================
       ENLACE LOGIN
       ========================================= */

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

    /* =========================================
       ERRORES
       ========================================= */

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

    /* =========================================
       RESPONSIVE
       ========================================= */

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

```
<div class="register-card">

    <!-- CABECERA -->

    <div class="register-header">

        <h1 class="register-title">
            <i class="fas fa-user-plus me-2"></i>
            Crear Cuenta
        </h1>

        <p class="register-subtitle">
            Únete a nuestra plataforma educativa y comienza tu experiencia
        </p>

    </div>


    <!-- CUERPO -->

    <div class="register-body">

        <form method="POST" action="{{ route('register') }}" id="registerForm">

            @csrf


            <!-- =====================================
                 NOMBRE
                 ===================================== -->

            <div class="form-group-advanced">

                <label class="form-label-custom" for="name">

                    <i class="fas fa-user"></i>

                    <span>Nombre Completo</span>

                </label>


                <div class="input-with-icon">

                    <i class="fas fa-user input-icon"></i>

                    <input
                        id="name"
                        type="text"
                        class="form-control-custom @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Ingresa tu nombre completo"
                    >

                </div>


                @error('name')

                    <div class="error-message">

                        <i class="fas fa-exclamation-circle"></i>

                        <span>{{ $message }}</span>

                    </div>

                @enderror

            </div>


            <!-- =====================================
                 EMAIL
                 ===================================== -->

            <div class="form-group-advanced">

                <label class="form-label-custom" for="email">

                    <i class="fas fa-envelope"></i>

                    <span>Correo Electrónico</span>

                </label>


                <div class="input-with-icon">

                    <i class="fas fa-envelope input-icon"></i>

                    <input
                        id="email"
                        type="email"
                        class="form-control-custom @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="ejemplo@correo.com"
                    >

                </div>


                @error('email')

                    <div class="error-message">

                        <i class="fas fa-exclamation-circle"></i>

                        <span>{{ $message }}</span>

                    </div>

                @enderror

            </div>


            <!-- =====================================
                 CONTRASEÑA
                 ===================================== -->

            <div class="form-group-advanced">

                <label class="form-label-custom" for="password">

                    <i class="fas fa-lock"></i>

                    <span>Contraseña</span>

                </label>


                <div class="input-with-icon">

                    <i class="fas fa-lock input-icon"></i>

                    <input
                        id="password"
                        type="password"
                        class="form-control-custom @error('password') is-invalid @enderror"
                        name="password"
                        required
                        autocomplete="new-password"
                        minlength="8"
                        placeholder="Mínimo 8 caracteres"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="password"
                        aria-label="Mostrar contraseña"
                    >
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


            <!-- =====================================
                 CONFIRMAR CONTRASEÑA
                 ===================================== -->

            <div class="form-group-advanced">

                <label class="form-label-custom" for="password-confirm">

                    <i class="fas fa-lock"></i>

                    <span>Confirmar Contraseña</span>

                </label>


                <div class="input-with-icon">

                    <i class="fas fa-lock input-icon"></i>

                    <input
                        id="password-confirm"
                        type="password"
                        class="form-control-custom"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        minlength="8"
                        placeholder="Repite tu contraseña"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="password-confirm"
                        aria-label="Mostrar contraseña"
                    >
                        <i class="fas fa-eye"></i>
                    </button>

                </div>

            </div>


            <!-- =====================================
                 ROL
                 ===================================== -->

            <div class="form-group-advanced">

                <div class="form-label-custom">

                    <i class="fas fa-user-tag"></i>

                    <span>Selecciona tu Tipo de Cuenta</span>

                </div>


                <p class="mb-3 text-muted">
                    Elige el rol que mejor describa tu actividad en la plataforma:
                </p>


                <div class="role-options">


                    <!-- ACADEMIA -->

                    <label class="role-option-register">

                        <input
                            type="radio"
                            id="rol-academia"
                            name="rol"
                            value="academia"
                            {{ old('rol') == 'academia' ? 'checked' : '' }}
                            required
                        >

                        <div class="role-icon">
                            <i class="fas fa-university"></i>
                        </div>

                        <div class="role-label">
                            Academia
                        </div>

                        <div class="role-description">
                            Gestiono una institución educativa
                        </div>

                    </label>


                    <!-- PROFESOR -->

                    <label class="role-option-register">

                        <input
                            type="radio"
                            id="rol-profesor"
                            name="rol"
                            value="profesor"
                            {{ old('rol') == 'profesor' ? 'checked' : '' }}
                        >

                        <div class="role-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>

                        <div class="role-label">
                            Profesor
                        </div>

                        <div class="role-description">
                            Imparto formación a estudiantes
                        </div>

                    </label>


                    <!-- ALUMNO -->

                    <label class="role-option-register">

                        <input
                            type="radio"
                            id="rol-alumno"
                            name="rol"
                            value="alumno"
                            {{ old('rol') == 'alumno' ? 'checked' : '' }}
                        >

                        <div class="role-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div class="role-label">
                            Alumno
                        </div>

                        <div class="role-description">
                            Recibo formación académica
                        </div>

                    </label>

                </div>


                @error('rol')

                    <div class="error-message mt-2">

                        <i class="fas fa-exclamation-circle"></i>

                        <span>{{ $message }}</span>

                    </div>

                @enderror

            </div>


            <!-- =====================================
                 BOTÓN REGISTRO
                 ===================================== -->

            <button
                type="submit"
                class="register-btn"
                id="registerSubmit"
            >

                <i class="fas fa-user-plus"></i>

                <span>Crear Cuenta</span>

            </button>


            <!-- =====================================
                 LOGIN
                 ===================================== -->

            <div class="login-link">

                ¿Ya tienes una cuenta?

                <a href="{{ route('login') }}">

                    <i class="fas fa-sign-in-alt me-1"></i>

                    Iniciar Sesión

                </a>

            </div>

        </form>

    </div>

</div>
```

</div>

@endsection
