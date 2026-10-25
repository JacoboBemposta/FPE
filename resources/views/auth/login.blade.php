@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100 bg-light">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="card shadow-lg border-0 rounded-3">
                <!-- Header con gradiente -->
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h2 class="mb-0 fw-bold"><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</h2>
                    <p class="mb-0 mt-2 opacity-75">Accede a tu cuenta de RedFPE</p>
                </div>

                <div class="card-body p-5">
                    <!-- Botón de Google -->
                    <div class="d-grid mb-4">
                        <a href="{{ route('login.google') }}" class="btn btn-outline-danger btn-lg py-3 fw-semibold">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" width="20" height="20" class="me-3">
                            Continuar con Google
                        </a>
                    </div>

                    <!-- Separador -->
                    <div class="position-relative text-center my-4">
                        <hr>
                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                            o ingresa con tu email
                        </span>
                    </div>

                    <!-- Formulario tradicional -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating mb-4">
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                                   placeholder="name@example.com">
                            <label for="email" class="text-muted">
                                <i class="fas fa-envelope me-2"></i>Correo Electrónico
                            </label>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password" placeholder="Contraseña">
                            <label for="password" class="text-muted">
                                <i class="fas fa-lock me-2"></i>Contraseña
                            </label>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    Recordarme
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a class="text-primary text-decoration-none" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-semibold">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <!-- Enlace a registro -->
                    <div class="text-center pt-3 border-top">
                        <p class="text-muted mb-0">¿No tienes una cuenta?
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .min-vh-100 {
        min-height: 100vh;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .card {
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }
    
    .form-control-lg {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-lg {
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .btn-outline-danger {
        border: 2px solid;
        font-weight: 600;
    }
    
    .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .form-floating label {
        padding-left: 2.5rem;
    }
    
    .form-floating i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 5;
    }
</style>
@endsection