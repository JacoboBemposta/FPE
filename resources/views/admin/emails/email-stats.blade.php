{{-- @extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar mr-2"></i>Panel de Estadísticas
            </h1>
            <p class="text-muted">Estadísticas completas de la plataforma</p>
        </div>
    </div>

    {{-- SECCIÓN DE ESTADÍSTICAS DE USUARIOS --}}
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4 class="h5 mb-0 text-gray-800">
                <i class="fas fa-users mr-2"></i>Estadísticas de Usuarios
            </h4>
            <hr class="mt-2">
        </div>

        {{-- Tarjetas de usuarios totales --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['total_usuarios'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Academias</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['total_academias'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Docentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['total_profesores'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Alumnos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['total_alumnos'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjetas adicionales de usuarios --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['usuarios_activos'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Inactivos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($userStats['usuarios_inactivos'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribución de usuarios por rol --}}
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i>Distribución de Usuarios por Rol
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Rol</th>
                                    <th>Cantidad</th>
                                    <th>Porcentaje</th>
                                    <th>Gráfico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalUsers = $userStats['total_usuarios']; @endphp
                                @foreach($usuariosPorRol as $rol)
                                @php 
                                    $porcentaje = $totalUsers > 0 ? ($rol->total / $totalUsers) * 100 : 0;
                                    $color = match($rol->rol) {
                                        'academia' => 'primary',
                                        'profesor' => 'success',
                                        'alumno' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-{{ $color }}">
                                            @if($rol->rol === 'academia')
                                                Academia
                                            @elseif($rol->rol === 'profesor')
                                                Docente
                                            @elseif($rol->rol === 'alumno')
                                                Alumno
                                            @else
                                                {{ $rol->rol }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ number_format($rol->total) }}</td>
                                    <td>{{ number_format($porcentaje, 1) }}%</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $color }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $porcentaje }}%"
                                                 aria-valuenow="{{ $porcentaje }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th>Total</th>
                                    <th>{{ number_format($totalUsers) }}</th>
                                    <th>100%</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Evolución de Registros (últimos 6 meses)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Mes</th>
                                    <th>Nuevos Usuarios</th>
                                    <th>Gráfico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $maxUsuarios = $evolucionUsuarios->max('total'); @endphp
                                @foreach($evolucionUsuarios as $item)
                                @php
                                    $width = $maxUsuarios > 0 ? ($item->total / $maxUsuarios) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->mes . '-01')->format('M Y') }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-info" 
                                                 role="progressbar" 
                                                 style="width: {{ $width }}%"
                                                 aria-valuenow="{{ $item->total }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $maxUsuarios }}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE ESTADÍSTICAS DE SUSCRIPCIONES --}}
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4 class="h5 mb-0 text-gray-800">
                <i class="fas fa-credit-card mr-2"></i>Estadísticas de Suscripciones
            </h4>
            <hr class="mt-2">
        </div>

        {{-- Tarjetas de estadísticas de suscripciones --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Suscripciones Activas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subscriptionStats['suscripciones_activas'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ingresos Este Mes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($subscriptionStats['ingresos_mes_actual'] ?? 0, 2) }}€</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Ingresos Año Actual</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($subscriptionStats['ingresos_anio_actual'] ?? 0, 2) }}€</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Por Vencer (7 días)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subscriptionStats['suscripciones_por_vencer'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribución de suscripciones --}}
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i>Distribución de Suscripciones por Tipo
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingresosPorTipo as $tipo)
                                <tr>
                                    <td>
                                        <span class="badge badge-{{ $tipo->tipo == 'academia' ? 'success' : 'primary' }}">
                                            {{ $tipo->tipo == 'academia' ? 'Academias' : 'Docentes' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $tipo->total_suscripciones }}
                                    </td>
                                    <td>
                                        {{ number_format($tipo->total_ingresos, 2) }}€
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-2"></i>Suscripciones por Plan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Plan</th>
                                    <th>Suscripciones</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSuscripciones = $subscriptionStats['total_suscripciones'] ?? 1; @endphp
                                @foreach($suscripcionesPorPlan as $plan)
                                @php $porcentaje = ($plan->total / $totalSuscripciones) * 100; @endphp
                                <tr>
                                    <td>{{ $plan->plan }}</td>
                                    <td>{{ $plan->total }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-info" 
                                                 role="progressbar" 
                                                 style="width: {{ $porcentaje }}%"
                                                 aria-valuenow="{{ $plan->total }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $totalSuscripciones }}">
                                                {{ number_format($porcentaje, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top suscriptores y evolución de ingresos --}}
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy mr-2"></i>Top 10 Suscriptores
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Total Pagado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSuscriptores as $index => $suscriptor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $suscriptor->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $suscriptor->rol == 'academia' ? 'success' : 'primary' }}">
                                            {{ $suscriptor->rol == 'academia' ? 'Academia' : 'Docente' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($suscriptor->total_pagado, 2) }}€</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Evolución de Ingresos (últimos 6 meses)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Mes</th>
                                    <th>Ingresos</th>
                                    <th>Gráfico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $maxIngresos = $ingresosMensuales->max('total') ?? 1; @endphp
                                @foreach($ingresosMensuales as $item)
                                @php
                                    $width = $maxIngresos > 0 ? ($item->total / $maxIngresos) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->year . '-' . $item->month . '-01')->format('M Y') }}</td>
                                    <td>{{ number_format($item->total, 2) }}€</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $width }}%"
                                                 aria-valuenow="{{ $item->total }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $maxIngresos }}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Título para sección de emails --}}
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4 class="h5 mb-0 text-gray-800">
                <i class="fas fa-envelope mr-2"></i>Estadísticas de Emails
            </h4>
            <hr class="mt-2">
        </div>
    </div>

    {{-- Tarjetas de estadísticas de emails (código existente) --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Emails Enviados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total'']) --}} }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Emails Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['enviados_hoy'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Esta Semana</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['enviados_semana'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Este Mes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['enviados_mes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resto del código existente (secciones de emails) --}}
    {{-- Distribución por contexto --}}
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-project-diagram mr-2"></i>Distribución por Contexto
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Contexto</th>
                                    <th>Cantidad</th>
                                    <th>Porcentaje</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = $stats['total']; @endphp
                                @foreach($porContexto as $item)
                                @php $porcentaje = $total > 0 ? ($item->total / $total) * 100 : 0; @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $item->contexto == 'academia_a_docente' ? 'primary' : 
                                            ($item->contexto == 'docente_a_academia' ? 'success' : 
                                            ($item->contexto == 'registro' ? 'info' : 'warning'))
                                        }}">
                                            {{ $item->contexto }}
                                        </span>
                                    </td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ 
                                                $item->contexto == 'academia_a_docente' ? 'primary' : 
                                                ($item->contexto == 'docente_a_academia' ? 'success' : 
                                                ($item->contexto == 'registro' ? 'info' : 'warning'))
                                            }}" 
                                            role="progressbar" 
                                            style="width: {{ $porcentaje }}%"
                                            aria-valuenow="{{ $porcentaje }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                                {{ number_format($porcentaje, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.email.stats.contexto', $item->contexto) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Distribución por estado --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-check-circle mr-2"></i>Distribución por Estado
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($porEstado as $estado)
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-{{ 
                                $estado->status == 'sent' ? 'success' : 
                                ($estado->status == 'failed' ? 'danger' : 
                                ($estado->status == 'delivered' ? 'info' : 'warning'))
                            }} shadow h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-{{ 
                                                $estado->status == 'sent' ? 'success' : 
                                                ($estado->status == 'failed' ? 'danger' : 
                                                ($estado->status == 'delivered' ? 'info' : 'warning'))
                                            }} text-uppercase mb-1">
                                                {{ ucfirst($estado->status) }}
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estado->total }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-{{ 
                                                $estado->status == 'sent' ? 'paper-plane' : 
                                                ($estado->status == 'failed' ? 'exclamation-triangle' : 
                                                ($estado->status == 'delivered' ? 'check' : 'eye'))
                                            }} fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top destinatarios y evolución --}}
    <div class="row mb-4">
        {{-- Top 10 destinatarios --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-2"></i>Top 10 Destinatarios
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Email Destinatario</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDestinatarios as $index => $destinatario)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <code>{{ $destinatario->destinatario_email }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $destinatario->total }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Evolución últimos 6 meses --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Evolución últimos 6 meses
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Mes</th>
                                    <th>Cantidad</th>
                                    <th>Gráfico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evolucion as $item)
                                @php
                                    $max = $evolucion->max('total');
                                    $width = $max > 0 ? ($item->total / $max) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->mes . '-01')->format('M Y') }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" 
                                                 role="progressbar" 
                                                 style="width: {{ $width }}%"
                                                 aria-valuenow="{{ $item->total }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $max }}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Búsqueda --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-search mr-2"></i>Búsqueda Avanzada
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email.search') }}" method="GET" class="form-inline">
                        <div class="input-group w-100">
                            <select name="tipo" class="form-control mr-2" style="width: 150px;">
                                <option value="email">Por Email</option>
                                <option value="remitente">Por Remitente</option>
                                <option value="contexto">Por Contexto</option>
                            </select>
                            <input type="text" name="busqueda" class="form-control" 
                                   placeholder="Buscar..." required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Últimos emails --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Últimos 50 Emails Enviados
                    </h6>
                    <a href="{{ route('admin.email.stats') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Remitente</th>
                                    <th>Destinatario</th>
                                    <th>Contexto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosEmails as $email)
                                <tr>
                                    <td><small class="text-muted">#{{ $email->id }}</small></td>
                                    <td>
                                        @if($email->remitente_nombre)
                                            {{ $email->remitente_nombre }}
                                            <br>
                                            <small class="text-muted">{{ $email->remitente_email_db }}</small>
                                        @else
                                            <small class="text-muted">ID: {{ $email->remitente_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $email->destinatario_email }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $email->contexto == 'academia_a_docente' ? 'primary' : 
                                            ($email->contexto == 'docente_a_academia' ? 'success' : 
                                            ($email->contexto == 'registro' ? 'info' : 'warning'))
                                        }}">
                                            {{ $email->contexto }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $email->status == 'sent' ? 'success' : 
                                            ($email->status == 'failed' ? 'danger' : 
                                            ($email->status == 'delivered' ? 'info' : 'warning'))
                                        }}">
                                            {{ ucfirst($email->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($email->created_at)->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="verDetalle({{ $email->id }})"
                                                data-toggle="tooltip" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para ver detalles --}}
<div class="modal fade" id="detalleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Email</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleContent">
                Cargando...
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function verDetalle(id) {
    fetch(`/admin/email-stats/detalle/${id}`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información Básica</h6>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Estado:</strong> <span class="badge badge-${data.status === 'sent' ? 'success' : 'danger'}">${data.status}</span></p>
                        <p><strong>Contexto:</strong> ${data.contexto}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Remitente/Destinatario</h6>
                        <p><strong>Remitente ID:</strong> ${data.remitente_id}</p>
                        <p><strong>Destinatario:</strong> ${data.destinatario_email}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Variables (JSON)</h6>
                        <pre class="bg-light p-3 rounded">${JSON.stringify(data.variables, null, 2)}</pre>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Contenido</h6>
                        <div class="card">
                            <div class="card-body">
                                <h5>${data.variables.asunto || 'Sin asunto'}</h5>
                                <hr>
                                <p>${data.variables.mensaje || 'Sin mensaje'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('detalleContent').innerHTML = html;
            $('#detalleModal').modal('show');
        })
        .catch(error => {
            document.getElementById('detalleContent').innerHTML = '<div class="alert alert-danger">Error al cargar los detalles</div>';
        });
}

// Gráfico de ingresos de suscripciones
document.addEventListener('DOMContentLoaded', function() {
    // Si tienes datos para un gráfico más avanzado, puedes añadirlo aquí
    // Por ahora solo mostramos las tablas
    
    // Ejemplo básico de gráfico si decides implementarlo
    const ctx = document.getElementById('graficoIngresos');
    if (ctx) {
        // Tu código para inicializar Chart.js aquí
    }
});
</script>
@endpush

<style>
.card {
    border-radius: 10px;
}
.badge {
    font-size: 0.8em;
    color: black;
}
.progress {
    height: 20px;
}
.progress-bar {
    line-height: 20px;
}
</style>
@endsection --}}