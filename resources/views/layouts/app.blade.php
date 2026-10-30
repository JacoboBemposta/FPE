<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Certificados de profesionalidad. Formación para el empleo">
    <meta name="update-role-route" content="{{ route('user.updateRole') }}">
    <meta name="user-rol" content="{{ Auth::user()?->rol ?? '' }}">
    <title>{{ config('app.name', 'RedFPE') }} - Plataforma de Empleo Educativo</title>
    <link rel="icon" type="image/webp" href="{{ asset('logo.webp') }}">

    <!-- Preconnect hints for critical CDNs -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Bootstrap 5 CSS (carga asíncrona; el CSS crítico del layout va inline abajo) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
        crossorigin="anonymous"
        media="print" data-async-css>
    
    <!-- Font Awesome (carga asíncrona) -->
    <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"
        media="print" data-async-css />
    
    @if(Auth::check() || request()->routeIs('ayuda'))
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" integrity="sha384-dCW5imOdApH6OwpFau8cZNKjqVbJYnCA5q+8YsMYP3XwXKsV6Jfz1u6MZLnXaBsS" crossorigin="anonymous" media="print" data-async-css>
    @endif
    
    <!-- Google Fonts (carga asíncrona) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" media="print" data-async-css>

    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    </noscript>
    
    <!-- Activador temprano de CSS asíncrono y configuración -->
    <script src="{{ asset('public/js/config.js?v=3') }}" defer></script>

    <!-- Bootstrap 5 JS Bundle (defer) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"
            defer>
    </script>
    @if(Auth::check() || request()->routeIs('ayuda'))
    <!-- SweetAlert2 JS (defer) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" integrity="sha384-nLoOnA/BDh8A/jxqtckg4DumuCGOBYUnNJLZdQz/zfYNp3wcjGSoWTAzgko06G/2" crossorigin="anonymous" defer></script>
    @endif
    
    <style>
        /* Fallback de métricas de fuentes para evitar CLS al cargar Google Fonts */
        @font-face {
            font-family: 'Poppins-fallback';
            src: local('Arial');
            size-adjust: 112%;
            ascent-override: 93%;
            descent-override: 31%;
            line-gap-override: 9%;
        }
        @font-face {
            font-family: 'Roboto-fallback';
            src: local('Arial');
            size-adjust: 100%;
            ascent-override: 92.8%;
            descent-override: 24.4%;
            line-gap-override: 0%;
        }

        /* CSS Crítico de Layout (Bootstrap Reboot + Grid + Navbar + Utilidades esenciales) */
        *, ::after, ::before { box-sizing: border-box; }
        body { margin: 0; font-size: 1rem; font-weight: 400; line-height: 1.5; }
        h1, h2, h3, h4, h5, h6, .h4, .h5 { margin-top: 0; margin-bottom: 0.5rem; line-height: 1.2; }
        .h4, h4 { font-size: 1.5rem; }
        .h5, h5 { font-size: 1.25rem; }
        p { margin-top: 0; margin-bottom: 1rem; }
        ul { margin-top: 0; margin-bottom: 1rem; padding-left: 2rem; }
        a { color: #0d6efd; text-decoration: underline; }
        img, svg { vertical-align: middle; }
        button { font-family: inherit; font-size: inherit; line-height: inherit; margin: 0; }
        hr { margin: 1rem 0; color: inherit; border: 0; border-top: 1px solid; opacity: .25; }
        .container { width: 100%; padding-right: 0.75rem; padding-left: 0.75rem; margin-right: auto; margin-left: auto; }
        @media (min-width: 576px) { .container { max-width: 540px; } }
        @media (min-width: 768px) { .container { max-width: 720px; } .col-md-4 { flex: 0 0 auto; width: 33.33333333%; } .col-md-6 { flex: 0 0 auto; width: 50%; } .text-md-end { text-align: right !important; } }
        @media (min-width: 992px) { .container { max-width: 960px; } .col-lg-2 { flex: 0 0 auto; width: 16.66666667%; } .col-lg-4 { flex: 0 0 auto; width: 33.33333333%; } .navbar-expand-lg { flex-wrap: nowrap; justify-content: flex-start; } .navbar-expand-lg .navbar-nav { flex-direction: row; } .navbar-expand-lg .navbar-collapse { display: flex !important; flex-basis: auto; } .navbar-expand-lg .navbar-toggler { display: none; } }
        @media (min-width: 1200px) { .container { max-width: 1140px; } }
        @media (min-width: 1400px) { .container { max-width: 1320px; } }
        .row { --bs-gutter-x: 1.5rem; --bs-gutter-y: 0; display: flex; flex-wrap: wrap; margin-top: calc(-1 * var(--bs-gutter-y)); margin-right: calc(-.5 * var(--bs-gutter-x)); margin-left: calc(-.5 * var(--bs-gutter-x)); }
        .row > * { flex-shrink: 0; width: 100%; max-width: 100%; padding-right: calc(var(--bs-gutter-x) * .5); padding-left: calc(var(--bs-gutter-x) * .5); margin-top: var(--bs-gutter-y); }
        .g-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
        .navbar { position: relative; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; }
        .navbar > .container { display: flex; flex-wrap: inherit; align-items: center; justify-content: space-between; }
        .navbar-brand { text-decoration: none; white-space: nowrap; }
        .navbar-nav { display: flex; flex-direction: column; padding-left: 0; margin-bottom: 0; list-style: none; }
        .nav-link { display: block; text-decoration: none; }
        .navbar-collapse { flex-basis: 100%; flex-grow: 1; align-items: center; }
        .collapse:not(.show) { display: none; }
        .navbar-toggler { padding: 0.25rem 0.75rem; font-size: 1.25rem; line-height: 1; background-color: transparent; border: 1px solid rgba(255,255,255,0.15); border-radius: 0.375rem; cursor: pointer; }
        .navbar-toggler-icon { display: inline-block; width: 1.5em; height: 1.5em; vertical-align: middle; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: center; background-size: 100%; }
        .d-flex { display: flex !important; } .d-block { display: block !important; }
        .align-items-center { align-items: center !important; } .align-items-start { align-items: flex-start !important; }
        .justify-content-start { justify-content: flex-start !important; } .justify-content-center { justify-content: center !important; }
        .ms-auto { margin-left: auto !important; } .me-1 { margin-right: 0.25rem !important; } .me-2 { margin-right: 0.5rem !important; } .me-3 { margin-right: 1rem !important; }
        .mb-0 { margin-bottom: 0 !important; } .mb-2 { margin-bottom: 0.5rem !important; } .mb-3 { margin-bottom: 1rem !important; } .mb-4 { margin-bottom: 1.5rem !important; }
        .mt-1 { margin-top: 0.25rem !important; } .mt-3 { margin-top: 1rem !important; } .mt-5 { margin-top: 3rem !important; } .my-5 { margin-top: 3rem !important; margin-bottom: 3rem !important; }
        .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; } .p-2 { padding: 0.5rem !important; } .p-3 { padding: 1rem !important; }
        .text-white { color: #fff !important; } .text-light { color: #f8f9fa !important; } .text-info { color: #0dcaf0 !important; }
        .text-decoration-none { text-decoration: none !important; } .text-start { text-align: left !important; } .text-center { text-align: center !important; }
        .fw-bold { font-weight: 700 !important; } .list-unstyled { padding-left: 0; list-style: none; } .rounded { border-radius: 0.375rem !important; } .rounded-circle { border-radius: 50% !important; }
        .fas, .fab, .far, .fa { display: inline-block; min-width: 1em; text-align: center; }

        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --gradient-accent: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
            --border-radius: 12px;
            --border-radius-lg: 20px;
        }
        
        * {
            font-family: 'Roboto', 'Roboto-fallback', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Poppins', 'Poppins-fallback', sans-serif;
            font-weight: 600;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: var(--dark-color);
        }
        
        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        /* Navbar Estilizada */
        .navbar {
            background: var(--gradient-primary) !important;
            padding: 1rem 0;
            box-shadow: var(--shadow-md);
            border-bottom: none;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        .navbar-brand img {
            height: 32px;
            width: 32px;
            filter: brightness(0) invert(1);
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: rotate(15deg);
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 8px 16px !important;
            margin: 0 4px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-item.active .nav-link {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }
        
        .nav-item .btn {
            border-radius: 50px !important;
            padding: 8px 24px !important;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        
        .nav-item .btn-primary {
            background: var(--accent-color);
            border: none;
        }
        
        .nav-item .btn-primary:hover {
            background: #3ab8d8;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .nav-item .btn-danger {
            background: #ef4444;
            border: none;
        }
        
        .nav-item .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .dropdown-menu {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-lg);
            margin-top: 10px;
            padding: 8px;
            z-index: 1100 !important;
        }
        
        .dropdown-item {
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }
        
        /* FIX IMPORTANTE: Asegurar que el dropdown esté por encima de otros elementos */
        .nav-item.dropdown {
            position: relative;
        }
        
        /* Alertas Mejoradas */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-sm);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
        }
        
        /* Modal de Selección de Rol Mejorado */
        #roleSelectionModal .modal-content {
            border-radius: var(--border-radius-lg);
            border: none;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        
        #roleSelectionModal .modal-header {
            background: var(--gradient-primary);
            padding: 2rem;
            border-bottom: none;
        }
        
        #roleSelectionModal .modal-title {
            font-size: 1.5rem;
            color: white;
        }
        
        #roleSelectionModal .modal-body {
            padding: 2.5rem;
            background: white;
        }
        
        #roleSelectionModal .modal-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem 2.5rem;
        }
        
        .role-option {
            border: 2px solid transparent;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            margin-bottom: 1rem;
            overflow: hidden;
            position: relative;
            background: white;
            box-shadow: var(--shadow-sm);
        }
        
        .role-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .role-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }
        
        .role-option:hover::before {
            transform: scaleX(1);
        }
        
        .form-check-input:checked + .card-body {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0.1) 100%);
            border-color: var(--primary-color);
        }
        
        .form-check-input:checked + .card-body::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .role-option .form-check-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .role-option .card-body {
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .role-option i {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .role-option h6 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }
        
        .role-option small {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        #roleSelectionModal .btn-primary {
            background: var(--gradient-primary);
            border: none;
            padding: 12px 32px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        #roleSelectionModal .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        }
        
        /* Estilos para el modal de edición de perfil */
        #editProfileModal .modal-content {
            border-radius: var(--border-radius-lg);
            border: none;
            box-shadow: var(--shadow-lg);
        }
        
        #editProfileModal .form-check {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        
        #editProfileModal .form-check:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        #editProfileModal .form-check-input:checked + .form-check-label {
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        #editProfileModal .form-check-label {
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
        }
        
        #editProfileModal .form-check-label i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        #editProfileModal .form-check-input {
            display: none;
        }
        
        #editProfileModal .form-check-input:checked + .form-check-label {
            border-color: var(--primary-color);
        }
        
        /* Estilos para mensajes flash */
        .container.mt-3 {
            margin-top: 1.5rem !important;
            max-width: 1200px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
                padding: 6px 12px;
            }
            
            .navbar-nav {
                padding: 1rem 0;
            }
            
            .nav-item .btn {
                margin: 5px 0;
                width: 100%;
            }
            
            #roleSelectionModal .modal-body {
                padding: 1.5rem;
            }
            
            .role-option .card-body {
                padding: 1.5rem 1rem;
            }
        }
        
        /* Animación para el modal */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #roleSelectionModal .modal-content {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }
        
        /* Efectos de hover generales */
        .btn, .card, .nav-link {
            transition: all 0.3s ease;
        }

        /* Estilos para el footer */
        .footer-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .footer-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4361ee, #3a0ca3, #4cc9f0);
            background-size: 200% 100%;
            animation: gradientAnimation 3s ease infinite;
        }
        
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .footer-section a:hover {
            opacity: 1 !important;
            color: #4cc9f0 !important;
            transform: translateX(5px);
        }
        
        .social-links a:hover {
            opacity: 1 !important;
            color: #4cc9f0 !important;
            transform: translateY(-3px);
        }
        
        .footer-bottom-links a:hover {
            color: #4cc9f0 !important;
        }
        
        /* Estilos responsivos */
        @media (max-width: 768px) {
            .footer-section .col-lg-4.col-md-6 {
                text-align: center;
            }
            
            .footer-section .col-lg-4.col-md-6 .d-flex {
                justify-content: center;
            }
            
            .footer-section .col-lg-2.col-md-6,
            .footer-section .col-lg-3.col-md-6 {
                text-align: left !important;
            }
            
            .col-lg-3.col-md-6.text-start.text-md-start,
            .col-lg-3.col-md-6.text-start.text-md-start ul,
            .col-lg-3.col-md-6.text-start.text-md-start li,
            .col-lg-3.col-md-6.text-start.text-md-start .social-links {
                text-align: center !important;
                justify-content: flex-start !important;
            }
            
            .footer-section li {
                justify-content: flex-start !important;
            }
            
            .footer-bottom-links {
                margin-top: 15px;
                text-align: center;
            }
            
            .footer-section .col-lg-2.col-md-6 .d-flex,
            .footer-section .col-lg-3.col-md-6 .d-flex:not(.justify-content-center) {
                justify-content: flex-start !important;
            }
        }        


        .btn-navbar-bg {
            background: rgba(255, 255, 255, 0.15) !important;
            border: none !important;
            color: white !important;
            border-radius: 50px !important;
            padding: 8px 24px !important;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-navbar-bg:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            color: white !important;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* Estilos específicos para los íconos dentro del botón */
        .btn-navbar-bg i {
            color: white !important;
        }    
    </style>
</head>
<body>
    <div id="app">
        <!-- Navbar Moderna -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('logo.webp') }}" alt="RedFPE Logo" width="80" height="80" fetchpriority="high" style="height: 80px; width: 80px; object-fit: contain;">
                    RedFPE
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="filter: brightness(0) invert(1);"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> Iniciar sesión
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> Registrarse
                                </a>
                            </li>
                        @else
                            <!-- BOTÓN SOLO PARA ADMIN -->
                            @if(Auth::user()->rol === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.stats') }}">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        <span>Estadísticas</span>
                                    </a>
                                </li>
                                <li class="nav-item ms-2">
                                    <a class="btn btn-navbar-bg px-4" href="{{ route('admin.panel') }}">
                                        <i class="fas fa-cogs me-2"></i>Panel Admin
                                    </a>
                                </li>
                            @endif

                            <!-- BOTÓN SOLO PARA ACADEMIA -->
                            @if(Auth::user()->rol === 'academia')
                                <li class="nav-item ms-2">
                                    <a class="btn btn-navbar-bg px-4" href="{{ route('academia.miscursos') }}">
                                        <i class="fas fa-university me-2"></i>Panel Academia
                                    </a>
                                </li>
                            @endif

                            <!-- BOTÓN SOLO PARA PROFESOR -->
                            @if(Auth::user()->rol === 'profesor')
                                <li class="nav-item ms-2">
                                    <a class="btn btn-navbar-bg px-4" href="{{ route('profesor.miscursos') }}">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Panel Docente
                                    </a>
                                </li>
                            @endif
                            
                            <!-- Menú de usuario -->
                            <li class="nav-item dropdown ms-3">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="bg-white rounded-circle p-2 me-2" style="width: 36px; height: 36px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <span>{{ Auth::user()?->name ?? 'Usuario' }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                            <i class="fas fa-user-edit me-2"></i>Editar Perfil
                                        </a>
                                    </li>
                                    
                                    <!-- Nueva opción para eliminar cuenta -->
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            <i class="fas fa-trash-alt me-2"></i>Eliminar cuenta
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger" style="background: none; border: none; width: 100%; text-align: left; padding: 10px 16px;">
                                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenedor para mensajes flash -->
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Contenido Principal -->
        <main>
            @yield('content')
        </main>

        <!-- Footer Actualizado -->
        <footer class="footer-section py-5 mt-5">
            <div class="container">
                <div class="row g-4">
                    <!-- Columna 1: Logo y descripción -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-logo mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('logo.webp') }}" alt="RedFPE Logo" width="100" height="100" loading="lazy" style="height: 100px; width: 100px; filter: brightness(0) invert(1);" class="me-2">
                                <h2 class="h4 text-white mb-0">RedFPE</h2>
                            </div>
                            <p class="text-light mb-4" style="opacity: 0.8;">
                                Plataforma educativa que conecta academias, docentes y alumnos en un ecosistema completo y eficiente.
                            </p>
                            <div class="privacy-badge p-3 rounded" style="background: rgba(255, 255, 255, 0.1); border-left: 3px solid #4cc9f0;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt text-info me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-light fw-bold d-block">Sin cookies de seguimiento</small>
                                        <small class="text-light" style="opacity: 0.7; font-size: 0.8rem;">Respetamos tu privacidad. Solo usamos cookies esenciales.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Columna 2: Enlaces rápidos -->
                    <div class="col-lg-2 col-md-6">
                        <h3 class="h5 text-white mb-4 text-start text-md-start" style="position: relative; padding-bottom: 10px;">
                            Enlaces
                            <span style="position: absolute; bottom: 0; left: 0; width: 30px; height: 2px; background: linear-gradient(90deg, #4cc9f0, #4361ee);"></span>
                        </h3>
                        <ul class="list-unstyled text-start text-md-start">
                            <li class="mb-2">
                                <a href="{{ url('/') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-home me-2" style="width: 20px;"></i>
                                    <span>Inicio</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('about') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-info-circle me-2" style="width: 20px;"></i>
                                    <span>Sobre Nosotros</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('privacy') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-user-shield me-2" style="width: 20px;"></i>
                                    <span>Privacidad</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('terms') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-file-contract me-2" style="width: 20px;"></i>
                                    <span>Términos</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Columna 3: Para usuarios -->
                    <div class="col-lg-2 col-md-6">
                        <h3 class="h5 text-white mb-4 text-start text-md-start" style="position: relative; padding-bottom: 10px;">
                            Para Usuarios
                            <span style="position: absolute; bottom: 0; left: 0; width: 30px; height: 2px; background: linear-gradient(90deg, #4cc9f0, #4361ee);"></span>
                        </h3>
                        <ul class="list-unstyled text-start text-md-start">
                            <li class="mb-2">
                                <a href="{{ route('academias') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-university me-2" style="width: 20px;"></i>
                                    <span>Academias</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('docentes') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-chalkboard-teacher me-2" style="width: 20px;"></i>
                                    <span>Docentes</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('alumnos') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-user-graduate me-2" style="width: 20px;"></i>
                                    <span>Alumnos</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('ayuda') }}" class="text-light text-decoration-none d-flex align-items-center justify-content-start" style="opacity: 0.8; transition: all 0.3s ease;">
                                    <i class="fas fa-question-circle me-2" style="width: 20px;"></i>
                                    <span>Ayuda</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Columna 4: Contacto -->
                    <div class="col-lg-2 col-md-6 text-start text-md-start">
                        <h3 class="h5 text-white mb-4 text-start" style="position: relative; padding-bottom: 10px;">
                            Contacto
                            <span style="position: absolute; bottom: 0; left: 0; width: 30px; height: 2px; background: linear-gradient(90deg, #4cc9f0, #4361ee);"></span>
                        </h3>
                        
                        <ul class="list-unstyled mb-0" style="opacity: 0.8;">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-envelope mt-1 me-3" style="color: #4cc9f0; width: 20px;"></i>
                                <span class="text-start d-block">info@redfpe.com</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-phone mt-1 me-3" style="color: #4cc9f0; width: 20px;"></i>
                                <span class="text-start d-block">+34 635 15 62 63</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6 text-start text-md-start">
                        <h3 class="h5 text-white mb-4 text-start" style="position: relative; padding-bottom: 10px;">
                            Comunidades colaboradoras
                            <span style="position: absolute; bottom: 0; left: 0; width: 30px; height: 2px; background: linear-gradient(90deg, #4cc9f0, #4361ee);"></span>
                        </h3>
                        <div class="social-links mt-5">
                            <a href="#" aria-label="LinkedIn de RedFPE" class="text-light me-3" style="opacity: 0.7; transition: all 0.3s ease; text-decoration: none;">
                                <i class="fab fa-linkedin fa-lg" aria-hidden="true"></i>
                            </a>
                            <a href="#" aria-label="Canal de Telegram de RedFPE" class="text-light me-3" style="opacity: 0.7; transition: all 0.3s ease; text-decoration: none;">
                                <i class="fab fa-telegram fa-lg" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <hr class="my-5" style="border-color: rgba(255, 255, 255, 0.1);">
                
                <!-- Fila inferior -->
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-light mb-0" style="opacity: 0.7;">
                            &copy; {{ date('Y') }} RedFPE. Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-bottom-links">
                            <a href="{{ route('privacy') }}" class="text-light text-decoration-none me-3" style="opacity: 0.7; font-size: 0.9rem;">Política de Privacidad</a>
                            <a href="{{ route('terms') }}" class="text-light text-decoration-none me-3" style="opacity: 0.7; font-size: 0.9rem;">Términos de Uso</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- ============================================== -->
    <!-- MODAL 1: PARA SELECCIÓN DE ROL (PRIMERA VEZ) -->
    <!-- ============================================== -->
    @auth
    @if(is_null(Auth::user()->rol) && !Session::has('role_selected'))
    <div class="modal fade" id="roleSelectionModal" tabindex="-1" aria-labelledby="roleSelectionModalLabel" 
         aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleSelectionModalLabel">
                        <i class="fas fa-user-tie me-2"></i>Selecciona tu tipo de cuenta
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4 text-center fs-5">Elige el rol que mejor se adapte a tu actividad en la plataforma:</p>
                    
                    <form id="roleSelectionForm">
                        @csrf
                        <div class="row g-4">
                            <!-- Academia -->
                            <div class="col-md-4">
                                <div class="form-check card role-option h-100">
                                    <input class="form-check-input" type="radio" name="rol" id="rolAcademia" value="academia" required>
                                    <label class="form-check-label card-body" for="rolAcademia">
                                        <i class="fas fa-university"></i>
                                        <h6 class="fw-bold mt-3">Academia</h6>
                                        <small class="text-muted">Gestiono cursos, docentes y estudiantes</small>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Profesor -->
                            <div class="col-md-4">
                                <div class="form-check card role-option h-100">
                                    <input class="form-check-input" type="radio" name="rol" id="rolProfesor" value="profesor" required>
                                    <label class="form-check-label card-body" for="rolProfesor">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <h6 class="fw-bold mt-3">Docente</h6>
                                        <small class="text-muted">Imparto formación y evalúo estudiantes</small>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Alumno -->
                            <div class="col-md-4">
                                <div class="form-check card role-option h-100">
                                    <input class="form-check-input" type="radio" name="rol" id="rolAlumno" value="alumno" required>
                                    <label class="form-check-label card-body" for="rolAlumno">
                                        <i class="fas fa-user-graduate"></i>
                                        <h6 class="fw-bold mt-3">Alumno</h6>
                                        <small class="text-muted">Participo en cursos y recibo formación</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary px-5" id="confirmRoleBtn">
                        <i class="fas fa-rocket me-2"></i>Confirmar y Continuar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth

    <!-- ============================================== -->
    <!-- MODAL 2: PARA EDITAR PERFIL -->
    <!-- ============================================== -->
    @auth
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--gradient-primary); color: white;">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="fas fa-user-edit me-2"></i>Editar Perfil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProfileForm" action="{{ route('user.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editName" name="name" 
                                   value="{{ Auth::user()->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="form-check form-check-inline w-100">
                                        <input class="form-check-input" type="radio" name="rol" id="editRolAcademia" 
                                               value="academia" {{ Auth::user()->rol === 'academia' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 text-center" for="editRolAcademia">
                                            <i class="fas fa-university d-block mb-1"></i>
                                            <small>Academia</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check form-check-inline w-100">
                                        <input class="form-check-input" type="radio" name="rol" id="editRolProfesor" 
                                               value="profesor" {{ Auth::user()->rol === 'profesor' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 text-center" for="editRolProfesor">
                                            <i class="fas fa-chalkboard-teacher d-block mb-1"></i>
                                            <small>Docente</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check form-check-inline w-100">
                                        <input class="form-check-input" type="radio" name="rol" id="editRolAlumno" 
                                               value="alumno" {{ Auth::user()->rol === 'alumno' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 text-center" for="editRolAlumno">
                                            <i class="fas fa-user-graduate d-block mb-1"></i>
                                            <small>Alumno</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" 
                                   value="{{ Auth::user()->email }}" readonly>
                            <small class="text-muted">El email no se puede modificar</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar cuenta permanentemente -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Eliminar cuenta permanentemente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form id="deleteAccountForm" action="{{ route('user.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">
                                <i class="fas fa-skull-crossbones me-2"></i>ADVERTENCIA: ACCIÓN IRREVERSIBLE
                            </h5>
                            <p class="mb-0">
                                Estás a punto de eliminar permanentemente tu cuenta y todos los datos asociados. 
                                Esta acción <strong>NO SE PUEDE DESHACER</strong>.
                            </p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-danger mb-3">
                                    <div class="card-header bg-danger text-white">
                                        <i class="fas fa-times-circle me-1"></i> ¿Qué se eliminará?
                                    </div>
                                    <div class="card-body">
                                        <ul class="mb-0">
                                            <li>Todos tus datos personales</li>
                                            <li>Historial de cursos</li>
                                            <li>Registros de actividad</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-warning mb-3">
                                    <div class="card-header bg-warning text-white">
                                        <i class="fas fa-exclamation me-1"></i> Consecuencias
                                    </div>
                                    <div class="card-body">
                                        <ul class="mb-0">
                                            <li>Acceso perdido permanentemente</li>
                                            <li>No podrás recuperar tus datos</li>
                                            <li>Se perderán todos los cursos</li>
                                            <li>La acción es inmediata</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Requisitos de confirmación -->
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-bold mb-3">Para confirmar la eliminación:</h6>
                            
                            <!-- Confirmación 1: Escribir frase específica -->
                            <div class="mb-3">
                                <label for="confirmationPhrase" class="form-label">
                                    Escribe <code class="bg-danger text-white px-2 rounded">ELIMINAR MI CUENTA</code> para confirmar:
                                </label>
                                <input type="text" 
                                    class="form-control" 
                                    id="confirmationPhrase" 
                                    name="confirmation_phrase"
                                    placeholder="Escribe exactamente: ELIMINAR MI CUENTA"
                                    required>
                                <div class="form-text">Debe coincidir exactamente, incluyendo mayúsculas.</div>
                            </div>
                            
                            <!-- Confirmación 2: Contraseña actual -->
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Contraseña actual:</label>
                                <input type="password" 
                                    class="form-control" 
                                    id="currentPassword" 
                                    name="current_password"
                                    placeholder="Introduce tu contraseña actual"
                                    required>
                            </div>
                            
                            <!-- Confirmación 3: Checkbox final -->
                            <div class="form-check">
                                <input class="form-check-input" 
                                    type="checkbox" 
                                    id="finalConfirmation" 
                                    name="final_confirmation"
                                    required>
                                <label class="form-check-label text-danger fw-bold" for="finalConfirmation">
                                    Confirmo que comprendo que esta acción es irreversible y deseo proceder con la eliminación permanente de todos mis datos.
                                </label>
                            </div>
                        </div>
                        
                        <!-- Contador de confirmación -->
                        <div class="text-center mb-3">
                            <div class="badge bg-secondary p-2">
                                Confirmaciones completadas: 
                                <span id="confirmationsCount">0</span>/3
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" 
                                class="btn btn-danger" 
                                id="confirmDeleteButton"
                                disabled>
                            <i class="fas fa-trash-alt me-1"></i> ELIMINAR CUENTA PERMANENTEMENTE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/js/perfil.js?v=2') }}" defer></script>
    @endauth

    @yield('scripts')
    @stack('scripts')
</body>
</html>