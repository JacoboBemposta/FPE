<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Suscripcion;
use App\Models\Pago;
use App\Models\User;

class EstadisticasSuscripcionesController extends Controller
{
    /**
     * Obtener estadísticas generales de suscripciones
     */
    public function index()
    {
        // ========== ESTADÍSTICAS DE USUARIOS (EXISTENTES) ==========
        // ... tu código existente para usuarios ...
        $userStats = [
            'total_usuarios' => User::count(),
            'total_academias' => User::where('rol', 'academia')->count(),
            'total_profesores' => User::where('rol', 'profesor')->count(),
            'total_alumnos' => User::where('rol', 'alumno')->count(),
            'usuarios_activos' => User::where('activo', true)->count(),
            'usuarios_inactivos' => User::where('activo', false)->count(),
        ];

        $usuariosPorRol = User::select('rol', DB::raw('count(*) as total'))
            ->groupBy('rol')
            ->get();

        $evolucionUsuarios = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // ========== ESTADÍSTICAS DE EMAILS (EXISTENTES) ==========
        // ... tu código existente para emails ...
        $stats = [
            'total' => DB::table('emails')->count(),
            'enviados_hoy' => DB::table('emails')->whereDate('created_at', Carbon::today())->count(),
            'enviados_semana' => DB::table('emails')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'enviados_mes' => DB::table('emails')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count(),
        ];

        $porContexto = DB::table('emails')
            ->select('contexto', DB::raw('count(*) as total'))
            ->groupBy('contexto')
            ->get();

        $porEstado = DB::table('emails')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $topDestinatarios = DB::table('emails')
            ->select('destinatario_email', DB::raw('count(*) as total'))
            ->groupBy('destinatario_email')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $evolucion = DB::table('emails')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $ultimosEmails = DB::table('emails')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // ========== ESTADÍSTICAS DE SUSCRIPCIONES (NUEVAS) ==========
        $subscriptionStats = $this->getSubscriptionStats();
        $suscripcionesPorPlan = $this->getSubscriptionsByPlan();
        $ingresosMensuales = $this->getMonthlyRevenue();
        $ingresosPorTipo = $this->getRevenueByType();
        $topSuscriptores = $this->getTopSubscribers();

        return view('admin.stats', compact(
            // Datos existentes
            'userStats',
            'usuariosPorRol',
            'evolucionUsuarios',
            'stats',
            'porContexto',
            'porEstado',
            'topDestinatarios',
            'evolucion',
            'ultimosEmails',
            // Nuevos datos de suscripciones
            'subscriptionStats',
            'suscripcionesPorPlan',
            'ingresosMensuales',
            'ingresosPorTipo',
            'topSuscriptores'
        ));
    }

    /**
     * Obtener estadísticas generales de suscripciones
     */
    private function getSubscriptionStats()
    {
        try {
            return [
                'total_suscripciones' => Suscripcion::count(),
                'suscripciones_activas' => Suscripcion::where('estado', 'activa')->count(),
                'suscripciones_por_vencer' => Suscripcion::where('estado', 'activa')
                    ->whereDate('fecha_fin', '<=', Carbon::now()->addDays(7))
                    ->count(),
                'ingresos_mes_actual' => Pago::where('estado', 'completado')
                    ->whereMonth('fecha_pago', Carbon::now()->month)
                    ->whereYear('fecha_pago', Carbon::now()->year)
                    ->sum('monto_total'),
                'ingresos_anio_actual' => Pago::where('estado', 'completado')
                    ->whereYear('fecha_pago', Carbon::now()->year)
                    ->sum('monto_total'),
                'ingresos_totales' => Pago::where('estado', 'completado')->sum('monto_total'),
            ];
        } catch (\Exception $e) {
            return [
                'total_suscripciones' => 0,
                'suscripciones_activas' => 0,
                'suscripciones_por_vencer' => 0,
                'ingresos_mes_actual' => 0,
                'ingresos_anio_actual' => 0,
                'ingresos_totales' => 0,
            ];
        }
    }

    /**
     * Obtener suscripciones por plan
     */
    private function getSubscriptionsByPlan()
    {
        try {
            return Suscripcion::select('plan', DB::raw('count(*) as total'))
                ->groupBy('plan')
                ->orderBy('total', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Obtener ingresos mensuales últimos 6 meses
     */
    private function getMonthlyRevenue()
    {
        try {
            return Pago::select(
                    DB::raw('YEAR(fecha_pago) as year'),
                    DB::raw('MONTH(fecha_pago) as month'),
                    DB::raw('SUM(monto_total) as total')
                )
                ->where('estado', 'completado')
                ->where('fecha_pago', '>=', Carbon::now()->subMonths(6))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Obtener ingresos por tipo de usuario
     */
    private function getRevenueByType()
    {
        try {
            return User::select(
                    DB::raw("CASE 
                        WHEN users.rol = 'academia' THEN 'academia' 
                        WHEN users.rol = 'profesor' THEN 'docente' 
                        ELSE 'otros' 
                    END as tipo"),
                    DB::raw('COUNT(suscripciones.id) as total_suscripciones'),
                    DB::raw('COALESCE(SUM(pagos.monto_total), 0) as total_ingresos')
                )
                ->leftJoin('suscripciones', 'users.id', '=', 'suscripciones.user_id')
                ->leftJoin('pagos', function($join) {
                    $join->on('suscripciones.id', '=', 'pagos.suscripcion_id')
                        ->where('pagos.estado', 'completado');
                })
                ->whereIn('users.rol', ['profesor', 'academia'])
                ->groupBy('tipo')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Obtener top 10 suscriptores
     */
    private function getTopSubscribers()
    {
        try {
            return User::select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.rol',
                    DB::raw('COALESCE(SUM(pagos.monto_total), 0) as total_pagado')
                )
                ->leftJoin('suscripciones', 'users.id', '=', 'suscripciones.user_id')
                ->leftJoin('pagos', function($join) {
                    $join->on('suscripciones.id', '=', 'pagos.suscripcion_id')
                        ->where('pagos.estado', 'completado');
                })
                ->whereIn('users.rol', ['profesor', 'academia'])
                ->groupBy('users.id', 'users.name', 'users.email', 'users.rol')
                ->orderBy('total_pagado', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}