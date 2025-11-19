<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use App\Models\Modulo;

class AdminController extends Controller
{
    public function index()
    {
        $familiasProfesionales = FamiliaProfesional::with(['cursos.modulos.unidades'])->get();
        $modulosDisponibles = Modulo::all();
        
        // CAMBIA admin.panel por admin.index
        return view('admin.index', compact('familiasProfesionales', 'modulosDisponibles'));
    }
}