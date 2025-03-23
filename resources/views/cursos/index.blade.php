@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Cursos Disponibles para Asignar</h1>

    <!-- Tabla de Cursos Disponibles -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Curso</th>
                <th>Horas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursosDisponibles as $curso)
                <tr>
                    <td>{{ $curso->codigo }}</td>
                    <td>{{ $curso->nombre }}</td>
                    <td>{{ $curso->horas }}</td>
                    <td>
                        <!-- Formulario para asignar curso -->
                        <form action="{{ route('academia.asignar_curso', $curso->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Asignar Curso</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay cursos disponibles para asignar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
