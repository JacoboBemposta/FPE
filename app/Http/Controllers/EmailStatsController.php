<!-- <?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailStatsController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total' => DB::table('emails_enviados')->count(),
            'enviados_hoy' => DB::table('emails_enviados')
                ->whereDate('created_at', today())
                ->count(),
            'enviados_semana' => DB::table('emails_enviados')
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'enviados_mes' => DB::table('emails_enviados')
                ->where('created_at', '>=', now()->subMonth())
                ->count(),
        ];

        // Estadísticas de usuarios
        $userStats = [
            'total_usuarios' => DB::table('users')->count(),
            'total_academias' => DB::table('users')->where('rol', 'academia')->count(),
            'total_profesores' => DB::table('users')->where('rol', 'profesor')->count(),
            'total_alumnos' => DB::table('users')->where('rol', 'alumno')->count(),
            'total_admins' => DB::table('users')->where('rol', 'admin')->count(),
            'usuarios_activos' => DB::table('users')->where('activo', true)->count(),
            'usuarios_inactivos' => DB::table('users')->where('activo', false)->count(),
        ];

        // Distribución de usuarios por rol (para gráfico)
        $usuariosPorRol = DB::table('users')
            ->select('rol', DB::raw('COUNT(*) as total'))
            ->whereNotNull('rol')
            ->groupBy('rol')
            ->orderBy('total', 'desc')
            ->get();

        // Evolución de usuarios registrados (últimos 6 meses)
        $evolucionUsuarios = DB::table('users')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Estadísticas por contexto
        $porContexto = DB::table('emails_enviados')
            ->select('contexto', DB::raw('COUNT(*) as total'))
            ->groupBy('contexto')
            ->orderBy('total', 'desc')
            ->get();

        // Estadísticas por estado
        $porEstado = DB::table('emails_enviados')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Top destinatarios
        $topDestinatarios = DB::table('emails_enviados')
            ->select('destinatario_email', DB::raw('COUNT(*) as total'))
            ->groupBy('destinatario_email')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Últimos emails
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

        // Evolución mensual de emails
        $evolucion = DB::table('emails_enviados')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return view('admin.emails.email-stats', compact(
            'stats',
            'userStats',
            'usuariosPorRol',
            'evolucionUsuarios',
            'porContexto',
            'porEstado',
            'topDestinatarios',
            'ultimosEmails',
            'evolucion'
        ));
    }

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

    public function buscar(Request $request)
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
} -->