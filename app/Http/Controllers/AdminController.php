<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use App\Models\Modulo;
use App\Helpers\SistemaHelper;

class AdminController extends Controller
{
    public function index()
    {
        $familiasProfesionales = FamiliaProfesional::withCount('cursos')->get();
        $sistema_suscripciones_activo = SistemaHelper::sistemaSuscripcionesActivo();
        return view('admin.index', compact('familiasProfesionales', 'sistema_suscripciones_activo'));
    }
}