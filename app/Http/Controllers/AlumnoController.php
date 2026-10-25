<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CursoAcademico;
use App\Models\FamiliaProfesional;
use App\Traits\EnviaEmails;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    use EnviaEmails;

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        // Obtener todos los cursos académicos
        $query = CursoAcademico::with(['curso.familiaProfesional', 'academia'])
            ->whereHas('academia', function($q) {
                $q->where('rol', 'academia');
            })
            ->where('curso_academicos.archivado', false);

        // Filtro por nombre de academia
        if ($request->filled('academia_nombre')) {
            $query->whereHas('academia', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->academia_nombre . '%');
            });
        }

        // Filtro por código de curso
        if ($request->filled('curso_codigo')) {
            $query->whereHas('curso', function($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->curso_codigo . '%');
            });
        }

        // Filtro por nombre de curso
        if ($request->filled('curso_nombre')) {
            $query->whereHas('curso', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->curso_nombre . '%');
            });
        }

        // Filtro por provincia
        if ($request->filled('provincia')) {
            $query->where('provincia', 'like', '%' . $request->provincia . '%');
        }

        // Filtro por municipio
        if ($request->filled('municipio')) {
            $query->where('municipio', 'like', '%' . $request->municipio . '%');
        }

        // Filtro por familia profesional
        if ($request->filled('familia')) {
            $query->whereHas('curso', function($q) use ($request) {
                $q->where('familia_profesional_id', $request->familia);
            });
        }

        $cursosAcademicos = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $familias = FamiliaProfesional::all();

        return view('alumno.index', compact('cursosAcademicos', 'familias'));
    }

    public function listarAcademias()
    {
        $academias = User::where('rol', 'academia')->paginate(10);
        return view('alumno.academias', compact('academias'));
    }

    public function verAcademia($id)
    {
        $academia = User::where('rol', 'academia')->findOrFail($id);
        
        // Obtener cursos académicos de esta academia
        $cursosAcademicos = CursoAcademico::where('academia_id', $id)
            ->with(['curso.familiaProfesional'])
            ->where('archivado', false)
            ->get();
        
        return view('alumno.academia', compact('academia', 'cursosAcademicos'));
    }

    public function enviarEmailAcademia(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|max:5000',
        ]);

        $academia = User::where('email', $request->email)->where('rol', 'academia')->first();
        if (!$academia) {
            return back()->with('error', 'El destinatario no es una academia válida.');
        }

        $datos = [
            'destinatario_email' => $request->email, 
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'contexto' => 'alumno_a_academia',
            'destinatario_tipo' => 'academia',
        ];

        $enviado = $this->enviarEmailRegistrado($datos);

        if ($enviado) {
            return redirect()->route('alumno.index')
                ->with('success', 'Mensaje enviado correctamente a la academia.');
        } else {
            return back()->with('error', 'Error al enviar el mensaje. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Obtener email de academia por AJAX (para el modal de contacto)
     */
    public function obtenerEmailAcademia($id)
    {
        try {
            $academia = User::where('rol', 'academia')->find($id);
            
            if (!$academia) {
                return response()->json([
                    'error' => 'Academia no encontrada'
                ], 404);
            }
            
            return response()->json([
                'email' => $academia->email,
                'nombre' => $academia->name
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener email de academia: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error al obtener el email de la academia'
            ], 500);
        }
    }
}