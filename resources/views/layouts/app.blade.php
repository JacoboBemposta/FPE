<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <!-- Navbar básico pero funcional -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

<!-- Modal para seleccionar rol -->
@auth
@if(session('show_role_modal') || is_null(Auth::user()->rol))
<div class="modal fade show" id="roleSelectionModal" tabindex="-1" aria-labelledby="roleSelectionModalLabel" aria-hidden="false" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roleSelectionModalLabel">
                    <i class="fas fa-user-tie me-2"></i>Selecciona tu tipo de cuenta
                </h5>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Para personalizar tu experiencia, por favor selecciona el tipo de cuenta que mejor describa tu perfil:</p>
                
                <form id="roleSelectionForm" action="{{ route('user.updateRole') }}" method="POST">
                    @csrf
                    <div class="row g-3 justify-content-center">
                        <!-- Academia -->
                        <div class="col-md-6">
                            <div class="form-check card role-option">
                                <input class="form-check-input" type="radio" name="rol" id="rolAcademia" value="academia" required>
                                <label class="form-check-label card-body text-center" for="rolAcademia">
                                    <i class="fas fa-university fa-2x text-primary mb-3"></i>
                                    <h6 class="fw-bold">Academia</h6>
                                    <small class="text-muted">Gestiono cursos y estudiantes</small>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Profesor -->
                        <div class="col-md-6">
                            <div class="form-check card role-option">
                                <input class="form-check-input" type="radio" name="rol" id="rolProfesor" value="profesor" required>
                                <label class="form-check-label card-body text-center" for="rolProfesor">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-success mb-3"></i>
                                    <h6 class="fw-bold">Profesor</h6>
                                    <small class="text-muted">Imparto formación</small>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Alumno -->
                        <div class="col-md-6">
                            <div class="form-check card role-option">
                                <input class="form-check-input" type="radio" name="rol" id="rolAlumno" value="alumno" required>
                                <label class="form-check-label card-body text-center" for="rolAlumno">
                                    <i class="fas fa-user-graduate fa-2x text-info mb-3"></i>
                                    <h6 class="fw-bold">Alumno</h6>
                                    <small class="text-muted">Recibo formación</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    @error('rol')
                        <div class="alert alert-danger mt-3">{{ $message }}</div>
                    @enderror
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="roleSelectionForm" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i>Confirmar y Continuar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .role-option {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 100%;
        margin-bottom: 1rem;
    }
    
    .role-option:hover {
        border-color: #007bff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
    }
    
    .form-check-input:checked + .card-body {
        background-color: rgba(0, 123, 255, 0.05);
        border-color: #007bff;
    }
    
    .role-option .form-check-input {
        position: absolute;
        top: 10px;
        left: 10px;
    }
    
    .role-option .card-body {
        padding: 1.5rem 1rem;
    }
    
    #roleSelectionModal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleOptions = document.querySelectorAll('.role-option');
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Remover clase activa de todas las opciones
            roleOptions.forEach(opt => opt.classList.remove('active'));
            // Agregar clase activa a la opción seleccionada
            this.classList.add('active');
        });
    });

    // Validación del formulario
    const roleForm = document.getElementById('roleSelectionForm');
    roleForm.addEventListener('submit', function(e) {
        const selectedRole = document.querySelector('input[name="rol"]:checked');
        if (!selectedRole) {
            e.preventDefault();
            alert('Por favor, selecciona un tipo de cuenta para continuar.');
        }
    });
});
</script>
@endif
@endauth 
</body>
</html>