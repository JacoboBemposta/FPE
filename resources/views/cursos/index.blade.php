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
                        @auth
                        @if(Auth::user()->rol === 'profesor')
                            {{-- @dd(Auth::user()->rol) --}}
                            <form action="{{ route('profesor.asignar_curso', $curso->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Asignar Curso</button>
                            </form>
                        @elseif(Auth::user()->rol === 'academia')
                            <form action="{{ route('academia.asignar_curso', $curso->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Asignar Curso</button>
                            </form>
                        @endif
                    @endauth
                    
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay cursos disponibles para asignar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="text-center mt-4">
        @if(Auth::user()->rol === 'academia')
            <a href="{{ route('academia.miscursos') }}" class="btn btn-secondary">Volver</a>
        @elseif(Auth::user()->rol === 'profesor')
            <a href="{{ route('profesor.miscursos') }}" class="btn btn-secondary">Volver</a>
        @else
            <!-- Puedes poner una redirección por defecto o manejarlo de otra manera -->
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
        @endif
    </div>

</div>
@endsection
