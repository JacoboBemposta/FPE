<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use App\Models\CursoAcademico;
use App\Models\AlumnoCurso;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AcademiaController extends Controller
{
    public function index()
    {
        // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();

        // Pasar la variable a la vista
        return view('academia.index', compact('familias_profesionales'));
    }

    public function misCursos()
    {
        $user = Auth::user();
    
        // Verificar que el usuario esté autenticado
        if (!$user) {
            return redirect()->route('login');
        }
    
        // Obtener los cursos académicos del usuario (relación muchos a muchos)
        $misCursos = $user->misCursos()->with(['curso', 'curso.FamiliaProfesional'])->get();
        $alumnos = AlumnoCurso::whereIn('curso_academico_id', $misCursos->pluck('id'))->get();

        // Pasar los cursos a la vista
        return view('academia.index', compact('misCursos','alumnos'));
    }
    
    public function getAlumnos($id)
    {
        // Obtén el curso académico por ID
        $cursoAcademico = CursoAcademico::find($id);
        
        // Verifica si existe el curso académico
        if (!$cursoAcademico) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }
    
        // Obtén los alumnos relacionados con ese curso
        $alumnos = $cursoAcademico->alumnos;
    
        return response()->json($alumnos);
    }
    
    public function detalleCurso($id)
    {
        // Obtén el curso académico junto con sus relaciones necesarias
        $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])->findOrFail($id);
        
        // Retorna la vista que mostrará los detalles del curso
        return view('academia.detalle_curso', compact('cursoAcademico'));
    }

    public function actualizarCurso(Request $request, $id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);

        // Validación de los campos del formulario
        $request->validate([
            'municipio' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'inicio' => 'nullable|date',
            'fin' => 'nullable|date',
        ]);

        // Actualización de los campos en el modelo
        $cursoAcademico->update([
            'municipio' => strip_tags($request->municipio),
            'provincia' => strip_tags($request->provincia),
            'inicio' => $request->inicio,
            'fin' => $request->fin,
        ]);
        


        $user = Auth::user();
        $misCursos = $user->misCursos()->with(['curso', 'curso.FamiliaProfesional'])->get();
        $alumnos = AlumnoCurso::whereIn('curso_academico_id', $misCursos->pluck('id'))->get();
   
        
        return view('academia.index', compact('misCursos','alumnos'));
    }

    public function guardarCursoAcademico(Request $request)
    {
        // Validamos que se reciba un curso académico válido
        $request->validate([
            'curso_academico_id' => 'required|exists:curso_academicos,id',  // Verificamos que el curso exista en la base de datos
        ]);

        // Guardamos la relación en la tabla alumnos_curso
        $alumnoCurso = new AlumnoCurso();
        $alumnoCurso->curso_academico_id = $request->curso_academico_id;  // Asignamos el curso académico
        $alumnoCurso->save();  // Guardamos la relación

        $user = Auth::user();
        $misCursos = $user->misCursos()->with(['curso', 'curso.FamiliaProfesional'])->get();
        $alumnos = AlumnoCurso::whereIn('curso_academico_id', $misCursos->pluck('id'))->get();

        
        return view('academia.index', compact('misCursos','alumnos'));
    }


public function guardarAlumno(Request $request)
{
    $dni = strip_tags($request->dni);
    $nombre = strip_tags($request->nombre);
    $email = strip_tags($request->email);
    $telefono = strip_tags($request->telefono);
    
    // Validación de los datos del formulario
    $request->validate([
        'dni' => 'required|string|max:15',
        'nombre' => 'required|string|max:255',
        'email' => 'required|email',
        'telefono' => 'nullable|string|max:20',
        'curso_academico_id' => 'required|exists:curso_academicos,id',
    ]);


    $cursoAcademicoId = $request->curso_academico_id;

    // Creación del nuevo alumno en la base de datos
    AlumnoCurso::create([
        'dni' => $dni,
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono,
        'curso_academico_id' => $cursoAcademicoId,
    ]);

    // Cargar el curso académico junto con sus relaciones usando el ID correcto
    $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])
        ->findOrFail($cursoAcademicoId);
    
    // Retorna la vista con los detalles del curso
    return view('academia.detalle_curso', compact('cursoAcademico'));
}


public function actualizarAlumno(Request $request, $id)
{
   
    // Limpiar los datos con strip_tags() para eliminar las etiquetas HTML
    $dni = strip_tags($request->dni);
    $nombre = strip_tags($request->nombre);
    $email = strip_tags($request->email);
    $telefono = strip_tags($request->telefono);
 
    // Validación de los datos (sin permitir HTML)
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email',
        'telefono' => 'nullable|string|max:20',
    ]);

    // Verificar si alguna de las entradas tiene una longitud de solo espacios o está vacía después de eliminar las etiquetas HTML
    if (empty($dni) || empty($nombre) || empty($email)) {
        return redirect()->back()->withErrors(['error' => 'Los campos no pueden contener solo etiquetas HTML.'])->withInput();
    }

    // Intentar actualizar el alumno con los datos limpios
    try {
        $alumno = AlumnoCurso::findOrFail($id);
        $alumno->update([
            'dni' => $dni,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
        ]);
    } catch (\Exception $e) {
        // Si hay un error al actualizar, mostrar un mensaje adecuado
        return redirect()->back()->withErrors(['error' => 'Hubo un problema al intentar actualizar el alumno.'])->withInput();
    }

    // Si todo salió bien, redirigir a la vista del curso actualizado
    $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])
        ->findOrFail($request->curso_academico_id);

    return view('academia.detalle_curso', compact('cursoAcademico'));
}


public function eliminarAlumno($id)
{
    // Buscar el alumno por su ID
    $alumno = AlumnoCurso::find($id);

    if (!$alumno) {
        return response()->json(['error' => 'Alumno no encontrado'], 404);
    }

    // Obtener el ID del curso antes de eliminar el alumno
    $cursoAcademicoId = $alumno->curso_academico_id;

    // Eliminar el alumno
    $alumno->delete();
    
    // Cargar el curso académico junto con sus relaciones necesarias
    $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])
        ->findOrFail($cursoAcademicoId);
    
    // Retornar la vista con los detalles del curso actualizado
    return view('academia.detalle_curso', compact('cursoAcademico'));
}

        // /**
    //  * Muestra la lista de cursos con el buscador avanzado.
    //  */
    // public function cursos(Request $request)
    // {
    //     // Obtener todas las familias con sus cursos y módulos
    //     $familias_profesionales = FamiliaProfesional::with('cursos.modulos')->get();

    //     // Filtrar por familia o curso si se ha seleccionado en el buscador
    //     if ($request->filled('familia_id')) {
    //         $familias_profesionales = $familias_profesionales->where('id', $request->familia_id);
    //     }

    //     if ($request->filled('curso_id')) {
    //         $familias_profesionales->each(function ($familia) use ($request) {
    //             $familia->cursos = $familia->cursos->where('id', $request->curso_id);
    //         });
    //     }

    //     return view('academia.cursos', compact('familias_profesionales'));
    // }

    /**
     * Asigna un curso al usuario autenticado.
     */
    // public function asignarCurso($curso_id)
    // {
    //     $user = Auth::user();
        
    //     // Verificar que el usuario tiene el rol de "academia"
    //     if ($user->rol !== 'academia') {
    //         return redirect()->back()->with('error', 'No tienes permiso para asignar cursos.');
    //     }

    //     $curso = Curso::findOrFail($curso_id);

    //     // Verificar si el curso ya está asignado
    //     if ($user->misCursos()->where('curso_id', $curso_id)->exists()) {
    //         return redirect()->back()->with('warning', 'El curso ya está asignado.');
    //     }

    //     // Asignar el curso al usuario
    //     $user->misCursos()->syncWithoutDetaching([$curso_id]); // Evita duplicados


    //     return redirect()->back()->with('success', 'Curso asignado con éxito.');
    // }

    // /**
    //  * Muestra los cursos asignados al usuario en academia/index.blade.php.
    //  */
}
