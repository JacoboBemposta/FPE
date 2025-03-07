<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamiliaProfesional;
use App\Models\Curso;


    class AdminController extends Controller
    {
        public function index()
        {
            // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
            $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();

            // Pasar la variable a la vista
            return view('admin.index', compact('familias_profesionales'));
        }
    }
