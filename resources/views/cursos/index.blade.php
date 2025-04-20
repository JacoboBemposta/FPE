@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Cursos Disponibles para Asignar</h1>

    <!-- Listado de Familias Profesionales con Cursos y Módulos -->
    <div class="accordion" id="familiasAccordion">
        @forelse($familias_profesionales as $familia)
        <div class="card mb-3">
            <div class="card-header" id="familiaHeading{{ $familia->id }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center" 
                            type="button" 
                            data-toggle="collapse" 
                            data-target="#familiaCollapse{{ $familia->id }}" 
                            aria-expanded="false" 
                            aria-controls="familiaCollapse{{ $familia->id }}">
                        <span>
                            <strong>{{ $familia->codigo }}</strong> - {{ $familia->nombre }}
                        </span>
                        <span class="badge badge-primary">{{ $familia->cursos->count() }} cursos</span>
                    </button>
                </h2>
            </div>

            <div id="familiaCollapse{{ $familia->id }}" class="collapse" aria-labelledby="familiaHeading{{ $familia->id }}" data-parent="#familiasAccordion">
                <div class="card-body">
                    @foreach($familia->cursos as $curso)
                    <div class="card mb-2">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $curso->codigo }}</strong> - {{ $curso->nombre }} ({{ $curso->horas }}h)
                            </div>
                            <div>
                                <form action="{{ route('academia.asignar_curso', $curso->id) }}" method="POST" class="d-inline">                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Asignar Curso</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info">
            No hay familias profesionales con cursos disponibles.
        </div>
        @endforelse
    </div>

    <!-- Botón Volver -->
    <div class="text-center mt-4">
        @if(Auth::user()->rol === 'academia')
            <a href="{{ route('academia.miscursos') }}" class="btn btn-secondary">Volver</a>
        @elseif(Auth::user()->rol === 'profesor')
            <a href="{{ route('profesor.miscursos') }}" class="btn btn-secondary">Volver</a>
        @else
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
        @endif
    </div>
</div>
@endsection