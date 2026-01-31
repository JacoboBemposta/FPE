<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Suscripcion;
use App\Models\Pago;
use App\Models\User;

class AdminStatsController extends Controller
{
    /**
     * Mostrar todas las estadísticas
     */
    public function index()
    {
        // ========== ESTADÍSTICAS DE USUARIOS ==========
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

        // ========== ESTADÍSTICAS DE EMAILS ==========
        $stats = [
            'total' => DB::table('emails_enviados')->count(),
            'enviados_hoy' => DB::table('emails_enviados')
                ->whereDate('created_at', Carbon::today())
                ->count(),
            'enviados_semana' => DB::table('emails_enviados')
                ->where('created_at', '>=', Carbon::now()->subWeek())
                ->count(),
            'enviados_mes' => DB::table('emails_enviados')
                ->where('created_at', '>=', Carbon::now()->subMonth())
                ->count(),
        ];

        $porContexto = DB::table('emails_enviados')
            ->select('contexto', DB::raw('COUNT(*) as total'))
            ->groupBy('contexto')
            ->orderBy('total', 'desc')
            ->get();

        $porEstado = DB::table('emails_enviados')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $topDestinatarios = DB::table('emails_enviados')
            ->select('destinatario_email', DB::raw('COUNT(*) as total'))
            ->groupBy('destinatario_email')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $evolucion = DB::table('emails_enviados')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $ultimosEmails = DB::table('emails_enviados')
            ->leftJoin('users', 'emails_enviados.remitente_id', '=', 'users.id')
            ->select(
                'emails_enviados.*',
                'users.name as remitente_nombre',
                'users.email as remitente_email_db'
            )
            ->orderBy('emails_enviados.created_at', 'desc')
            ->limit(50)
            ->get();

        // ========== ESTADÍSTICAS DE SUSCRIPCIONES ==========
        $subscriptionStats = $this->getSubscriptionStats();
        $suscripcionesPorPlan = $this->getSubscriptionsByPlan();
        $ingresosMensuales = $this->getMonthlyRevenue();
        $ingresosPorTipo = $this->getRevenueByType();
        $topSuscriptores = $this->getTopSubscribers();

        return view('admin.stats', compact(
            // Datos de usuarios
            'userStats',
            'usuariosPorRol',
            'evolucionUsuarios',
            
            // Datos de emails
            'stats',
            'porContexto',
            'porEstado',
            'topDestinatarios',
            'ultimosEmails',
            'evolucion',
            
            // Datos de suscripciones
            'subscriptionStats',
            'suscripcionesPorPlan',
            'ingresosMensuales',
            'ingresosPorTipo',
            'topSuscriptores'
        ));
    }

    /**
     * Detalle por contexto de emails
     */
    public function detalleContexto($contexto)
    {
        $emails = DB::table('emails_enviados')
            ->leftJoin('users', 'emails_enviados.remitente_id', '=', 'users.id')
            ->where('emails_enviados.contexto', $contexto)
            ->select(
                'emails_enviados.*',
                'users.name as remitente_nombre',
                'users.email as remitente_email_db'
            )
            ->orderBy('emails_enviados.created_at', 'desc')
            ->paginate(25);

        $titulo = match($contexto) {
            'academia_a_docente' => 'Emails de Academia a Docente',
            'docente_a_academia' => 'Emails de Docente a Academia',
            'registro' => 'Emails de Registro',
            'notificacion' => 'Emails de Notificación',
            default => 'Emails de ' . ucfirst($contexto)
        };

        return view('admin.emails.detalle-contexto', compact('emails', 'titulo', 'contexto'));
    }

    /**
     * Búsqueda de emails
     */
    public function buscarEmails(Request $request)
    {
        $request->validate([
            'busqueda' => 'required|string|min:2',
            'tipo' => 'required|in:email,remitente,contexto'
        ]);

        $query = DB::table('emails_enviados')
            ->leftJoin('users', 'emails_enviados.remitente_id', '=', 'users.id');

        switch ($request->tipo) {
            case 'email':
                $query->where('emails_enviados.destinatario_email', 'like', '%' . $request->busqueda . '%');
                break;
            case 'remitente':
                $query->where(function($q) use ($request) {
                    $q->where('users.name', 'like', '%' . $request->busqueda . '%')
                    ->orWhere('users.email', 'like', '%' . $request->busqueda . '%');
                });
                break;
            case 'contexto':
                $query->where('emails_enviados.contexto', 'like', '%' . $request->busqueda . '%');
                break;
        }

        $resultados = $query->select(
            'emails_enviados.*',
            'users.name as remitente_nombre',
            'users.email as remitente_email_db'
        )
        ->orderBy('emails_enviados.created_at', 'desc')
        ->paginate(25);

        return view('admin.emails.busqueda', compact('resultados'));
    }

    /**
     * Detalle de email individual
     */
    public function detalleEmail($id)
    {
        $email = DB::table('emails_enviados')
            ->leftJoin('users', 'emails_enviados.remitente_id', '=', 'users.id')
            ->where('emails_enviados.id', $id)
            ->select(
                'emails_enviados.*',
                'users.name as remitente_nombre',
                'users.email as remitente_email_db'
            )
            ->first();

        if (!$email) {
            abort(404);
        }

        return response()->json([
            'id' => $email->id,
            'status' => $email->status,
            'contexto' => $email->contexto,
            'remitente_id' => $email->remitente_id,
            'destinatario_email' => $email->destinatario_email,
            'variables' => json_decode($email->variables, true) ?? []
        ]);
    }

    /**
     * Métodos para estadísticas de suscripciones
     */
    
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

    /**
     * Obtener datos para gráficos (API)
     */
    public function datosGraficos(Request $request)
    {
        $rango = $request->get('rango', '6m'); // 6m, 1y, all

        switch ($rango) {
            case '1y':
                $fechaInicio = Carbon::now()->subYear();
                break;
            case 'all':
                $fechaInicio = Carbon::create(2020, 1, 1);
                break;
            default: // 6m
                $fechaInicio = Carbon::now()->subMonths(6);
        }

        // Ingresos mensuales
        $ingresosMensuales = Pago::select(
                DB::raw('DATE_FORMAT(fecha_pago, "%Y-%m") as mes'),
                DB::raw('SUM(monto_total) as total')
            )
            ->where('estado', 'completado')
            ->where('fecha_pago', '>=', $fechaInicio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Suscripciones nuevas por mes
        $suscripcionesNuevas = Suscripcion::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $fechaInicio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return response()->json([
            'ingresos_mensuales' => $ingresosMensuales,
            'suscripciones_nuevas' => $suscripcionesNuevas,
        ]);
    }
}