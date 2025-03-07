<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a la Plataforma Educativa</title>
    <!-- Agregar Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: #007bff;
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .role-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            transition: all 0.3s ease;
        }
        .role-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        .role-card img {
            height: 100px;
            width: auto;
            display: block;
            margin: 0 auto 10px;
        }

    </style>
</head>
<body>

<!-- Barra de navegación con login y registro -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Plataforma Educativa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    <!-- BOTÓN SOLO PARA ADMIN -->
                    @if(Auth::user()->rol === 'admin')
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3" href="{{ route('admin.panel') }}">Panel Admin</a>
                        </li>
                    @endif

                    <!-- BOTÓN SOLO PARA ACADEMIA -->
                    @if(Auth::user()->rol === 'academia')
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3" href="{{ route('academia.miscursos') }}">Panel Academia</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Bienvenido a SAFAPE</h1>
        <h2 class="display-4">La plataforma que agiliza la gestión académica y conecta academias con profesores</h2>
        <p class="lead">Gestiona cursos, exámenes y más para academias, profesores y alumnos.</p>
        <a href="#roles" class="btn btn-light btn-lg">Descubre más</a>
    </div>
</section>

<!-- Roles Section -->
<section id="roles" class="container my-5">
    <h2 class="text-center mb-4">Selecciona tu rol para comenzar</h2>

    <div class="row">
        <!-- Card para Academias -->
        <div class="col-md-4">
            <div class="role-card">
                <img src="{{ asset('images/academia.png') }}" alt="Academia">
                <h4>Academia</h4>
                <p>Gestiona cursos y asigna profesores. Haz crecer tu institución educativa.</p>
                
                @auth
                    @if(Auth::user()->role === 'academia')
                        <a href="{{ route('academia.cursos') }}" class="btn btn-primary">Acceder</a>
                    @else
                        <button class="btn btn-secondary" disabled>Acceso restringido</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
                @endauth
            </div>
        </div>

        <!-- Card para Profesores -->
        <div class="col-md-4">
            <div class="role-card">
                <img src="{{ asset('images/profesor.png') }}" alt="Profesor">
                <h4>Profesor</h4>
                <p>Imparte clases y realiza seguimientos de los alumnos y exámenes.</p>

                @auth
                    @if(Auth::user()->role === 'profesor')
                        <a href="{{ route('profesor.dashboard') }}" class="btn btn-primary">Acceder</a>
                    @else
                        <button class="btn btn-secondary" disabled>Acceso restringido</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
                @endauth
            </div>
        </div>

        <!-- Card para Alumnos -->
        <div class="col-md-4">
            <div class="role-card">
                <img src="{{ asset('images/alumno.png') }}" alt="Alumno">
                <h4>Alumno</h4>
                <p>Accede a tus cursos, exámenes y resultados de manera sencilla.</p>

                @auth
                    @if(Auth::user()->role === 'alumno')
                        <a href="{{ route('alumno.dashboard') }}" class="btn btn-primary">Acceder</a>
                    @else
                        <button class="btn btn-secondary" disabled>Acceso restringido</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <p>&copy; 2025 Plataforma Educativa. Todos los derechos reservados.</p>
</footer>

<!-- Agregar los Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
